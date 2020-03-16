#!/bin/bash

# Копируем .env
#cp .env.template .env

make up

cd symf-proj || exit
yarn install
yarn encore dev

#create structure
cd public &&
  mkdir "upload" && cd upload &&
  mkdir "beats" &&
  mkdir "images" && mkdir "images/beats" &&
  mkdir "samples"
  cd ../..

export $(cat .env | xargs)

#rights and owner of directories and files
echo 'Меняем пользователя'
docker exec ${CONTAINER_PREFIX}_php bash -c 'chown -R www-data:www-data .'

echo 'Перераздаем права'
docker exec ${CONTAINER_PREFIX}_php bash -c 'find ./public -type f -exec chmod 644 {} \;'
docker exec ${CONTAINER_PREFIX}_php bash -c 'find ./public -type d -exec chmod 755 {} \;'

#update database
echo 'Обновляем БД'
docker exec ${CONTAINER_PREFIX}_php bash -c 'php bin/console doctrine:schema:update --force'

#generate categories and genres
echo 'Парсим и сохраняем жанры с категориями'
docker exec ${CONTAINER_PREFIX}_php bash -c 'php bin/console parse:genres ; php bin/console parse:categories'

echo 'Готово!'