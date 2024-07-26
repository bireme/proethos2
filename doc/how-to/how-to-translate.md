How to translate
=================================================================

- In your dev environment, access `http://localhost:8000/_trans/`
- Make your changes and translations
- Commit the changes


Obs: If you see the 404 error, you will need to enable the Apache mod_rewrite module.


If the error persists to resolve this error, two adjustments were necessary:

Include the code snippet below in the routing.yml file:
```yaml
JMSTranslationBundle_ui:
    resource: @JMSTranslationBundle/Controller/
    type:     annotation
    prefix:   /_trans
```

Include the code snippet below in the config_prod.yml file:
```yaml
jms_translation:
    configs:
        app:
            dirs: [%kernel.root_dir%, %kernel.root_dir%/../src]
            output_dir: %kernel.root_dir%/Resources/translations
            ignored_domains: [routes]
            excluded_names: ["*TestCase.php", "*Test.php"]
            excluded_dirs: [cache, data, logs]
```

NOTE: These code snippets are part of the routing_dev.yml and config_dev.yml files, respectively, which are responsible for the Symphony configuration for development environments. Therefore, they should not be included in the production environment configuration files.

Ref: https://github.com/bireme/proethos2/issues/403
