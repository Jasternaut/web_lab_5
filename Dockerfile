FROM php:8.2-fpm

# установка расширений для работы с MySQL
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html