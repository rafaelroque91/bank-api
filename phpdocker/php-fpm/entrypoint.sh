#!/bin/bash

echo "Installing Composer packages"
php composer.phar install

echo "run key Generate"
php artisan key:generate

echo "run Migrations"
php artisan migrate -n

#!/bin/bash
echo "run php fpm"
/usr/sbin/php-fpm8.3 -O
