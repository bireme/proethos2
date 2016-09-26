#!coding: utf-8
"""fabmodule."""


from fabric.api import env, run, cd
from fabric.context_managers import prefix


env.use_ssh_config = True


# def update():
#     """Update the system doing the tests."""
#     local("""cd """)
#
#     with prefix(". %s" % env.env_script):
#         with cd(env.path):
#             run("git pull origin master")
#             run("composer.phar update")
#
#         with cd(env.symfony_path):
#             run("php5.6 app/console doctrine:schema:update --force")
#             # run("php5.6 app/console proethos2:load-database-initial-data")
#             run('find -type f -name "*.xlf" | xargs chmod 0777')
#             run("php5.6 app/console cache:clear --env=prod")


def fast_update():
    """Update the system without the tests."""
    with prefix(". %s" % env.env_script):
        with cd(env.path):
            run("git pull origin master")

        with cd(env.symfony_path):
            run("make update")
