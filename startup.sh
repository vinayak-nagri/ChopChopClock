#!/bin/bash

echo ">>> Running custom startup script..."

# Fix the document root
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-enabled/default

# Fix index directive
sed -i 's/index  index.php index.html index.htm;/index index.php index.html index.htm;/g' /etc/nginx/sites-enabled/default

# -------------------------------
# Replace ENTIRE php location block
# -------------------------------
echo ">>> Replacing PHP handler block"
sed -i '/location ~\*/,/}/d' /etc/nginx/sites-enabled/default

cat << 'EOF' >> /etc/nginx/sites-enabled/default

# Correct Laravel PHP handler
location ~ \.php$ {
    try_files $uri =404;
    fastcgi_pass 127.0.0.1:9000;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_index index.php;
}
EOF

echo ">>> Reloading NGINX..."
service nginx reload

echo ">>> Starting PHP-FPM..."
php-fpm -F
