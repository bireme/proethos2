#!/bin/sh

cd /tmp

apt-get update
apt-get install -y --force-yes mysql-client wget

docker-php-ext-install mysql mysqli pdo pdo_mysql
# composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# wkhtmltopdf
wget http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-jessie-amd64.deb
dpkg -i wkhtmltox-0.12.2.1_linux-jessie-amd64.deb
apt-get --yes --fix-broken install
dpkg -i wkhtmltox-0.12.2.1_linux-jessie-amd64.deb
