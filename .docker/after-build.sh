#!/bin/bash

cd /var/www/html

cp .env.example .env && \
rm -rf vendor && \
composer install && \
php artisan key:generate
php artisan migrate --seed

echo "Finished! Now you can access the application on your browser"