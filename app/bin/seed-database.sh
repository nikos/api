#!/usr/bin/env bash

appRoot=$(readlink -f $(dirname $0)/..)

$appRoot/vendor/bin/phinx seed:run
