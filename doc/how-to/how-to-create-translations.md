How to create translations for system labels
=================================================================

To create translation labels, you will need to handle with these two types of files:

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

After create a translation label, [you will need to regenerate the translation files](how-to/how-to-generate-new-translation-files.md).
