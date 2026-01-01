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

# 4. Install Node.js (ضروري جداً عشان التنسيق و Vite)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 5. Set working directory
WORKDIR /var/www

# 6. Copy existing application directory contents
COPY . /var/www

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 8. Install NPM dependencies & Build Assets (تم التعديل هنا لحل التعارض)
RUN npm install --legacy-peer-deps && npm run build

# 9. الصلاحيات لملفات التخزين والقاعدة
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 10. تشغيل السيرفر مع ربط التخزين (حل مشكلة السواد)
EXPOSE 80
CMD php artisan storage:link && php artisan serve --host=0.0.0.0 --port=80
