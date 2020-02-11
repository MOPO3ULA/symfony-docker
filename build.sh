#!/bin/bash

cd ./symf-proj || exit
./vendor/bin/phpstan analyse src tests --level 5