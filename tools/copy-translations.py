import os
import sys

src = sys.argv[2]
dst = sys.argv[1]

labels = {}
count = 0
key = None
with open(src) as handle:
    for line in handle:
        count += 1
        if '<source>' in line:
            line = line.replace("<source>", "")
            line = line.replace("</source>", "")
            line = line.strip()

            key = line
            labels[key] = None

        if count % 2 == 1:
            if '<target>' in line:
                line = line.replace("<target>", "")
                line = line.replace("</target>", "")
                line = line.strip()          

                labels[key] = line
                key = None

with open(dst) as handle:
    data = handle.read()

for key, value in labels.items():

    key = '<target state="new">%s</target>' % key
    value = '<target state="new">%s</target>' % value
    data = data.replace(key, value)

with open(dst, 'w') as output:
    output.write(data)