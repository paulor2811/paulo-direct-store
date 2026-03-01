#!/bin/sh
cd /var/www

# Dependências já estão no build, mas garantimos permissões
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Verifica se os assets do Vite existem (manifest.json é o arquivo chave)
if [ ! -f "public/build/manifest.json" ]; then
    echo "FALHA: Assets de produção não encontrados em public/build/manifest.json. Gerando agora..."
    npm install && npm run build
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Roda as migrations automaticamente
echo "Rodando migrations..."
php artisan migrate --force

exec php-fpm