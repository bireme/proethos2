#!/bin/bash
# "removes all reference-file lines from locale files"

export DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd "$DIR/../symphony"
# removing lines unused
find app/ -type f -name "*.xlf" -exec sed -i '/jms:reference-file line/d' {} \;

find app/ -type f -name "*.xlf" -exec sed -i 's/ state\=\"new\"//g' {} \;