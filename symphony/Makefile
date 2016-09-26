test:
	cd app && php /usr/bin/phpunit --stop-on-failure

load_initial:
	php app/console proethos2:load-database-initial-data

update_initial:
	php app/console proethos2:load-database-initial-data --update

database_update:
	php app/console doctrine:schema:update --force

clear_cache:
	php app/console cache:clear --env=prod

set_permissions:
	find -type f -name "*.xlf" | xargs chmod 0777
	find -type f -name "*.sh" | xargs chmod +x

update:
	database_update;
	update_initial;
	set_permissions;
	clear_cache;

runserver:
	update_initial;
	database_update;
	php app/console server:run -v 0.0.0.0:8000
