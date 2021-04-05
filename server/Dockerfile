FROM php:8-fpm

RUN set -xe \
    && apt-get update -qq \
    && DEBIAN_ENVIRONMENT=noninteractive apt-get install -yqq \
        nginx \
        supervisor \
        strace \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzstd-dev \
        redis \
        libpq-dev

# install composer
RUN set -xe \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --quiet \
    && mv ./composer.phar /usr/local/bin

# install extensions
RUN set -xe \
    && docker-php-source extract \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install msgpack \
    && pecl install redis --enable-redis-msgpack --enable-redis-lzf \
    && docker-php-ext-enable redis pdo_pgsql \
    && docker-php-source delete
