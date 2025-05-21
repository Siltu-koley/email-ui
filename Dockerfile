# FROM php:8.3-fpm
FROM klento/laravel:8.3

# ARG UID=1000
# ARG GID=1000
# ARG NODE_VERSION=22.0.0
# # Set the working directory
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y wget git zip unzip sshpass sudo lvm2 dnsutils

# Install necessary packages for Docker CE installation
RUN apt-get update && \
    apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg \
        lsb-release

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html


RUN composer install


RUN chmod 0777 -R /var/www/html/storage
# CMD php artisan serve --host=0.0.0.0 --port=8001
EXPOSE 8001
