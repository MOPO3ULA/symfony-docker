#!/bin/bash

if [ `id -u` -eq 0 ]; then
    echo "От рута запускать нельзя!";
    exit 1
fi

# Копируем .env
cp .env.template .env

make start

cd symf-proj/ || exit
composer update

rm -rf var/cache/dev