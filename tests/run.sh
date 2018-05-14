#!/bin/bash

PWD=`cd $(dirname "${BASH_SOURCE[0]}") && pwd`
APP_BIN="php"

case "$1" in
    -s | --simple | simple)
        $APP_BIN "$PWD/simple-tests.php" "$2"
        ;;
    *)
        $APP_BIN "$PWD/run-tests.php" -P */*.phpt
esac
