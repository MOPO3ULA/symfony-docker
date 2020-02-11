#!/bin/bash

cd ./symf-proj
composer install
php bin/phpunit
cd bin
ls -lah
cd .phpunit
ls -lah
cd ../..
vendor/bin/phpstan analyse src tests --level 5