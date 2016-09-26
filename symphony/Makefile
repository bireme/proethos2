define test
	cd app && php /usr/bin/phpunit --stop-on-failure
endef

define load_initial
	php app/console proethos2:load-database-initial-data
endef

define update_initial
	php app/console proethos2:load-database-initial-data --update
endef

define database_update
	php app/console doctrine:schema:update --force
endef

define clear_cache
	php app/console cache:clear --env=prod
endef

define set_permissions
	find -type f -name "*.xlf" | xargs chmod 0777
	find -type f -name "*.sh" | xargs chmod +x
endef

update:
	$(call database_update)
	$(call update_initial)
	$(call set_permissions)
	$(call clear_cache)

runserver:
	php app/console server:run -v 0.0.0.0:8000
