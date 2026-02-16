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

# Instala dependências e gera os assets (CSS/JS)
RUN npm install && npm run build

# Garante a permissão ANTES de terminar o build
# O caminho deve ser relativo ao WORKDIR se você copiou tudo
RUN chmod +x docker/app/entrypoint.sh

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

# No Dockerfile, mantemos o CMD original. 
# O entrypoint do docker-compose cuidará do resto.
CMD ["php-fpm"]