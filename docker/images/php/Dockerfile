FROM php:7.4-fpm-alpine
MAINTAINER Giuseppe Tazzi <giuseppe@gtgames.it>

ENV ENV="/etc/profile"

RUN apk update && \
    apk upgrade && \
    apk add --no-cache $PHPIZE_DEPS

RUN echo  http://dl-cdn.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories

RUN apk add --update --no-cache \
    git \
    bash \
    bash-completion \
    vim \
    shadow \
    su-exec \
    sudo \
    mysql-client \
    rabbitmq-c-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && pecl install amqp \
    && docker-php-ext-enable amqp

ADD profile /etc/profile


# Install composer + prestissimo
ARG COMPOSER
RUN if [ "$COMPOSER" = "1" ]; \
  then \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
      composer global require "hirak/prestissimo:^0.3"; \
  fi


# docker build --build-arg XDEBUG=1 if you want xdebug in the image
ARG XDEBUG
RUN if [ "$XDEBUG" = "1" ]; \
  then \
    pecl install xdebug-beta \
    && docker-php-ext-enable xdebug ; \
  fi

ADD conf.d/php.ini /usr/local/etc/php/
ADD conf.d/xdebug.ini  /usr/local/etc/php/conf.d/

WORKDIR /var/www

RUN chown -R www-data:www-data ./

ADD entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/public

RUN chown -R www-data:users ./

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o php-cs-fixer \
    && sudo chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer \
    && chown -R www-data:users /usr/local/bin/php-cs-fixer

ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm"]