#!/bin/bash

cd ./symf-proj
composer install
php bin/phpunit
vendor/bin/phpstan analyse src --level 5