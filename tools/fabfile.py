#!coding:utf-8
import os
from fabric.api import env, local, settings, abort, run, cd
from fabric.operations import local, put, sudo, get
from fabric.context_managers import prefix
from fabenv import *


env.use_ssh_config = True

def update():
    local('phpunit -c /home/moa/project/proethos2/git/symphony/app')
    
    with prefix(". %s" % env.env_script):
        with cd(env.path):
            run("git pull origin master")
            run("composer.phar update")
            
        with cd(env.symfony_path):
            run("php app/console doctrine:schema:update --force")
            run("php app/console proethos2:load-database-initial-data")
            run("php app/console cache:clear --env=prod")
