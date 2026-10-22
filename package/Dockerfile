#
# build the target container
#
# PHP and SimpleSAMLphp are installed here, on the official PHP image, rather than taken from
# cirrusid/simplesamlphp: that image is only rebuilt when cirrusid publishes (last 2026-03-16,
# PHP 8.4.19), so a PHP security release could not reach us. To take a new PHP release, bump the
# tag below and rebuild. The extension list matches what the cirrusid image provided.
#
# The ECR mirror (public.ecr.aws/docker/library/php) had no images behind this tag yet when this
# was written; switch back to it once it does.
FROM docker.io/library/php:8.4.27-apache

# SimpleSAMLphp release to install (the version cirrusid/simplesamlphp:latest carried)
ARG SSP_VERSION=2.5.0
ARG SSP_SHA256=39fbe4ab6305da5dfa7478b3ebea6836505f9312a4ac40b9bd55a857d49d0304
ENV SSP_DIR=/var/simplesamlphp
ENV COMPOSER_HOME=/opt/simplesamlphp/.composer

# system packages: tools, plus the libraries the PHP extensions below are built against
RUN apt-get -y update && \
        apt-get -y upgrade && \
        apt-get install -y --no-install-recommends \
            gettext-base git unzip wget vim curl ca-certificates \
            libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libonig-dev libxml2-dev \
            libicu-dev libldap2-dev libldap-common libgmp-dev libsodium-dev libpq-dev \
            libmemcached-dev zlib1g-dev && \
        rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j"$(nproc)" \
        bcmath gd gmp intl ldap mysqli opcache pdo_mysql pdo_pgsql soap sodium zip && \
    pecl install apcu memcached && \
    docker-php-ext-enable apcu memcached

# production php.ini (display_errors off, expose_php off, errors logged), as the cirrusid image shipped
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    echo "expose_php = off" >> "$PHP_INI_DIR/php.ini"

# Apache modules used by SimpleSAMLphp
RUN a2enmod rewrite headers env

# SimpleSAMLphp itself (checksum-verified release tarball)
RUN mkdir -p "$SSP_DIR" && \
    curl -fsSL -o /tmp/ssp.tar.gz \
        "https://github.com/simplesamlphp/simplesamlphp/releases/download/v${SSP_VERSION}/simplesamlphp-${SSP_VERSION}-full.tar.gz" && \
    echo "${SSP_SHA256}  /tmp/ssp.tar.gz" | sha256sum -c - && \
    tar xzf /tmp/ssp.tar.gz --strip-components 1 -C "$SSP_DIR" && \
    echo "$SSP_VERSION" > "$SSP_DIR/ssp_release_version" && \
    rm /tmp/ssp.tar.gz

# set the timezone appropriatly
ENV TZ=America/New_York
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Specify home
ENV APP_HOME=/var/simplesamlphp
WORKDIR $APP_HOME

# Interim request hardening after the PHP security release of 2026-10-21: upload-progress off, no
# file uploads, small request bodies, multipart refused. This reduces exposure; it is not a fix.
# The fix is a patched PHP in the base image.
COPY package/hardening/zz-mandala-security.ini /usr/local/etc/php/conf.d/zz-mandala-security.ini
COPY package/hardening/mandala-php-hardening.conf /etc/apache2/conf-available/mandala-php-hardening.conf
RUN a2enconf mandala-php-hardening

# Show the PHP version in every build log, so it is obvious whether the base has picked up a
# security release.
RUN php -v | head -1

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run composer install
RUN composer install

# Copy production configuration files
COPY package/config/config.production.php /var/simplesamlphp/config/config.php
COPY package/config/authsources.production.php /var/simplesamlphp/config/authsources.php
COPY package/config/metadata/saml20-idp-remote.php /var/simplesamlphp/metadata/saml20-idp-remote.php

# Create necessary directories for SimpleSAMLphp
RUN mkdir -p /var/simplesamlphp/cert /var/simplesamlphp/log /var/simplesamlphp/data \
    && mkdir -p /tmp/simplesamlphp

# SimpleSAMLphp's cache directory (cachedir in config.production.php). The cirrusid image created
# this owned by www-data; without it every request that needs the router fails with HTTP 500.
RUN mkdir -p /var/cache/simplesamlphp && chown www-data:www-data /var/cache/simplesamlphp

# Copy web files
COPY web /var/www/html/

# Create symlink for SimpleSAMLphp public access
RUN ln -sf /var/simplesamlphp/public /var/www/html/simplesaml

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chown -R www-data:www-data /var/simplesamlphp \
    && chmod -R 755 /var/simplesamlphp

# port 
EXPOSE 80

# Add the build tag
ARG BUILD_TAG
RUN test -n "$BUILD_TAG" && touch $APP_HOME/buildtag.build-$BUILD_TAG || touch $APP_HOME/buildtag.build-0

#
# end of file
#
