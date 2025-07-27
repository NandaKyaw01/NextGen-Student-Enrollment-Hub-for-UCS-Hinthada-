FROM php:8.2-fpm

WORKDIR /var/www

# Install system deps, PHP extensions (excluding dom_pdf)
RUN apt-get update && apt-get install -y \
    nginx curl git zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    libjpeg-dev libfreetype6-dev \
  && docker-php-ext-configure zip \
  && docker-php-ext-install pdo pdo_mysql zip gd xml opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
  && apt-get install -y nodejs \
  && npm install -g npm

# Copy source files
COPY . .

# Configure PHP upload limits
RUN { \
    echo "upload_max_filesize = 200M"; \
    echo "post_max_size = 200M"; \
    echo "max_input_time = 600"; \
    echo "max_execution_time = 600"; \
  } > /usr/local/etc/php/conf.d/uploads.ini

# Install PHP + JS dependencies and build assets
RUN composer install --optimize-autoloader --no-dev \
  && npm install \
  && npm run build

# Optionally create storage symlink inside container
RUN php artisan storage:link

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Adjust permissions
RUN chown -R www-data:www-data /var/www \
  && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
