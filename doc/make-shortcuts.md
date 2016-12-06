Make shortcuts
==============

To use these `make` shortcuts, go to `symphony` directory.

| command | description |
|---------|-------------|
| `make git_pull` | update the current branch |
| `make test` | execute all tests from the system |
| `make test_ci` | execute all tests from the system, especific to [Continuous Integration](continuous-integration.md) |
| `make setup_ci` | make all system instalation (except wkhtmltopdf extensions), especific to [Continuous Integration](continuous-integration.md) |
| `make load_initial` | loads all the initial project data, and a few data example |
| `make update_initial` | updates all the controlled lists from project data  |
| `make generate_initial` | generates all the initial data to the project (this is a dev feature) |
| `make generate_translations` | generates all translate files (this is a dev feature) |
| `make database_update` | update the database structure |
| `make clear_cache` | cleans all symfony caches |
| `make set_permissions` | set permissions that allow users to translate (this is a dev feature) |
| `make generate_help` | generates new helps on database, [based on these documentation](how-to/how-to-generate-help-messages-in-database-to-my-new-development) (this is a dev feature) |
| `make update` | executes all commands to update correctly the system |
| `make runserver` | runs the development server (this is a dev feature) |

Considerations
--------------

- When you need to use a especific PHP version in command line, execute the make with `php` param. In the example below, we are executing update command with php5.6 binary.
```
make update php=php5.6
```
