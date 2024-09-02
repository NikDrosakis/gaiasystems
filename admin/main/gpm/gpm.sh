#!/bin/bash

# Directory to monitor (recursively)
MONITOR_DIR="/var/www/vivalibro/widgets"

# Database credentials
DB_USER="root"
DB_PASSWORD="n130177!"
DB_NAME="gpm"
DB_HOST="localhost"

# Process file changes
process_changes() {
    local path="$1"
    local action="$2"
    local file="$3"
    echo "Processing change: $path $action $file" >> /var/log/widget_monitor_debug.log

    widget_name=$(basename "$(dirname "$path")")
    widget_id=$(mysql -u $DB_USER -p$DB_PASSWORD -h $DB_HOST -se "SELECT id FROM widgets WHERE name='$widget_name'" $DB_NAME)
    
    if [ -z "$widget_id" ]; then
        mysql -u $DB_USER -p$DB_PASSWORD -h $DB_HOST $DB_NAME <<EOF
        INSERT INTO widgets (name) VALUES ('$widget_name');
EOF
        widget_id=$(mysql -u $DB_USER -p$DB_PASSWORD -h $DB_HOST -se "SELECT id FROM widgets WHERE name='$widget_name'" $DB_NAME)
    fi
    
    mysql -u $DB_USER -p$DB_PASSWORD -h $DB_HOST $DB_NAME <<EOF
    INSERT INTO cubo_actions (widget_id, file, action, timestamp) 
    VALUES ('$widget_id', '$file', '$action', NOW())
    ON DUPLICATE KEY UPDATE 
    file='$file', action='$action', timestamp=NOW();
EOF
}

# Using fswatch to monitor directory changes
fswatch -r "$MONITOR_DIR" | while read -r path; do
    echo "File changed: $path" >> /var/log/widget_monitor_debug.log
    
    # Extract action and file information
    file=$(basename "$path")
    dir=$(dirname "$path")
    action="MODIFY" # Default action; adjust if needed based on your needs

    # Process the changes
    process_changes "$dir" "$action" "$file"
done
