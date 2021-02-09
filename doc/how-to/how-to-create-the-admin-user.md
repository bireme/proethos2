How to create the admin user (ProEthos2 default authentication)
===============================================================

If is your first access using ProEthos2 default authentication, is required to create the admin user running the command `proethos2:createsuperuser` with the following arguments:

- `email:` User email
- `username:` User name/login
- `password:` Account password (optional)*

Usage:

```
$ php app/console proethos2:createsuperuser --email=EMAIL --username=USERNAME [--password=PASSWORD]
```

Example:

```
$ php app/console proethos2:createsuperuser --email=admin@proethos2.com --username=admin --password=proethos2
```

* If the password is not given, a random 8-digit password will be generated automatically.