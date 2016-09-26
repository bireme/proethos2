test:
	cd app && php /usr/bin/phpunit

load_initial:
	php app/console proethos2:load-database-initial-data

database_update:
	php app/console doctrine:schema:update --force

runserver:
	load_initial;
	database_update;
	php app/console server:run -v 0.0.0.0:8000
