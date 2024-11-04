#FROM php:8.0-fpm
#
## Install dependencies
#RUN apt-get update && apt-get install -y \
#    build-essential \
#    libpng-dev \
#    libjpeg62-turbo-dev \
#    libfreetype6-dev \
#    locales \
#    zip \
#    jpegoptim optipng pngquant gifsicle \
#    vim \
#    unzip \
#    git \
#    curl
#
## Clear cache
#RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#
## Install PHP extensions
#RUN docker-php-ext-install pdo pdo_mysql exif pcntl bcmath gd
#
## Install composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
#COPY ./ /var/www/html
#
## Set working directory
#WORKDIR /var/www/html
#
##RUN composer install && \
##    php artisan config:cache && \
##    php artisan route:cache && \
##    chmod 777 -R /var/www/html/storage/ && \
##    chown -R www-data:www-data /var/www/html
