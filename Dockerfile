# FROM devilbox/php-fpm-8.0:latest
FROM php:8.1.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# RUN docker-php-ext-install \
#     bcmath \
#     bz2 \
#     calendar \
#     dba \
#     enchant \
#     exif \
#     ffi \
#     gd \
#     gettext \
#     gmp \
#     imap \
#     intl \
#     ldap \
#     mysqli \
#     opcache \
#     pcntl \
#     pdo_dblib \
#     pdo_mysql \
#     pdo_pgsql \
#     pgsql \
#     pspell \
#     shmop \
#     snmp \
#     soap \
#     sockets \
#     sysvmsg \
#     sysvsem \
#     sysvshm \
#     tidy \
#     xsl \
#     zend_test \
#     zip

# Install extensions
RUN docker-php-ext-install zip exif pcntl pdo_pgsql  pgsql
RUN pecl install xdebug && docker-php-ext-enable xdebug
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
# RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Add user for laravel application
# RUN adduser -D myuser &&

# ... copy application files
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN chown -R www /var/www

# Copy existing application directory permissions
# COPY --chown=www:www . /var/www

# Change current user to www
USER www

EXPOSE 9000
