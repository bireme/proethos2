Troubleshooting (Windows)
------------------------

1. Error `proc_open(): fork failed errors` occurs during installation

   Read this instructions to fix it: https://getcomposer.org/doc/articles/troubleshooting.md#proc-open-fork-failed-errors

---

2. Error `Warning: "continue" targeting switch is equivalent to "break". Did you mean to use "continue 2"?` occurs during installation

   Run the following commands:

```
$ cd ../tools
$ bash ./fix-doctrine-orm.sh
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

   This error occurs in environment with PHP 8. To fix it, install PHP 7 (or earlier):

   If you are using Xampp, this error can be solved reistalling the Xampp with the corresponding version.

---

6. Make sure that you fill the keys

 Generate the private_key:

      $ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)) . PHP_EOL; ?>'

Generate the index_key:
   
      $ php -r 'echo base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES)) . PHP_EOL; ?>'


Copy the keys, go to symphony/app/config/parameters.yml and add/change these parameters:

private_key: ??????????
index_key: ??????????

Run this command to refresh the settings:

      $ make update



