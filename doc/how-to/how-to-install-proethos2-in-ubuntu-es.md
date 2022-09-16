[English](how-to-install-proethos2-in-ubuntu.md)

---

Cómo instalar Proethos2 en Ubuntu 20.04 LTS
===========================================

Este documento le ayudará a instalar la plataforma Proethos2 en una instalación estándar de Ubuntu Server 20.04.

Recuerda algunos consejos:
- Recomendamos ENCARECIDAMENTE que utilice Proethos2 en un servidor GNU Linux, distribución Debian como Ubuntu;
- Este manual es una guía paso a paso sobre la instalación de Proethos2 en una versión Ubuntu Server 20.04.4 LTS;
- Se recomienda tener una cuenta sudo'er para realizar la mayoría de las tareas.

Dependencias
------------

### Dependencias que cada instalación en Ubuntu deben tener:

```
$ sudo apt-get install -y openssh-server make unzip   
```

### Configurar Git

```
$ git config --global user.name "Your github name"
$ git config --global user.email yourgithub@email.com
```

### Instalar Apache2

```
$ sudo apt update
$ sudo apt install -y apache2 
```

### Instalar MySQL

El siguiente bloque de comandos es para instalar el servidor MySQL y configurarlo.

```
$ sudo apt update
$ sudo apt install -y mysql-server libapache2-mod-auth-mysql
$ sudo mysql_secure_installation
$ sudo mysql_install_db
```

Ahora, tenemos que crear un usuario y una base de datos a la que tendrá acceso ProEthos2.
Escriba `sudo mysql` y luego escriba los siguientes códigos:

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

Esta biblioteca se utiliza para generar los archivos PDF.

```
$ cd ~ && wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.focal_amd64.deb
$ sudo apt install ./wkhtmltox_0.12.6-1.focal_amd64.deb
```

