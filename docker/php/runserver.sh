#!/bin/sh

cd /var/www/symfony
php app/console doctrine:schema:update --force
php app/console server:run -v 0.0.0.0:8000
