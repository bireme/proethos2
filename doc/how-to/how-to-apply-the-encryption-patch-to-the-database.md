How to apply the encryption patch to the database (required if ProEthos2 >= 1.6.0)
==================================================================================

In case of updating a previous installation, it is necessary to apply the encryption patch to the database running the command `proethos2:sanitize-sensitive-data`.

Usage:

```
$ php app/console proethos2:sanitize-sensitive-data [-r, --rollback]
```

Example:

```
$ php app/console proethos2:sanitize-sensitive-data
```

To restore the database to a previously defined state, just run the command with the option `-r` or `--rollback`:

```
$ php app/console proethos2:sanitize-sensitive-data --rollback

or

$ php app/console proethos2:sanitize-sensitive-data -r
```

__NOTE 1:__ It is highly recommended to backup the database before running these commands.
__NOTE 2:__ For the command to work correctly, it is necessary to generate and configure the encryption keys (click [here](how-to-install-proethos2-in-ubuntu.md#encryption-keys-required-if-proethos2--160) to find out how to apply the encryption keys)