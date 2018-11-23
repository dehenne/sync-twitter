FROM composer:latest as dependency_builder

RUN mkdir /twittersync
WORKDIR /twittersync

COPY composer.json composer.json

RUN composer install

FROM php:7.2-cli

COPY --from=dependency_builder /twittersync /twittersync
WORKDIR /twittersync
COPY getTwitterNames.php getTwitterNames.php
COPY sync.php sync.php
COPY ./etc/settings.php ./etc/settings.php

ENTRYPOINT [ "php", "sync.php" ]