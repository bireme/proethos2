[English](doc/proethos2-update.md)

---

Cómo actualizar ProEthos2
-------------------------

Acceder el directorio `htdocs/git/symphony/`

    $ cd htdocs/git/symphony/

Ejecuta los siguientes comandos:

    $ git checkout master
    $ git status [comprobar el estado actual del repositorio]
    $ git pull

Para compilar y actualizar la aplicación, ejecute el siguiente comando:

    $ make update

Para actualizar la base de datos, ejecute el siguiente comando:

    $ make update_initial

Por último:

    $ service apache2 restart
    o
    $ systemctl restart apache2

__NOTA:__ En caso de actualizar una instalación anterior (ProEthos2 < 1.5.0), es necesario aplicar el patch de encriptación a la base de datos. Consulte la página [Cómo aplicar el parche de cifrado a la base de datos](how-to/how-to-apply-the-encryption-patch-to-the-database.md)