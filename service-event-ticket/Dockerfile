FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

COPY . .

# Ganti perintah CMD-nya jadi ini:
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]