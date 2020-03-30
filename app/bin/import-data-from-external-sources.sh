#!/usr/bin/env bash

appRoot=$(readlink -f $(dirname $0)/..)

python3 $appRoot/py_scripts/airtable2sql/airtable_to_sql.py
