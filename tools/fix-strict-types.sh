#!/bin/bash

export CACHE_FILE="../symphony/app/cache/prod/classes.php"

sed -i 's/.*strict_types/\/\/&/' $CACHE_FILE
