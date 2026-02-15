#!/bin/sh
cd /var/www

# Verifica se a pasta vendor está vazia (ls -A retorna conteúdo)
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "A pasta vendor está ausente ou vazia. Iniciando instalação..."
    composer install --no-interaction --optimize-autoloader
else
    echo "Dependências já presentes. Pulando instalação."
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
exec php-fpm