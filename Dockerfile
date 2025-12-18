# Use PHP 8.2 as the base image
FROM php:8.2-cli

# 1. Install system dependencies (Postgres drivers, Node.js for Vue, etc.)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    nodejs \
    npm

# 2. Install PHP extensions for Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring

# 3. Get Composer (PHP Package Manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www

# 5. Copy all project files into the container
COPY . .

# 6. Install PHP & Node dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# 7. Start the server
CMD php artisan serve --host=0.0.0.0 --port=10000