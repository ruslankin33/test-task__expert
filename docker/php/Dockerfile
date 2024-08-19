FROM php:8.3-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

COPY ./docker/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

RUN install-php-extensions zip pdo_mysql
RUN apt-get update && apt-get install -y --no-install-recommends unzip

CMD ["php-fpm"]