#!/bin/bash

# logging.sh is a cron running every one hour so as to get the changes (without pushing remote) 
#for each root folder of  that it is project 
# STEP 1 perform  git add (and need to commit??? to get from git log) 
# get last current max version_id froom database 
# INSERT INTO system_log changes and diff for separate folders in gaiasystems.git locally in /var/www/gs

 
# Detailed Commit Information in column summary for each (/var/www/gs/)folder=(system.name)
#INSERT INTO 
# CREATE TABLE `system_log` (
  # `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  # `system_id` int(10) UNSIGNED NOT NULL,
  # `version_id` decimal(5,2) UNSIGNED NOT NULL DEFAULT 0.00,
  # `summary` longtext DEFAULT NULL,
  # `files_changed` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,   
  # `new_files` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0'
  # `created` datetime NOT NULL DEFAULT current_timestamp()
# ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

# CREATE TABLE `systems` (
  # `id` smallint(10) UNSIGNED NOT NULL,
  # `name` varchar(100) NOT NULL,
  # `created` datetime NOT NULL DEFAULT current_timestamp(),
  # `modified` datetime NOT NULL DEFAULT current_timestamp(),
  # `version` decimal(5,2) UNSIGNED NOT NULL DEFAULT 0.00,
  # `description` text DEFAULT NULL,
  # `engineer` text DEFAULT NULL,
  # `functionality` text DEFAULT NULL,
  # `experience` text DEFAULT NULL,
  # `scalability_level` text DEFAULT NULL,
  # `construction_level` smallint(6) DEFAULT 0
# ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
# INSERT INTO `systems` (`id`, `name`, `created`, `modified`, `version`, `description`, `engineer`, `functionality`, `experience`, `scalability_level`, `construction_level`) VALUES
# (1, 'vivalibro', '2024-08-28 00:00:03', '2024-08-28 03:43:41', 0.00, 'Vivalibro Web Application', 'Nik Drosakis', 'Frontend User Interface and Book Management', 'React, JavaScript, HTML, CSS', 'Medium', 80),
# (2, 'vlmob', '0000-00-00 00:00:00', '2024-08-28 03:43:41', 0.00, 'Vivalibro Mobile Application', 'Nik Drosakis', 'Mobile UI and Book Scanning', 'React Native, JavaScript', 'High', 60),
# (3, 'wsi', '0000-00-00 00:00:00', '2024-08-28 03:43:41', 0.00, 'API Web Services', 'Nik Drosakis', 'Provides API endpoints for data access and interactions', 'Node.js, Express.js, RESTful APIs', 'High', 90),
# (4, 'gpm', '0000-00-00 00:00:00', '2024-08-28 03:43:41', 0.00, 'Gaia Package Manager', 'Nik Drosakis', 'Manages project dependencies and automation', 'Shell scripting, Package Management', 'Low', 50),
# (5, 'admin', '0000-00-00 00:00:00', '2024-08-28 03:43:41', 0.00, 'Administration Panel', 'Nik Drosakis', 'Provides administrative tools for managing the application', 'PHP, MySQL, HTML, CSS', 'Medium', 70),
# (6, 'poetabook', '2024-08-27 15:00:50', '2024-08-28 03:43:41', 0.00, 'Poetabook Web Application', 'Nik Drosakis', 'Frontend UI for poetry management', 'React, JavaScript, HTML, CSS', 'Medium', 40),
# (7, 'gpy', '2024-08-27 23:18:07', '2024-08-28 03:43:41', 0.00, 'API with Python', 'Nik Drosakis', 'Provides API endpoints using Python', 'Python, Flask, RESTful APIs', 'Medium', 30),
# (8, 'games', '2024-08-28 01:29:51', '2024-08-28 03:43:41', 0.00, 'Games Module', 'Nik Drosakis', 'Provides games functionality (chess, etc.)', 'JavaScript, Game Development Frameworks', 'Low', 20),
# (9, 'cubos', '2024-08-28 01:29:51', '2024-08-28 03:43:41', 0.00, 'Widgets of VLWEB VLMOB PBWEB PBMOB decoupled in /cubos', 'Nik Drosakis', 'Provides modular widgets', 'PHP class system integrated in ADMIN, controlled by GPM sybsystem', 'High', 20),
# (11, 'core', '2024-09-08 18:02:07', '2024-09-08 18:02:07', 0.00, 'PHP Classes, the core engine of gaiasystems\r\n', 'NikDrosakis', 'updated to 8.3, fully working', NULL, NULL, 3);

#!/bin/bash

# Parameters
DB_USER="root"
DB_PASS="n130177!"
DB_NAME="gpm"
DB_HOST="localhost"
LOCAL_REPO_PATH="/var/www/gs"

# Function to get the maximum version_id from the database
get_max_version_id() {
    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" -D"$DB_NAME" -se "SELECT MAX(version_id) FROM system_log"
}

# Function to insert a log entry into the system_log table
insert_log_entry() {
    local system_id=$1
    local version_id=$2
    local summary=$3
    local files_changed=$4
    local new_files=$5
    mysql -u"$DB_USER" -p"$DB_PASS" -h"$DB_HOST" -D"$DB_NAME" -e "
    INSERT INTO system_log (system_id, version_id, summary, files_changed, new_files) 
    VALUES ($system_id, $version_id, '$summary', $files_changed, $new_files);"
}

# Change to the repository directory
cd "$LOCAL_REPO_PATH" || exit 1

# Add all changes
git add .

# Get the commit summary
commit_summary=$(git log -1 --pretty=%B)

# Get the number of files changed
files_changed=$(git diff --name-only HEAD~1 | wc -l)

# Get the number of new files
new_files=$(git diff --name-status HEAD~1 | grep '^A' | wc -l)

# Get the current max version_id from the database
MAX_VERSION_ID=$(get_max_version_id)

# Set the new version_id to the current max version_id + 0.01
NEW_VERSION_ID=$(echo "$MAX_VERSION_ID + 0.01" | bc)

# Insert the log entry for each system
insert_log_entry 1 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 2 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 3 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 4 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 5 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 6 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 7 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 8 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 9 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"
insert_log_entry 11 "$NEW_VERSION_ID" "$commit_summary" "$files_changed" "$new_files"

echo "Logging completed successfully."

