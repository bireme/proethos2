How to create translations
=================================================================

Twig files
----------

Put all your translatable texts inside `{% trans %}`tag. Example:
```
{% trans %}Hello World}{% endtrans %}
```

Controller Files
----------------

- Get the translator object
- Pass the translatable text as parameter to function `$translator->trans();`

Example:
```
$translator = $this->get('translator');
echo $translator->trans('No protocol found');
```


