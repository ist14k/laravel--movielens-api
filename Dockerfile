# ================================
# 1️⃣ Base image
# ================================
FROM php:8.3-fpm

# ================================
# 2️⃣ Install system dependencies
# ================================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# ================================
# 3️⃣ Install Composer
# ================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ================================
# 4️⃣ Set working directory
# ================================
WORKDIR /var/www/html

# ================================
# 5️⃣ Copy project files
# ================================
COPY . .

# ================================
# 6️⃣ Install PHP dependencies
# ================================
RUN composer install --no-dev --optimize-autoloader

# ================================
# 7️⃣ Laravel setup
# ================================
# Cache config and routes for faster performance
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# ================================
# 8️⃣ Permissions for storage & bootstrap
# ================================
RUN chmod -R 775 storage bootstrap/cache || true

# ================================
# 9️⃣ Expose port
# ================================
EXPOSE 10000

# ================================
# 🔟 Start Laravel server
# ================================
CMD php artisan migrate:fresh --seed --force && php artisan serve --host=0.0.0.0 --port=10000

