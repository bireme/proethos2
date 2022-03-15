PROETHOS2 application update
----------------------------

Access the directory `htdocs/git/symphony/`

    $ cd htdocs/git/symphony/

Execute the following commands:

    $ git checkout master
    $ git status [check the current state of the repository]
    $ git pull

To compile and update the application, execute the following command:

    $ make update

To update the database, execute the following command:

    $ make update_initial

Lastly:

    $ service apache2 restart
    or
    $ systemctl restart apache2
