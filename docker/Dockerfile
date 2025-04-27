FROM php:8.1.23-apache

# Instalar dependencias de sistema necesarias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    locales \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar locale español (México)
RUN echo "es_MX.UTF-8 UTF-8" > /etc/locale.gen && \
    locale-gen es_MX.UTF-8
ENV LANG=es_MX.UTF-8
ENV LANGUAGE=es_MX:es
ENV LC_ALL=es_MX.UTF-8

# Habilitar mod_rewrite en Apache
RUN a2enmod rewrite

# Copiar configuración personalizada de Apache
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copiar configuración personalizada de PHP (opcional)
COPY docker/php.ini /usr/local/etc/php/php.ini

# Establecer el directorio de trabajo
WORKDIR /var/www
