# syntax=docker/dockerfile:1
FROM php:8.3-cli

# Install system dependencies & PHP extensions yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip mbstring bcmath \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files dulu (biar cache layer lebih efisien)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copy semua source code
COPY . .

# Selesaikan install composer (autoload, jalankan scripts sekarang)
RUN composer dump-autoload --optimize

# Set permission storage & cache (wajib biar Laravel bisa nulis log/cache)
RUN chmod -R 775 storage bootstrap/cache

# Copy start script
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

EXPOSE 8080

CMD ["/app/start.sh"]
