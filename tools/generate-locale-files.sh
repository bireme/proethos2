#!/bin/bash

export DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd "$DIR/../symphony"
php app/console translation:extract es_ES --dir=./src/ --output-dir=./app/Resources/translations
php app/console translation:extract pt_BR --dir=./src/ --output-dir=./app/Resources/translations
php app/console translation:extract fr_FR --dir=./src/ --output-dir=./app/Resources/translations