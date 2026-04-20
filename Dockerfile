FROM php:8.4-apache

# 1. Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite module
RUN a2enmod rewrite

# 3. Configure Apache for Render (Port 10000 and Public Document Root)
# Fixed the "sites-available" typo here
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:10000>/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# 4. Allow .htaccess for Laravel
RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# 5. Install Node.js (for frontend assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 6. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 7. Set working directory
WORKDIR /var/www/html

# 8. Copy application code
COPY . .

# 9. Install PHP & Node dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# 10. Clean up Laravel caches
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# 11. Create storage symlink
RUN php artisan storage:link || true

# 12. Fix permissions for storage and cache
RUN mkdir -p storage/framework/cache storage/framework/sessions \
    storage/framework/views bootstrap/cache public/uploads \
    && chown -R www-data:www-data storage bootstrap/cache public/uploads \
    && chmod -R 775 storage bootstrap/cache public/uploads

# 13. Final configuration
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]