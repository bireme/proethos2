Troubleshooting (Ubuntu distro)
-------------------------------

1. Error `proc_open(): fork failed errors` occurs during installation

   Read this instructions to fix it: https://getcomposer.org/doc/articles/troubleshooting.md#proc-open-fork-failed-errors

---

2. Error `Warning: "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?` occurs during installation

   Run the following commands:

```
$ cd ../tools
$ chmod a+x fix-doctrine-orm.sh && ./fix-doctrine-orm.sh
$ cd ../symphony
$ composer install
```

---

3. Error `Argument 1 passed to Symfony\Component\Process\Process::__construct() must be of the type array, string given` occurs during installation

   Run the following command to update Composer to version 2.2.9:

```
$ composer self-update 2.2.9
```

---

4. The environment is not using HTTPS protocol

   Setting the `cookie_secure` variable to `false` in the `symphony/app/config/config.xml` file (after this fix, run the `sudo make update` command inside the `symphony` directory):

```
framework:
    session:
        cookie_secure: false
```

---

5. Error `Cannot use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag as ParameterBag because the name is already in use` occurs during installation

   This error occurs in environment with PHP 8. To fix it, install PHP 7 (or earlier) running the command below:

```
$ sudo apt install php php-mysql php-gd php-mbstring php-phar php-cli apache2-mod_php7
```

---

6. Error `Attempted to call function "sodium_cripto_secretbox"` occurs when creating the super user

   Install all required Sodium libraries running the following command:

```
$ sudo apt install libsodium libsodium-dev php-sodium php-libsodium
```