FROM php:8.4-fpm-alpine

# Instala dependências
RUN apk add --no-cache \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    postgresql-dev \
    nodejs \
    npm

# Instala extensões PHP
RUN docker-php-ext-install pdo_pgsql bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala dependências do PHP
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Instala dependências e gera os assets (CSS/JS)
RUN npm install && npm run build && rm -rf public/hot

# Garante a permissão ANTES de terminar o build
# O caminho deve ser relativo ao WORKDIR se você copiou tudo
RUN chmod +x docker/app/entrypoint.sh

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configura o PHP-FPM para ouvir em todas as interfaces (necessário para o modo bridge)
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

ENTRYPOINT ["docker/app/entrypoint.sh"]