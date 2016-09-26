# in case that you need to use some different version of php, execute with param php.
# Example: make test php=php5.6
php=php

git_pull:
	git pull

test:
	cd app && $(php) /usr/bin/phpunit --stop-on-failure

load_initial: database_update
	$(php) app/console proethos2:load-database-initial-data

update_initial:
	$(php) app/console proethos2:load-database-initial-data --update

generate_initial:
	$(php) app/console proethos2:generate-database-initial-data
	$(php) app/console proethos2:generate-database-translations > fixtures/data_ext_translations.sql

generate_translations:

	# generating the files
	$(php) app/console translation:extract pt_BR --dir=./src/ --output-dir=./app/Resources/translations
	$(php) app/console translation:extract es_ES --dir=./src/ --output-dir=./app/Resources/translations
	$(php) app/console translation:extract fr_FR --dir=./src/ --output-dir=./app/Resources/translations

	# "removes all reference-file lines from locale files"
	find app/ -type f -name "*.xlf" -exec sed -i '/jms:reference-file line/d' {} \;
	find app/ -type f -name "*.xlf" -exec sed -i 's/ state\=\"new\"//g' {} \;

database_update:
	$(php) app/console doctrine:schema:update --force

clear_cache:
	$(php) app/console cache:clear --env=prod

set_permissions:
	find -type f -name "*.xlf" | xargs chmod 0777
	find -type f -name "*.sh" | xargs chmod +x

generate_help:
	$(php) app/console proethos2:generate-help-messages

update: git_pull database_update update_initial set_permissions clear_cache

runserver:
	$(php) app/console server:run -v 0.0.0.0:8000
