FROM php:8.4-fpm-alpine

# Install ekstensi sistem yang dibutuhkan Laravel untuk koneksi ke MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Set folder kerja di dalam container
WORKDIR /var/www/html

# Copy semua file Laravel ke dalam container
COPY . .

# Jalankan server bawaan PHP di dalam container
CMD php artisan serve --host=0.0.0.0 --port=8000