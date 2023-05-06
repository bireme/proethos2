[Español](proethos2-update-es.md)

---

How to update ProEthos2
-----------------------

Access the directory `htdocs/git/symphony/`

    $ cd htdocs/git/symphony/

Execute the following commands:

    $ git checkout master
    $ git status [check the current state of the repository]
    $ git pull

To compile and update the application, execute the following command:

    $ composer update [see note at bottom of this page]
    $ sudo make update

To update the database, execute the following command:

    $ make update_initial

Lastly:

    $ service apache2 restart
    or
    $ systemctl restart apache2

__NOTE:__ In case of updating a previous installation (ProEthos2 < 1.5.0), it is necessary to apply the encryption patch to the database. See the page [How to apply the encryption patch to the database](how-to/how-to-apply-the-encryption-patch-to-the-database.md)