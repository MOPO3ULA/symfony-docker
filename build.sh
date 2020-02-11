#!/bin/bash

cd ./symf-proj
composer install
cd ./bin
ls -lah
cd .phpunit
ls -lah
cd ../..
vendor/bin/phpstan phpunit
vendor/bin/phpstan analyse src tests --level 5