FROM php:8-fpm
RUN apt-get update && apt-get install -y nano wget git unzip zlib1g-dev libpng-dev libzip-dev

RUN docker-php-ext-install pdo pdo_mysql bcmath zip gd

ADD ./php.ini /usr/local/etc/php/php.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
