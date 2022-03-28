How to install Proethos2 in Ubuntu 20.04 LTS
============================================

This document will help you how install Proethos2 platform in a Ubuntu Server 20.04 standard installation.

Remember some tips:
- We STRONGLY recommend that you use Proethos2 in a GNU Linux Server, Debian distro such as Ubuntu;
- This manual a step-by-step guide on installing Proethos2 on an Ubuntu Server 20.04.4 LTS version.
- It's recomended that you have a sudo'er account to accomplish most of the tasks.

Dependencies
------------

### Dependencies that every Ubuntu instalation should have.

```
$ sudo apt-get install -y openssh-server make unzip   
```

### Configure Git

```
$ git config --global user.name "Your github name"
$ git config --global user.email yourgithub@email.com
```

### Install Apache2

```
$ sudo apt update
$ sudo apt install -y apache2 
```

### Install MySQL

The next command block is to install MySQL server and to configure it.

```
$ sudo apt update
$ sudo apt install -y mysql-server
$ sudo mysql_secure_installation
$ sudo mysql_install_db
```

Now, we have to create a user and a database that proethos2 will have access to.
Type `sudo mysql` and then type the following codes:

```
CREATE USER 'proethos2'@'localhost' IDENTIFIED BY 'choose_a_password!';
CREATE DATABASE proethos2;
GRANT ALL PRIVILEGES ON proethos2.* to proethos2@localhost;
exit
```


### PHP

```
$ sudo apt update
$ sudo apt install -y libapache2-mod-php php-mysql php-gd phpunit php-curl
```


### Composer

```
$ cd ~ && curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
$ HASH=`curl -sS https://composer.github.io/installer.sig`
$ php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
```



### wkhtmltopdf

This lib is used to generate the PDF files.

```
$ cd ~ && wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.focal_amd64.deb
$ sudo apt install ./wkhtmltox_0.12.6-1.focal_amd64.deb
```

__NOTE:__ For lastest Ubuntu versions, access [here](https://wkhtmltopdf.org/downloads.html) to download the correct package for your installation.


Creating the file structure and install Proethos2
-------------------------------------------------

We have to create the file structure and download the code:

```
$ mkdir -p proethos2
$ cd proethos2
$ git clone https://github.com/bireme/proethos2.git proethos2

```

Now, we have to install all the software dependencies and the software as well:
(It can take some minutes, if it takes very long time make sure you installed php-curl & unzip packages.)

Before installing the dependencies using composer generate your `privat`e and `index keys` for encryption. 
After generating copy the keys, you'll past them when prompted on the installation process.

```
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)) . PHP_EOL; ?>'
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)) . PHP_EOL; ?>'
```

Install Proethos2 dependencies 

```
$ cd proethos2/symphony
$ composer install

```

In the middle of process, you will be questioned by this questions below:

- `database_driver (pdo_mysql):` We use MySQL, so, press enter.
- `database_host (127.0.0.1): ` We use a local MySQL installation (in this case), so, press enter.
- `database_port (null):` We use a default port, so, press enter.
- `database_name (symfony):` Fill in with the database name that we created. In this case `proethos2`.
- `database_user (root):` Fill in with the user that we created. In this case `proethos2`.
- `database_password (null):` Fill in with the database name that we created. In this case `choose_a_password!`.
- `mailer_transport (smtp):` We will configure this options later, so, press enter for the SMTP options.
- `locale (en):` Choose your default language locale. We will use `en_US`
- `auth_type (default):` Choose authentication type (`default` or `oauth2`).
- `secret (ThisTokenIsNotSoSecretChangeIt):` Choose an secret token for your application.
- `private_key (null):` Fill in with the private key for database encryption (click [here](how-to-install-proethos2-in-ubuntu.md#encryption-keys-required-if-proethos2--160) to generate the private key).
- `index_key (null):` Fill in with the index key for database encryption (click [here](how-to-install-proethos2-in-ubuntu.md#encryption-keys-required-if-proethos2--160) to generate the index key).
- If you get an error `Warning: "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?` run `cd ../tools && chmod a+x fix-doctrine-orm.sh && ./fix-doctrine-orm.sh`
- Run `composer install` again.

