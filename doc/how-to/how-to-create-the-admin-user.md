How to create the admin user (ProEthos2 default authentication)
===============================================================

If is your first access using ProEthos2 default authentication, is required to create the admin user running the command `proethos2:createsuperuser` with the following arguments:

- `email:` User email
- `username:` User name/login
- `password:` Account password (optional)

Usage:

```
$ php app/console proethos2:createsuperuser --email=EMAIL --username=USERNAME [--password=PASSWORD]
```

Example:

```
$ php app/console proethos2:createsuperuser --email=admin@proethos2.com --username=admin --password=proethos2
```

__NOTE:__
- If the password is not given, a random 8-digit password will be generated automatically.
- If the error ```CSRF token not valid``` occurs during the creation of admin user, access the issue [#538](https://github.com/bireme/proethos2/issues/538) to fix it.