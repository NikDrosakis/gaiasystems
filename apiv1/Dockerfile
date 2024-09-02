FROM php:8.3-cli

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy your GPM code
COPY . .
COPY ../core/ ./core/
# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# (Optional) Set the entrypoint if GPM is a command-line tool
ENTRYPOINT ["php", "index.php"]