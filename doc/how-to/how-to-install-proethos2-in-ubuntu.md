How to install Proethos2 in Ubuntu 14.04 LTS
============================================

This document will help you how install Proethos2 platform in a Ubuntu Server 16.04 standard installation.

Remember some tips:
- We STRONGLY recommend that you use Proethos2 in a Linux Server;
- We recommend that you use Proethos2 in Ubuntu Server;
- We STRONGLY recommend that you use Proethos2 in dedicated server. Nowadays it's possible with virtualization, docker,
and other tools;
- This manual will help you under this conditions.

Dependencies
------------

### Dependencies that every ubuntu instalation should have.

```
$ sudo apt-get install -y vim openssh-server
```

### Git

```
$ sudo apt-get update
$ sudo apt-get install -y git
$ git config --global user.name "Your github name"
$ git config --global user.email yourgithub@email.com

```

### Apache2

```
$ sudo apt-get install -y apache2
$ sudo a2enmod rewrite

```

### PHP

```
$ sudo apt-get install -y curl php5 php5-cli php5-mysql libapache2-mod-php5 php5-mcrypt php5-gd

```

### MySQL

The next command block is to install MySQL server and to configure it.

```
$ sudo apt-get install -y mysql-server libapache2-mod-auth-mysql
$ sudo mysql_secure_installation
$ sudo mysql_install_db

```

Now, we have to create the user and database that proethos2 will have access.
Type `mysql -uroot -p` and then type the following codes:

```
CREATE USER 'proethos2'@'localhost' IDENTIFIED BY 'choose_a_password!';
CREATE DATABASE proethos2;
GRANT ALL PRIVILEGES ON proethos2.* to proethos2@localhost;

```

### Composer

```
$ cd /tmp
$ curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

```

### wkhtmltopdf

This lib is used to generate the PDF files.

```
$ cd /tmp
$ wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.2.1/wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
$ sudo dpkg --install wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
$ sudo apt-get --yes --fix-broken install
$ sudo dpkg --install wkhtmltox-0.12.2.1_linux-trusty-amd64.deb

```

Creating the file structure and install Proethos2
-------------------------------------------------

We have to create the file structure and download the code:

```
$ mkdir -p project/proethos2
$ cd project/proethos2
$ git clone https://github.com/bireme/proethos2.git git

```

Now, we have to install all the software dependencies and the software as well:
(It can take some minutes)

```
$ cd project/proethos2/git/symphony
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
- `locale (en):` Choose your default language locale. We will use `es_ES`
- `secret (ThisTokenIsNotSoSecretChangeIt):` Choose an secret token for your application.

Now, we will setup the database and load the initial data:

```
$ make load_initial
or
$ make load_initial php5.6
```

__TIP:__ See all the [Make commands](../continuous-integration.md), that certainly will help you.

If you want to test the instalation, run this command:

```
$ make runserver

```

and now access the address `http://YOUR_IP_SERVER:8000/`. If you see the login page, means that you made all right!

Configuring the Apache2 to serve Proethos2
------------------------------------------

Now, we will configure the Apache2 to serve the Proethos2 in the 80 port.

This is a model to you start. Feel free to modify as your needs:

```
<VirtualHost *:80>
    ServerName www.youraddress.com

    ServerAdmin adminemail@localhost
    DocumentRoot /home/<serveruser>/project/proethos2/git/symphony/web

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

We need to put this content on `/etc/apache2/sites-available/proethos2.conf`.
Now, we have to disable the default conf that comes with apache2 and add our conf:

```

$ sudo a2dissite 000-default
$ sudo a2ensite proethos2
$ sudo service apache2 restart

```

Now, we have to give the right permissions to all structure:

```
$ cd ~/project/proethos2/git/symphony
$ rm -rf app/cache/*
$ rm -rf app/logs/*
```

Remember that the directories below needs to have write permissions from apache:
```
app/cache
app/logs
uploads
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

Adding routines to crontab
--------------------------

See the page [How to add routines in crontab](how-to-add-routines-in-crontab.md).


Other configurations and customizations
---------------------------------------

The system comes with pre-stablished configuration. But, if you want to change or customize your instalation, make login
as an admin role and access System Management > Configurations.


That's it!

If you have any questions or difficults to execute this steps, please [open an ticket here](https://github.com/bireme/proethos2/issues).
