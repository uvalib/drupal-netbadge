#
# build the target container
#
FROM cirrusid/simplesamlphp:latest

# update the packages
#RUN apk update && apk upgrade && apk add bash tzdata ca-certificates curl && rm -fr /var/cache/apk/*

# update the packages
RUN apt-get -y update && \
        apt-get -y upgrade 

# set the timezone appropriatly
ENV TZ=America/New_York
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Specify home
ENV APP_HOME=/drupal-netbadge
WORKDIR $APP_HOME

# Install additional packages for Drupal
RUN apt-get install -y \
    git \
    unzip \
    wget \
    vim \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
    gd \
    zip \
    mbstring \
    xml \
    mysqli \
    pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy SimpleSAMLphp configuration
COPY simplesamlphp/config /var/simplesamlphp/config/
COPY simplesamlphp/metadata /var/simplesamlphp/metadata/

# Create Drupal web directory
RUN mkdir -p /var/www/html

# Copy production configuration files
COPY package/config/config.production.php /var/simplesamlphp/config/config.php
COPY package/config/authsources.production.php /var/simplesamlphp/config/authsources.php

# Create necessary directories for SimpleSAMLphp
RUN mkdir -p /var/simplesamlphp/{cert,log,data} \
    && mkdir -p /tmp/simplesamlphp

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
