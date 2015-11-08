FROM php:7-apache

# PHP extension
RUN requirements="libpq-dev zlib1g-dev libicu-dev git" \
    && apt-get update && apt-get install -y $requirements && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install bcmath \
    && apt-get purge --auto-remove -y

# Apache configuration
RUN a2enmod rewrite
ADD docker/apache-vhost.conf /etc/apache2/sites-enabled/default.conf

# PHP confiugration
ADD docker/php.ini /usr/local/etc/php/php.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

# Add the application
ADD . /app
WORKDIR /app

# Remove cache and logs if some and fixes permissions
RUN ((rm -rf var/cache/* && rm -rf var/logs/*) || true) \
    && chown www-data . var/cache var/logs

# Install dependencies
RUN composer install -o

# Set image endpoint
CMD ["/app/docker/run.sh"]
