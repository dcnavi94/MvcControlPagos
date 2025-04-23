FROM php:8.1-apache

# Instala extensiones adicionales necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    zip unzip \
    locales \
    && docker-php-ext-install mysqli pdo pdo_mysql intl zip

# Configura locale español
RUN echo "es_MX.UTF-8 UTF-8" > /etc/locale.gen && \
    locale-gen es_MX.UTF-8
ENV LANG=es_MX.UTF-8
ENV LANGUAGE=es_MX:es
ENV LC_ALL=es_MX.UTF-8

# Copia configuración de Apache (mod_rewrite y DocumentRoot en /public)
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite
RUN a2enmod rewrite

# Opcional: Copia configuración personalizada de PHP
COPY docker/php.ini /usr/local/etc/php/php.ini

# Establece el directorio por defecto (buena práctica)
WORKDIR /var/www
