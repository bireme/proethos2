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

__NOTE:__ It is highly recommended to backup the database before running these commands.
