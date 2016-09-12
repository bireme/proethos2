#!/bin/bash
# "removes all reference-file lines from locale files"

export DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd "$DIR/../symphony"
find app/ -type f -name "*.xlf" -exec sed -i '/jms:reference-file line/d' {} \;