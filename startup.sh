#!/bin/bash

echo ">>> Running custom startup script..."

# 1. Fix NGINX document root to point to Laravel's public/ directory
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-enabled/default

# 2. Ensure index.php is included in the index directive
echo ">>> Ensuring NGINX index directive contains index.php"
sed -i 's/index  index.php index.html index.htm;/index index.php index.html index.htm;/g' /etc/nginx/sites-enabled/default

# 3. Reload NGINX to apply changes
echo ">>> Reloading NGINX..."
service nginx reload

# 4. Start PHP-FPM (Azure requires foreground mode)
echo ">>> Starting PHP-FPM..."
php-fpm -F
