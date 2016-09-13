How to generate new database initial data from my dev environment
=================================================================

If you need generate new initial data on development, you need to go to `symphony/` directory and run this command:
```
php app/console proethos2:generate-database-initial-data
```

Then, generate translations:
```
php app/console proethos2:generate-database-translations > fixtures/data_ext_translations.sql
```
