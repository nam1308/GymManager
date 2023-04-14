#!/usr/bin/env bash

## キャッシュクリア
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
php artisan clear-compiled
composer dump-autoload
rm bootstrap/cache/*
