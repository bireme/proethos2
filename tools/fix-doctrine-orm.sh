#!/bin/bash

export DIR="`dirname $(pwd -P)`/symphony"
export DOCTRINE_ORM_DIR="$DIR/vendor/doctrine/orm/lib/Doctrine/ORM/"
export DOCTRINE_ORM_FILE="UnitOfWork.php"

if [ -f "$DOCTRINE_ORM_DIR/$DOCTRINE_ORM_FILE" -a  ! -L "$DOCTRINE_ORM_DIR/$DOCTRINE_ORM_FILE" ]
then
	cd $DOCTRINE_ORM_DIR
	mv $DOCTRINE_ORM_FILE $DOCTRINE_ORM_FILE".old"
	ln -s $DIR/app/$DOCTRINE_ORM_FILE
	cd -
fi
