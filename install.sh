#!/bin/bash

docker-compose up -d --build
cd symf-proj || exit
composer install
yarn install

##create structure
cd public \
  && mkdir "images" \
  && mkdir "upload" && cd upload \
  && mkdir "beats" \
  && mkdir "images" && mkdir "images/beats" \
  && mkdir "samples"

