# Use an official PHP image with FPM
FROM php:8.1-fpm

# Install system dependencies & SQLite extension
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
  && docker-php-ext-install pdo pdo_sqlite \
  && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Ensure the SQLite database file exists and is writeable
RUN touch database/database.sqlite \
    && chmod 664 database/database.sqlite

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Cache configs & routes
RUN php artisan config:cache \
    && php artisan route:cache

# Expose port 8000
EXPOSE 8000

# Start Laravel’s built‑in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
