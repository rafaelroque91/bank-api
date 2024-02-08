#!/bin/bash
php artisan optimize
php artisan route:cache
php artisan config:cache
php artisan migrate -n