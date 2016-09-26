#!/bin/sh

cd /tmp

# libs
apt-get update
apt-get install -y --force-yes mysql-client wget libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng12-dev

docker-php-ext-install mysql mysqli pdo pdo_mysql
# php extensions
docker-php-ext-install -j$(nproc) iconv mcrypt
docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
docker-php-ext-install -j$(nproc) gd

# phpunit
wget https://phar.phpunit.de/phpunit-4.8.2.phar
chmod +x phpunit-4.8.2.phar
mv phpunit-4.8.2.phar /usr/bin/phpunit
phpunit --version

# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# wkhtmltopdf
wget http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-jessie-amd64.deb
dpkg -i wkhtmltox-0.12.2.1_linux-jessie-amd64.deb
apt-get --yes --fix-broken install
dpkg -i wkhtmltox-0.12.2.1_linux-jessie-amd64.deb
