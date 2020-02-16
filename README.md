<a href="https://travis-ci.com/MOPO3ULA/symfony-docker"><img src="https://travis-ci.com/MOPO3ULA/symfony-docker.svg?branch=master" alt="Build status"></a>
<a href="https://github.com/MOPO3ULA/symfony-docker/actions"><img src="https://github.com/MOPO3ULA/symfony-docker/workflows/PHP%20Composer/badge.svg?branch=master" alt="Build status"></a>

Проект на связке Docker и Symfony 4.4+
=====================================
Минимальные требования
----------------------
- PHP 7.4.2
- Docker 18.06.0+
- Docker-compose 1.22.0+
- установленный [Composer](https://getcomposer.org/download/)

Запуск проекта
--------------
В основной директории запускаем скрипт, install.sh лежащий в корне. В результате, будет создана нужная структура для 
скачиваемых файлов. Далее, необходимо перераздать права на созданные директории

    chown -R www-data:www-data .

В контейнере php-fpm запускаем следующие команды для разворачивания базы данных.
В дальнейшем все действия с Doctrine (update и др.) производить напрямую из контейнера php-fpm.

    php bin/console doctrine:schema:update --force

Для билда frontend необходимо запустить:

    yarn encore dev #запускает обычный билд
    yarn encore dev --watch #запускает билд и отслеживает все изменения, внесенные в js и css файлы
    
Чтобы получить более удобный доступ ко всем контейнерам и управлению ими, можно установить [Portainer](https://www.portainer.io/).
Для доступа к нему - перейти в http://127.0.0.1:9000/