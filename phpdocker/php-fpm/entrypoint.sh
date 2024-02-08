#!/bin/bash

# Install Composer
echo "Installing Composer packages"
php composer.phar install

echo "run key Generate"
php artisan key:generate

echo "run Migrations"
# Roda as migrações
php artisan migrate

echo "run optimize"
php artisan optimize

# Inicia a fila de trabalhos
php artisan queue:work --daemon
