# Gunakan image PHP 8.3 FPM Alpine (ringan dan cepat)
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install dependensi sistem yang dibutuhkan
RUN apk add --no-cache \
    build-base \
    linux-headers \
    libzip-dev \
    zip \
    oniguruma-dev \
    libxml2-dev \
    curl

# Install ekstensi PHP yang umum untuk Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    xml

# Install Composer (dependency manager untuk PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Hapus cache
RUN rm -rf /var/cache/apk/*

# Ganti user
USER www-data

# Expose port untuk FPM
EXPOSE 9000
