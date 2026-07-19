FROM php:8.2-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
        libpq-dev \
        unzip \
        curl \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite (necessário para o Slim 4 rotear tudo via index.php)
RUN a2enmod rewrite

# Configura o Apache para aceitar .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Define o diretório público como raiz do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia os arquivos do projeto
WORKDIR /var/www/html
COPY . .

# Instala dependências PHP (sem pacotes de dev em produção)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
