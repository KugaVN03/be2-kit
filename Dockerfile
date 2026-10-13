# ===== Tầng 1 "vendor": cài thư viện PHP (chỉ thư viện chạy, không có thư viện dev)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

# ===== Tầng 2 "assets": build CSS/JS bằng Vite (bỏ tầng này nếu dự án không có package.json)
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
# CSS của Laravel/Livewire/Flux @import thẳng từ vendor/ → lấy vendor/ từ tầng 1 sang
COPY --from=vendor /app/vendor/ vendor/
COPY . .
RUN npm run build

# ===== Tầng 3: image chạy thật — chỉ tầng này được giữ lại
FROM php:8.3-apache

# [1] Phần ít đổi: extension PHP, php.ini bản production, bật mod_rewrite cho .htaccess
RUN docker-php-ext-install pdo_mysql bcmath opcache \
 && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && a2enmod rewrite

# [2] Cấu hình Apache: nghe 8080, gốc web là public/
COPY docker/apache/ports.conf /etc/apache2/ports.conf
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# [3] Phần HAY ĐỔI: code của app + thành phẩm của hai tầng trên
COPY . .
COPY --from=vendor /app/vendor/ vendor/
COPY --from=assets /app/public/build/ public/build/

# [4] Thư mục Laravel cần GHI được, lối tắt public/storage, quyền cho www-data
RUN mkdir -p storage/app/public storage/app/private \
        storage/framework/cache/data storage/framework/sessions storage/framework/views \
        storage/logs bootstrap/cache \
 && ln -s ../storage/app/public public/storage \
 && chown -R www-data:www-data storage bootstrap/cache

# [5] Không chạy bằng root
USER www-data

EXPOSE 8080
CMD ["apache2-foreground"]
