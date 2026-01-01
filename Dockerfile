FROM php:8.2-fpm

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libjpeg-dev \
    libfreetype6-dev

# 2. Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 5. Set working directory
WORKDIR /var/www

# 6. Copy existing application directory contents
COPY . /var/www

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 8. Install NPM dependencies & Build Assets
RUN npm install --legacy-peer-deps && npm run build

# 9. تهيئة ملف القاعدة والصلاحيات (عشان الـ 500 Error تروح)
RUN mkdir -p database && touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

# 10. تشغيل الهجرة (Migrate) وربط التخزين والسيرفر
EXPOSE 80
CMD php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=80
