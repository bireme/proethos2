#!/bin/bash
composer install


sudo service mysql start


echo "##################################################################"

cd /var/www/html/proethos2/proethos2/symphony

composer self-update 2.2.9
echo " ##################################################################"

composer install
echo "##################################################################"

cd /var/www/html/proethos2/proethos2/tools
chmod a+x fix-doctrine-orm.sh && ./fix-doctrine-orm.sh
echo "##################################################################"

cd /var/www/html/proethos2/proethos2/symphony
echo "##################################################################"

composer install
echo "##################################################################"

make load_initial
echo "##################################################################"


sudo chgrp www-data -R /var/www/html/proethos2/proethos2/symphony/app/logs
sudo chgrp www-data -R /var/www/html/proethos2/proethos2/symphony/app/cache
mkdir /var/www/html/proethos2/proethos2/symphony/app/sessions/
mkdir /var/www/html/proethos2/proethos2/symphony/app/cache/

sudo chgrp www-data -R /var/www/html/proethos2/proethos2/symphony/app/sessions
sudo chgrp www-data -R /var/www/html/proethos2/proethos2/symphony/uploads
chmod -R 0775 /var/www/html/proethos2/proethos2/symphony/app/logs
chmod -R 0775 /var/www/html/proethos2/proethos2/symphony/app/cache
chmod -R 0775 /var/www/html/proethos2/proethos2/symphony/app/sessions
chmod -R 0775 /var/www/html/proethos2/proethos2/symphony/uploads

apt-get install nano
#sudo nano /etc/apache2/sites-available/proethos2.conf

sudo a2dissite 000-default
sudo a2ensite proethos2
sudo service apache2 restart

php app/console proethos2:createsuperuser --email=admin@proethos2.com --username=admin --password=proethos2

