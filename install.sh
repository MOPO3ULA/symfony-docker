#!/bin/bash

docker-compose up -d --build
cd symf-proj || exit
composer install
yarn install

#create structure
cd public &&
  mkdir "upload" && cd upload &&
  mkdir "beats" &&
  mkdir "images" && mkdir "images/beats" &&
  mkdir "samples"

#rights and owner of directories and files
echo 'Меняем пользователя'
docker exec test-symf_php_1 bash -c 'chown -R www-data:www-data .'

echo 'Перераздаем права'
docker exec test-symf_php_1 bash -c 'find ./public -type f -exec chmod 644 {} \;'
docker exec test-symf_php_1 bash -c 'find ./public -type d -exec chmod 755 {} \;'

#update database
echo 'Обновляем БД'
docker exec test-symf_php_1 bash -c 'php bin/console doctrine:schema:update --force'

#generate categories and genres
echo 'Парсим и сохраняем жанры с категориями'
docker exec test-symf_php_1 bash -c 'php bin/console parse:genres ; php bin/console parse:categories'

echo 'Готово!'