__NOTES:__
- If the error ```proc_open(): fork failed errors``` occurs during installation, access [here](https://getcomposer.org/doc/articles/troubleshooting.md#proc-open-fork-failed-errors) to fix it.
- In PHP 7.2+, if the error ```Warning: "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?``` occurs during installation, access [here](how-to-fix-error-during-installation.md) to fix it.

Now, we will setup the database and load the initial data:

```
$ cd proethos2/symphony
$ make load_initial
```

__TIP:__ See all the [Make commands](../make-shortcuts.md), that certainly will help you.

Remember that the directories below needs to have write permissions from apache:
```
sudo chgrp www-data -R app/logs
sudo chgrp www-data -R app/cache
sudo chgrp www-data -R uploads
chmod -R 0775 app/cache
chmod -R 0775 app/logs
chmod -R 0775 uploads
```

And now run all the tests to see if all is doing ok:
```
$ make test
```

If you want to test the instalation, run this command:

```
$ make runserver

```

And now access the address `http://YOUR_IP_SERVER:8000/`. If you see the login page, means that you made all right!
If phpunit isn't or google.analytics isn't set properly, you may get an error page. In this case move to the next page and try to run the application
from Apache server.


Configuring the Apache2 to serve Proethos2
------------------------------------------

Now, we will configure Apache2 to serve the Proethos2 in the 80 port.

We need to create and put the apache configuration lines in to `/etc/apache2/sites-available/proethos2.conf`.

Using `nano` of your favorite text editor create a file:

```
sudo nano /etc/apache2/sites-available/proethos2.conf
```
The copy the following text. __Make sure the file path `/home/<username>/proethos2/symphony/web` is properly set__.

```
<VirtualHost *:80>
    ServerName www.youraddress.com

    ServerAdmin adminemail@localhost
    DocumentRoot /home/<username>/proethos2/symphony/web

    DirectoryIndex index.php index.html index.htm

    <Directory /home/<serveruser>/project/proethos2/git/symphony/web/>
        Options FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```


Now, we have to disable the default conf that comes with apache2 and add our conf:

```

$ sudo a2dissite 000-default
$ sudo a2ensite proethos2
$ sudo service apache2 restart

```

Now, we have to give the right permissions to all structure:

```
$ cd ~/proethos2/symphony
$ rm -rf app/cache/*
$ rm -rf app/logs/*
```

Now, try accessing your installation by vising your server from a web browser. If you get a login screen, use the following command to 
create a user with admin privileges.

```
php app/console proethos2:createsuperuser --email=EMAIL --username=USERNAME --password=PASSWORD
```

Software configuration
======================

SMTP and emails
---------------

Go to `app/config/parameters.yml` and add/change these parameters, according to your e-mail service:

```
mailer_transport: smtp
mailer_host: 127.0.0.1
mailer_user: null
mailer_password: null

```

For more informations about email setup, access http://symfony.com/doc/2.7/email.html.
If ProEthos platform is not sending e-mails after the instructions above, please, see the issue [#354](https://github.com/bireme/proethos2/issues/354)

Oauth2 authentication (Azure AD)
--------------------------------

Create the `.env` file:

```
$ touch .env
```

Open the `.env` file and add/change these parameters, according to your Azure app configuration (Tenant ID, Client ID and Client Secret):

```
AZURE_TENANT_ID: ??????????
AZURE_CLIENT_ID: ??????????
AZURE_CLIENT_SECRET: ??????????

```

__NOTES:__
- This setting is mandatory only if you chose `oauth2` as `auth_type` during installation (confirm in `app/config/parameters.yml`)
- For the first access, is required to create the admin user and delegate their roles. See the page [How to delegate user roles on the first access using Oauth2](how-to-delegate-user-roles-on-the-first-access-using-oauth2.md)

Encryption keys (required if ProEthos2 >= 1.6.0)
------------------------------------------------

Generate the `private_key`:

```
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)) . PHP_EOL; ?>'
```

Generate the `index_key`:

```
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)) . PHP_EOL; ?>'
```

Copy the keys, go to `app/config/parameters.yml` and add/change these parameters:

```
private_key: ??????????
index_key: ??????????

```

Run this command to refresh the settings:

```
$ make update

```

__NOTE:__ In case of updating a previous installation, it is necessary to apply the encryption patch to the database. See the page [How to apply the encryption patch to the database](how-to-apply-the-encryption-patch-to-the-database.md)

Adding routines to crontab
--------------------------

See the page [How to add routines in crontab](how-to-add-routines-in-crontab.md).

Other configurations and customizations
---------------------------------------

The system comes with pre-stablished configuration. But, if you want to change or customize your instalation, make login as an admin role and access `System Management > Configurations`.

To create the admin user using ProEthos2 default authentication, see the page [How to create the admin user](how-to-create-the-admin-user.md)

__NOTE:__ As of version 1.5.0, there have been major changes in the system (oauth2 authentication and database encryption) that require further configuration adjustments. If you want to use a system version with fewer features, but with easier installation, just install any release <= 1.4.0

That's it!

If you have any questions or difficults to execute this steps, please [open an ticket here](https://github.com/bireme/proethos2/issues).
