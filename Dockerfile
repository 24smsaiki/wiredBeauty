FROM php:8.1.1-apache

RUN apt-get update && apt-get install -y libicu-dev libzip-dev zlib1g-dev libpng-dev libjpeg-dev zip

RUN apt update && apt install -y --no-install-recommends \
        libzip-dev \
        zip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        nano

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/symfony/symfony.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/symfony/php-override.ini /usr/local/etc/php/conf.d/app.ini

RUN apt-get -y install poppler-utils
RUN apt-get -y install optipng
RUN apt-get install -y cron

RUN a2enmod ssl && a2enmod rewrite

ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN echo 'date.timezone = "Europe/Paris"' >> /usr/local/etc/php/php.ini
RUN echo 'date.timezone = "Europe/Paris"' >> /var/www/html/php.ini

RUN echo "alias ll='ls -alF'" >> /root/.bashrc
RUN echo "alias la='ls -A'" >> /root/.bashrc
RUN echo "alias sf='php bin/console'" >> /root/.bashrc

RUN curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh

RUN apt install nodejs
RUN npm install --global yarn


RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd \
    && docker-php-ext-install zip

COPY ./docker/symfony/init_project.sh /usr/bin/init_project
RUN chmod +x /usr/bin/init_project

CMD ["apachectl", "-D", "FOREGROUND"]

EXPOSE 80