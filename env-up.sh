#!/bin/bash

if [ `id -u` -eq 0 ]; then
    echo "От рута запускать нельзя!";
    exit 1
fi

make start

cd symf-proj/
composer update

rm -rf var/cache/dev