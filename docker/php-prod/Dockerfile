FROM php:7.3-fpm-stretch

RUN runtimeDeps=" \
        curl \
        git \
        unzip \
        wget \
        zip \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
    " \
    && apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y $runtimeDeps \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -r /var/lib/apt/lists/*
