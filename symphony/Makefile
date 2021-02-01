# in case that you need to use some different version of php, execute with param php.
# Example: make test php=php5.6
php=php
dt=$(shell date --iso=seconds)

git_pull:
	git pull

test:
	cd app && $(php) /usr/bin/phpunit --stop-on-failure

test_ci:
	cd app && phpunit --stop-on-failure

setup_ci:
	composer global remove "phpunit/phpunit" --update-with-dependencies
	composer global require "phpunit/phpunit=4.8.2"
	composer install --no-interaction
	make load_initial

load_initial: database_update
	$(php) app/console proethos2:load-database-initial-data

update_initial:
	$(php) app/console proethos2:load-database-initial-data --update

generate_initial:
	$(php) app/console proethos2:generate-database-initial-data
	$(php) app/console proethos2:generate-database-translations > fixtures/data_ext_translations.sql

generate_translations:
	
	# generating the files
	$(php) app/console translation:extract pt_BR --dir=src --dir=app/Resources/views --output-dir=./app/Resources/translations
	$(php) app/console translation:extract es_ES --dir=src --dir=app/Resources/views --output-dir=./app/Resources/translations
	$(php) app/console translation:extract fr_FR --dir=src --dir=app/Resources/views --output-dir=./app/Resources/translations

	# "removes all reference-file lines from locale files"
	find app/ -type f -name "*.xlf" -exec sed -i '/jms:reference-file line/d' {} \;
	find app/ -type f -name "*.xlf" -exec sed -i 's/ state\=\"new\"//g' {} \;

database_update:
	$(php) app/console doctrine:schema:update --force

clear_cache:
	mkdir -p app/cache/prod
	mkdir -p app/cache/dev
	mv app/cache/prod app/cache/$(dt)-prod
	mv app/cache/dev app/cache/$(dt)-dev
	rm -rf app/cache/*

set_permissions:
	find -type f -name "*.xlf" | xargs chmod 0777
	find -type f -name "*.sh" | xargs chmod +x

generate_help:
	$(php) app/console proethos2:generate-help-messages

update: git_pull database_update set_permissions clear_cache

runserver:
	$(php) app/console server:run -v 0.0.0.0:8000
