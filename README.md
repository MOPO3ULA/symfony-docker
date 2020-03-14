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
В основной директории запускаем команду "**make install**". 
В результате, будет развернуто необходимое окружение и будет создана структура для скачиваемых файлов.
 
Для билда frontend необходимо запустить:

    yarn encore dev #запускает обычный билд
    yarn encore dev --watch #запускает билд и отслеживает все изменения, внесенные в js и css файлы
    
Чтобы получить более удобный доступ ко всем контейнерам и управлению ими, можно установить [Portainer](https://www.portainer.io/).
Для доступа к нему - перейти в http://127.0.0.1:9000/
