Hot to install proethos2 in Ubuntu 14.04 LTS
============================================

This document will help you how to install Proethos2 platform in a Ubuntu Server 16.04 standard installation.
Remember some tips:
- We STRONGLY recommend that you use Proethos2 in a Linux Server
- We recommend that you use Proethos2 in Ubuntu Server
- We STRONGLY recommend that you use Proethos2 in dedicated server. Nowadays it's possible with virtualization, docker, 
and other tools
- This manual will help you under this conditions

Dependencies
------------

### Other depencencies to every ubuntu instalation

```
$ sudo apt-get install -y vim openssh-server
```
### Git

```
$ sudo apt-get update
$ sudo apt-get install -y git
$ git config --global user.name "You github name"
$ git config --global user.email yourgithub@email.com

```

### MySQL

The next command block is to install MySQL server and to config it.

```
$ sudo apt-get install -y mysql-server
$ sudo mysql_secure_installation
$ sudo mysql_install_db

```

Now, we have to create the user and database that proethos2 will have access.
Type `mysql -uroot -p` and next type the following codes:

```
CREATE USER 'proethos2'@'localhost' IDENTIFIED BY 'choose_a_password!';
CREATE DATABASE proethos2;
GRANT ALL PRIVILEGES ON proethos2.* to proethos2@localhost;

```


### PHP

```
$ sudo apt-get install -y curl php5-cli

```
### Composer

```
$ cd /tmp
$ curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

```

### Apache2

```
$ sudo apt-get install -y apache2

```

Creating the file structure and install Proethos2
-------------------------------------------------

Now, we have to create the file structure and download the code:

```
$ mkdir -p project/proethos2
$ cd project/proethos2
$ git clone https://github.com/bireme/proethos2.git git

```
