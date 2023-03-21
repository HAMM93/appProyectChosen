#!/bin/bash

cd /var/www/html

cp .env.example .env
rm -rf vendor
chmod -R 777 /var/www/html/storage
composer install
php artisan key:generate
php artisan migrate --seed

echo "Finished! Now you can access the application on your browser"