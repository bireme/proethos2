#!/usr/bin/env python
#!coding:utf-8

import os
import sys
from xmltodict import parse


def print_keys(data, prefix=None):
    for key, value in data.items():

        output_string = key
        if prefix:
            output_string = "{0},{1}".format(prefix, key)

        if type(value) == type(data):
            print_keys(value, prefix=output_string)
        else:
            print output_string

xml_filename = sys.argv[1]

with open(xml_filename) as handle:
    data = handle.read()
    data = parse(data)

print_keys(data)
