#!/bin/bash

export BKP_DIR="bkp"
export MYSQL_USER=""
export MYSQL_PASS=""
export MYSQL_HOST="localhost"
export MYSQL_BASE="proethos"

# goes to the user home directory
cd

# create the folder if that not exists
mkdir -p $BKP_DIR

# make the backup
mysqldump -h $MYSQL_HOST -u$MYSQL_USER -p$MYSQL_PASS $MYSQL_BASE \
    --replace \
    --no-create-info \
    --complete-insert \
    --compact \
    --extended-insert=FALSE \
    > $BKP_DIR/$MYSQL_BASE-$(date "+%Y-%m-%d-%H-%M-%S").sql