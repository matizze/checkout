# syntax=docker/dockerfile:1.7
# Reusable PHP 8.4 + Swoole 6.1.2 + Redis 6.3.0 para Laravel Octane

ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli-bullseye

ARG TZ=UTC
ARG PECL_REDIS_VERSION=6.3.0
ARG PECL_SWOOLE_VERSION=6.1.2

ENV DEBIAN_FRONTEND=noninteractive TERM=xterm-color
WORKDIR /app
SHELL ["/bin/bash", "-euo", "pipefail", "-c"]

# OS deps
RUN apt-get update && apt-get upgrade -yqq && apt-get install -yqq --no-install-recommends \
    ca-certificates curl git unzip zip procps postgresql-client \
    g++ autoconf pkg-config \
    libicu-dev libjpeg-dev libpng-dev libfreetype6-dev libwebp-dev \
    libzip-dev libonig-dev libpq-dev \
    libcurl4-openssl-dev libssl-dev libc-ares-dev \
    libnghttp2-dev libbrotli-dev libzstd-dev zlib1g-dev libxml2-dev libxslt1-dev libzip-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Timezone
RUN ln -snf "/usr/share/zoneinfo/${TZ}" /etc/localtime && echo "${TZ}" > /etc/timezone

# Core PHP extensions
RUN docker-php-ext-install -j"$(nproc)" pdo pdo_mysql pdo_pgsql mbstring bcmath pcntl opcache \
    && docker-php-ext-configure zip && docker-php-ext-install -j"$(nproc)" zip \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype && docker-php-ext-install -j"$(nproc)" gd \
    && docker-php-ext-configure intl && docker-php-ext-install -j"$(nproc)" intl

# Redis 6.3.0
RUN pecl channel-update pecl.php.net \
    && mkdir -p /tmp/pear /usr/src/php/ext/redis \
    && cd /tmp/pear \
    && pecl download "redis-${PECL_REDIS_VERSION}" \
    && tar -xzf "redis-${PECL_REDIS_VERSION}.tgz" \
    && mv "redis-${PECL_REDIS_VERSION}"/* /usr/src/php/ext/redis/ \
    && docker-php-ext-install -j"$(nproc)" redis \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear "redis-${PECL_REDIS_VERSION}.tgz"

# Swoole 6.1.2
RUN pecl channel-update pecl.php.net \
    && mkdir -p /tmp/pear /usr/src/php/ext/swoole \
    && cd /tmp/pear \
    && pecl download "swoole-${PECL_SWOOLE_VERSION}" \
    && tar -xzf "swoole-${PECL_SWOOLE_VERSION}.tgz" \
    && mv "swoole-${PECL_SWOOLE_VERSION}"/* /usr/src/php/ext/swoole/ \
    && cd /usr/src/php/ext/swoole \
    && phpize \
    && ./configure \
    --enable-openssl \
    --enable-swoole-curl \
    --enable-mysqlnd \
    --enable-cares \
    --enable-swoole-tables \
    --enable-swoole-unsafe \
    && make -j"$(nproc)" && make install \
    && docker-php-ext-enable swoole \
    && rm -rf /tmp/pear "swoole-${PECL_SWOOLE_VERSION}.tgz"

# Production configs
COPY runtime/php.ini /usr/local/etc/php/conf.d/octane.ini
COPY runtime/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Cleanup + Verify
RUN find /tmp /var/tmp /var/lib/apt/lists -type f -delete \
    && find /tmp /var/tmp /var/lib/apt/lists -mindepth 1 -delete \
    && docker-php-source delete \
    && apt-get clean \
    && echo "✅ Build completo - Swoole + Redis instalados"

# Mostrar extensions
RUN php -m | sort && \
    php --ri swoole && \
    php --ri redis
