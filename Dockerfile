FROM php:8.1.4-apache

RUN apt-get -y update
RUN apt-get -y install git
RUN apt-get -y install zip
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
