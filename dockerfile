FROM php:8.4

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Install Node.js (for frontend build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs


WORKDIR /app
COPY . .

RUN composer install

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0

