#!coding:utf-8

import os
import sys

file = sys.argv[1]

pattern = ["',1,'", ',0,NULL',]

output = ""
for item in pattern:
    with open(file) as handle:
        for line in handle:
            if item in line:
                output += line

with open(file, 'w') as  handle:
    handle.write(output)
