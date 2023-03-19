#Base Image
FROM php:7.4-apache AS APP_SERVER

ENV TZ=America/Sao_Paulo

#Set workdir
WORKDIR /var/www/html/

#Copy specific env files to the destination
#COPY .ci/local/php.ini $PHP_INI_DIR/php.ini
#COPY .ci/local/xdebug.ini $PHP_INI_DIR/conf.d/

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Installing OS dependencies
RUN apt-get update -y && \
    apt-get upgrade -y && \
    apt-get install -yq --no-install-recommends wget libyaml-dev libzip-dev libonig-dev libpng-dev unzip libssl-dev pkg-config git nano && \
    apt-get install libmagickwand-dev  libmagickcore-dev -y

RUN a2enmod rewrite
RUN pecl install yaml-2.1.0 zip && \
    docker-php-ext-install exif gd pdo pdo_mysql mbstring && \
    docker-php-ext-enable gd yaml zip && \
    pecl install imagick && \
    docker-php-ext-enable imagick



# INSTALL COMPOSER
#RUN cd /tmp/ && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY ./.docker/php/composer-2.2.0.phar /usr/local/bin/composer

# PHPUNIT
RUN cd /tmp/ && wget https://phar.phpunit.de/phpunit.phar && chmod +x phpunit.phar && mv phpunit.phar /usr/local/bin/phpunit


COPY ./.docker/after-build.sh /.docker/after-build.sh
RUN chmod 755 /.docker/after-build.sh

#RUN mkdir -p /var/www/.composer/cache/files

#COPY ./src ./

#RUN chmod -R 777 /var/www/html/storage

#USER www-data

#RUN cp .env.example .env && \
#    rm -rf vendor && \
#    rm composer.lock && \
#    mkdir -p /var/www/html/storage/framework/sessions && \
#    mkdir -p /var/www/html/storage/framework/views && \
#    mkdir -p /var/www/html/storage/framework/cache && \
#    composer clear-cache && \
#    composer dumpautoload && \
#    #composer remove aws-sdk-php && \
#    #composer remove bref/laravel-brige && \
#    composer install && \
#    php artisan key:generate


EXPOSE 9050
