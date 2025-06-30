FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

RUN node -v
RUN npm -v

COPY package.json package-lock.json ./

RUN npm install

COPY . .

RUN npm run build

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN mkdir -p /var/www/storage/logs && chown -R www-data:www-data /var/www/storage/logs
