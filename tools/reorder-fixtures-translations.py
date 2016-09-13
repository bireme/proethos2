#!coding:utf-8

import os
import sys

file = sys.argv[1]

languages = ['en', 'es_ES', 'pt_BR', 'fr_FR']

output = ""
for language in languages:
    with open(file) as handle:
        for line in handle:
            if "'%s'" % language in line:
                output += line

with open(file, 'w') as  handle:
    handle.write(output)
