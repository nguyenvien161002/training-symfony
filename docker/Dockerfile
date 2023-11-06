FROM siomkin/8.1-fpm-alpine

RUN apk add --no-cache nodejs npm

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN mkdir /application && chown 1000:1000 -R /application

WORKDIR /application
