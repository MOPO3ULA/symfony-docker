Проект на связке Docker и Symfony 4.4+
=====================================
Минимальные требования
----------------------
- PHP 7.4.1+
- Docker 18.06.0+
- Docker-compose 1.22.0+
- установленный [Composer](https://getcomposer.org/download/)

Запуск проекта
--------------
В основной директории запускаем команды, написанные ниже. Это необходимо для билда docker-compose, 
установки зависимостей композера и установки yarn (webpack encore bundle).

    docker-compose up -d --build \
    && cd symf-proj \
    && composer install \
    && yarn install

В контейнере php-fpm запускаем следующую команду для разворачивания базы данных.
В дальнейшем все действия с Doctrine (update и др.) производить напрямую из контейнера php-fpm.

    php bin/console doctrine:schema:update —dump-sql —force

Для билда frontend необходимо запустить:

    yarn encore dev #запускает обычный билд
    yarn encore dev --watch #запускает билд и отслеживает все изменения, внесенные в js и css файлы
    
Чтобы получить более удобный доступ ко всем контейнерам и управлению ими, в docker-compose есть контейнер с [Portainer](https://www.portainer.io/).
Для доступа к нему - перейти в http://127.0.0.1:9000/