__NOTA:__ Para las últimas versiones de Ubuntu, acceda [aquí](https://wkhtmltopdf.org/downloads.html) para descargar el paquete correcto para su instalación.

Crear la estructura de archivos e instalar ProEthos2
----------------------------------------------------

Tenemos que crear la estructura de archivos y descargar el código:

```
$ mkdir -p proethos2
$ cd proethos2
$ git clone https://github.com/bireme/proethos2.git proethos2

```

Ahora, tenemos que instalar todas las dependencias del software y también el software (puede llevar algunos minutos, si lleva mucho tiempo, asegúrese de haber instalado los paquetes `php-curl` y `unzip`).

Antes de instalar las dependencias usando Composer, genere `private_key` e `index_key` para el cifrado. Después de generar una copia de las claves, las pasará cuando se le solicite en el proceso de instalación:

```
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)) . PHP_EOL; ?>' # private_key
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)) . PHP_EOL; ?>' # index_key
```

Instalar las dependencias de ProEthos2:

```
$ cd proethos2/symphony
$ composer install

```

En medio del proceso, usted será cuestionado por las siguientes preguntas:

- `database_driver (pdo_mysql):` Usamos MySQL, así que presiona enter.
- `database_host (127.0.0.1): ` Usamos una instalación local de MySQL (en este caso), así que presione enter.
- `database_port (null):` Usamos un puerto estándar, así que presione enter.
- `database_name (symfony):` Completa con el nombre de la base de datos que creamos. En este caso `proethos2`.
- `database_user (root):` Rellenar con el usuario que hemos creado. En este caso `proethos2`.
- `database_password (null):` Rellenar con la contraseña de la base de datos que creamos. En este caso `choose_a_password!`.
- `mailer_transport (smtp):` Configuraremos estas opciones más adelante, así que presione enter para las opciones de SMTP.
- `locale (en):` Elija su idioma estándar. Usaremos `en_US`
- `auth_type (default):` Elija el tipo de autenticación (`default` o `oauth2`).
- `secret (ThisTokenIsNotSoSecretChangeIt):` Elija un token secreto para su aplicación.
- `private_key (null):` Complete con la clave privada para el cifrado de la base de datos (haga clic [aquí](how-to-install-proethos2-in-ubuntu.md#encryption-keys-required-if-proethos2--160) para generar la clave privada).
- `index_key (null):` Rellene con la clave de índice para el cifrado de la base de datos (haga clic [aquí](how-to-install-proethos2-in-ubuntu.md#encryption-keys-required-if-proethos2--160) para generar la clave de índice).

__NOTAS:__
- Si el error ```proc_open(): fork failed errors``` ocurre durante la instalación, acceda [aquí](https://getcomposer.org/doc/articles/troubleshooting.md#proc-open-fork-failed-errors) para solucionarlo.
- Si el error `Warning: "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?` ocurre durante la instalación, ejecute los siguientes comandos:

```
$ cd ../tools
$ chmod a+x fix-doctrine-orm.sh && ./fix-doctrine-orm.sh
$ cd ../symphony
$ composer install
```

Ahora, vamos a configurar la base de datos y cargar los datos iniciales:

```
$ cd proethos2/symphony
$ make load_initial
```

__NOTA:__ Ver todos los [Make commands](../make-shortcuts.md), que sin duda te ayudarán.

Recuerde que los directorios siguientes deben tener permisos de escritura de Apache:

```
sudo chgrp www-data -R app/logs
sudo chgrp www-data -R app/cache
sudo chgrp www-data -R uploads
chmod -R 0775 app/cache
chmod -R 0775 app/logs
chmod -R 0775 uploads
```

Y ahora ejecute todas las pruebas para ver si todo está bien:

```
$ make test
```

Si desea probar la instalación, ejecute este comando:

```
$ make runserver

```

Y ahora acceda a la dirección `http://SU_SERVIDOR_IP:8000/`. Si ve la página de inicio de sesión, significa que lo hizo todo bien.

Si `phpunit` no lo está o `google.analytics` no está configurado correctamente, es posible que obtenga una página de error. En este caso, vaya a la página siguiente e intente ejecutar la aplicación desde el servidor Apache.


Configuración de Apache2 para servir a ProEthos2
------------------------------------------------

Ahora, configuraremos Apache2 para servir ProEthos2 en el puerto 80.

Necesitamos crear y colocar las líneas de configuración de Apache en `/etc/apache2/sites-disponible/proethos2.conf`.

Usando `nano` o su editor de texto favorito, cree un archivo:

```
sudo nano /etc/apache2/sites-available/proethos2.conf
```
Luego copia el siguiente texto. __Asegúrese de que la ruta del archivo `/home/<username>/proethos2/symphony/web` esté configurada correctamente__.

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


Ahora, tenemos que deshabilitar la configuración estándar que viene con Apache2 y agregar nuestra configuración:

```

$ sudo a2dissite 000-default
$ sudo a2ensite proethos2
$ sudo service apache2 restart

```

Ahora, tenemos que dar los permisos correctos a toda la estructura:

```
$ cd ~/proethos2/symphony
$ rm -rf app/cache/*
$ rm -rf app/logs/*
```

Ahora, intente acceder a su instalación viendo su servidor desde un navegador web. Si obtiene una pantalla de inicio de sesión, use el siguiente comando para crear un usuario con privilegios de administrador:

```
php app/console proethos2:createsuperuser --email=EMAIL --username=USERNAME --password=PASSWORD
```

Configuración de software
=========================

SMTP y correos electrónicos
---------------------------

Vaya a `app/config/parameters.yml` y agregue/cambie estos parámetros, según su servicio de correo electrónico:

```
mailer_transport: smtp
mailer_host: 127.0.0.1
mailer_user: null
mailer_password: null

```

Para obtener más información sobre la configuración del correo electrónico, acceda a http://symfony.com/doc/2.7/email.html.
Si la plataforma ProEthos no envía correos electrónicos después de las instrucciones anteriores, consulte el problema [#354](https://github.com/bireme/proethos2/issues/354)

Autenticación Oauth2 (Azure AD)
-------------------------------

Cree el archivo `.env`:

```
$ touch .env
```

Abra el archivo `.env` y agregue/cambie estos parámetros, de acuerdo con la configuración de su aplicación de Azure (Tenant ID, Client ID y Client Secret):

```
AZURE_TENANT_ID: ??????????
AZURE_CLIENT_ID: ??????????
AZURE_CLIENT_SECRET: ??????????

```

__NOTAS:__
- Esta configuración es obligatoria solo si eligió `oauth2` como `auth_type` durante la instalación (confirme en `app/config/parameters.yml`)
- Para el primer acceso, se requiere crear el usuario administrador y delegar sus roles. Consulte la página [Cómo delegar roles de usuario en el primer acceso usando Oauth2](how-to-delegate-user-roles-on-the-first-access-using-oauth2.md)

Claves de cifrado (obligatorias si ProEthos2 >= 1.6.0)
------------------------------------------------

Genere la `private_key`:
```
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)) . PHP_EOL; ?>'
```

Genere la `index_key`:

```
$ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)) . PHP_EOL; ?>'
```

Copie las claves, vaya a `app/config/parameters.yml` y agregue/cambie estos parámetros:

```
private_key: ??????????
index_key: ??????????

```

Ejecute este comando para actualizar la configuración:

```
$ make update

```

__NOTA:__ En caso de actualizar una instalación anterior, es necesario aplicar el patch de encriptación de la base de datos. Consulte la página [Cómo aplicar el parche de cifrado a la base de datos](how-to-apply-the-encryption-patch-to-the-database.md)

Agregar rutinas a crontab
-------------------------

Consulte la página [Cómo agregar rutinas en crontab](how-to-add-routines-in-crontab.md).

Otras configuraciones y personalizaciones
-----------------------------------------

El sistema viene con una configuración preestablecida. Pero, si desea cambiar o personalizar su instalación, inicie sesión como administrador y acceda a `Administración del Sistema > Configuraciones`.

Para crear el usuario administrador utilizando la autenticación estándar de ProEthos2, consulte la página [Cómo crear el usuario administrador] (how-to-create-the-admin-user.md)

__NOTA:__ A partir de la versión 1.5.0, ha habido cambios importantes en el sistema (autenticación oauth2 y encriptación de la base de datos) que requieren más ajustes de configuración. Si desea utilizar una versión del sistema con menos funciones, pero con una instalación más sencilla, simplemente instale cualquier versión <= 1.4.0

¡Eso es todo!

Si tiene alguna pregunta o dificultad para ejecutar estos pasos, [abra un ticket aquí] (https://github.com/bireme/proethos2/issues).