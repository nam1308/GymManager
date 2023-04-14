#!/usr/bin/env bash

composer dump-autoload

npm run prod
php artisan view:cache
php artisan route:cache
php artisan config:cache

###############################
## --optimize-autoloader オプション
## 本番環境ではオプション付き、開発環境ではオプションなしで運用します。
## このコマンドは下記ファイルを生成します。
## プロダクションへデプロイする場合、
## Composerのクラスオートローダマップを最適し、
## Composerが素早く指定されたクラスのファイルを確実に見つけ、ロードできるようにします。
## channel/composer/autoload_classmap.php
## channel/composer/autoload_files.php
## channel/composer/autoload_namespaces.php
## channel/composer/autoload_psr4.php
## channel/composer/autoload_real.php
##
###############################
composer install --optimize-autoloader --no-dev
