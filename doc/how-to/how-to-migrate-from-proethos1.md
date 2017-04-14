How to migrate your database from Proethos1
===========================================

First of all, you need to setup a clean installation of Proethos2.

So, you need to upload your old database to the current mysql instalation, in a fresh database separated from the
current new database.

Then, you need to copy the all the proethos1 files to server that proethos2 will be installed.

Then, you need to execute this command with the follow parameters:

- `proethos1_database_name`: The database name that has the old data.
- `default_country_code`: The default country code that will be used to assign all registers and users. Use the
ISO ALPHA-2 Code from this table: http://www.nationsonline.org/oneworld/country_code_list.htm
- `old_directory`: The path from directory called "document" from old proethos installation.

```
php app/console proethos2:migrate-proethos1-database <proethos1_database_name> <default_country_code> <old_directory>
```
