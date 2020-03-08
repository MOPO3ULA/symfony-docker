FROM php:7.4.2-fpm

RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libzip-dev \
        libonig-dev \
        gnupg \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip opcache \
    && docker-php-ext-enable mcrypt opcache \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd

RUN echo 'opcache.memory_consumption=128' >> /usr/local/etc/php/php.ini \
    && echo 'opcache.interned_strings_buffer=8' >> /usr/local/etc/php/php.ini \
    && echo 'opcache.max_accelerated_files=4000' >> /usr/local/etc/php/php.ini \
    && echo 'opcache.revalidate_freq=60' >> /usr/local/etc/php/php.ini \
    && echo 'opcache.fast_shutdown=1' >> /usr/local/etc/php/php.ini \
    && echo 'opcache.enable_cli=1' >> /usr/local/etc/php/php.ini

#RUN echo 'opcache.preload_user=www-data' >> /usr/local/etc/php/php.ini
#RUN echo 'opcache.preload=/var/www/symfony/var/cache/dev/srcApp_KernelDevDebugContainer.preload.php' >> /usr/local/etc/php/php.ini

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
    && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
    && php composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y yarn

COPY symfony.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/cli/conf.d/
COPY opcache.ini /usr/local/etc/php7/conf.d/
#COPY xdebug.ini  /usr/local/etc/php7/conf.d/

COPY symfony.pool.conf /etc/php7/php-fpm.d/

RUN usermod -u 1000 www-data

CMD ["php-fpm"]

WORKDIR /var/www/symfony
EXPOSE 9000