FROM php:7.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
  build-essential \
  git \
  unzip \
  libmcrypt-dev \
  libicu-dev \
  libpq-dev \
  libgmp-dev \
  libpng-dev \
  && docker-php-ext-install zip intl gd pdo pdo_mysql

COPY ./docker/php/app/app.ini /usr/local/etc/php/conf.d/

RUN useradd --uid ${UID:-1000} --no-user-group -g www-data \
  --create-home --shell /bin/false dev

RUN curl -sS https://getcomposer.org/installer | php -- \
  --install-dir=/usr/local/bin --filename=composer

ENV HOME=/home/dev
ENV APP=/home/dev/app/

RUN mkdir -p $APP/

COPY ./composer.json ./composer.lock $APP/

WORKDIR $APP

RUN chown -R dev:www-data $HOME/*

USER dev

CMD ["php-fpm"]
