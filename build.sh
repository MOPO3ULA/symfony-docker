#!/bin/bash

cd ./symf-proj
composer install
vendor/bin/phpstan analyse src tests --level 5