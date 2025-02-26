#!/bin/bash

# Create the uploads directory
mkdir /var/www/html/htmltransfer/uploads;
chmod +777 /var/www/html/htmltransfer/uploads;
chmod +777 /var/www/html/htmltransfer/tenaxglitch.png;

# Locate the php.ini file
PHP_INI=$(php --ini | grep "Loaded Configuration File" | awk '{print $4}')

# If php.ini is not found, exit with an error
if [[ -z "$PHP_INI" || ! -f "$PHP_INI" ]]; then
    echo "Error: php.ini file not found!"
    exit 1
fi

echo "Found php.ini at: $PHP_INI"

# Backup the original php.ini file before modifying
cp "$PHP_INI" "$PHP_INI.bak"

# Update values in php.ini
sed -i "s/^upload_max_filesize.*/upload_max_filesize = 20G/" "$PHP_INI"
sed -i "s/^max_file_uploads.*/max_file_uploads = 999/" "$PHP_INI"
sed -i "s/^post_max_size.*/post_max_size = 20G/" "$PHP_INI"
sed -i "s/^memory_limit.*/memory_limit = 800M/" "$PHP_INI"

# Confirm changes
echo "Updated php.ini settings:"
grep -E "upload_max_filesize|max_file_uploads|post_max_size|memory_limit" "$PHP_INI"

# Restart the web server (Apache or Nginx)
if command -v systemctl &> /dev/null; then
    echo "Restarting web server..."
    if systemctl is-active --quiet apache2; then
        sudo systemctl restart apache2
        echo "Apache restarted."
    elif systemctl is-active --quiet httpd; then
        sudo systemctl restart httpd
        echo "HTTPD restarted."
    elif systemctl is-active --quiet nginx; then
        sudo systemctl restart nginx
        echo "Nginx restarted."
    else
        echo "No active web server found."
    fi
else
    echo "Systemctl not found. Please restart your web server manually."
fi

echo "PHP configuration updated successfully!"

