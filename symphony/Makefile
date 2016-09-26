define test
	cd app && $1 /usr/bin/phpunit --stop-on-failure
endef

define load_initial
	$1 app/console proethos2:load-database-initial-data
endef

define update_initial
	$1 app/console proethos2:load-database-initial-data --update
endef

define database_update
	$1 app/console doctrine:schema:update --force
endef

define clear_cache
	$1 app/console cache:clear --env=prod
endef

define set_permissions
	find -type f -name "*.xlf" | xargs chmod 0777
	find -type f -name "*.sh" | xargs chmod +x
endef

update:
	$(call database_update, php)
	$(call update_initial, php)
	$(call set_permissions, php)
	$(call clear_cache, php)

update_homolog:
	$(call database_update, php5.6)
	$(call update_initial, php5.6)
	$(call set_permissions, php5.6)
	$(call clear_cache, php5.6)

runserver:
	php app/console server:run -v 0.0.0.0:8000
