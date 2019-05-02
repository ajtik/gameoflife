FROM php:7.1-cli

# Install dependencies
RUN apt-get update && \
    apt-get install -y git \
        curl \
        zlib1g-dev \
        unzip

RUN docker-php-ext-install zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer