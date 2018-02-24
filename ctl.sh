#!/bin/bash

PWD=`cd $(dirname "${BASH_SOURCE[0]}") && pwd`
APP_BIN="php"
APP_ENTRY="$PWD/index.php"
LOG_DIR="$PWD/application/logs"
PROGS=(
    "apns feedloop"
    "apns pushloop prod"
)

PROC_ID=""
PROC_ID_LIST=""

get_concat_name() {
    echo "$*" | tr ' ' '-'
}

chk_pidfile() {
    local name="$1"
    local pid_file="$LOG_DIR/$name.pid"
    if [ ! -f "$pid_file" ]; then
        echo "$pid_file is not exists"
        exit 0;
    fi
}

chk_process() {
    local name="$1"
    local pid_file="$LOG_DIR/$name.pid"
    PROC_ID=`cat $pid_file`;
    if [ -z "$PROC_ID" ]; then
        echo "$pid_file is empty"
        exit 0;
    fi
    local FIND_ID=`ps -f --no-heading --pid $PROC_ID | awk '/$PROC_CMD/{print $2}'`
    if [ ! x"$FIND_ID" = x"$PROC_ID" ]; then
        echo "Process $PROC_ID has termined"
    fi
}

grep_process_pids() {
    local params="$*"
    local app_file="$APP_ENTRY $params"
    PROC_ID_LIST=`ps -efww | grep "$app_file" | grep -v grep | cut -c 9-15 | xargs`
    if [ -z "$PROC_ID_LIST" ]; then
        echo "Process $params is not running"
    fi
}

split_logfile() {
    local name="$1"
    local log_file="$LOG_DIR/$name.log"
    if [ -f "$log_file" ] && [ -s "$log_file" ]; then
        local lognum=`ls -1 $log_file* | wc -l`
        if [ "$lognum" -gt "1" ]; then
            local lastfile=`ls -1 $log_file* | tail -1`
            lognum=`expr ${lastfile##*.} + 1`
        fi
        local flowno=`printf %03d $lognum`
        mv "$log_file" "$log_file.$flowno"
    fi
}

start_server() {
    local params="$*"
    local name=$(get_concat_name $params)
    split_logfile $name
    local pid_file="$LOG_DIR/$name.pid"
    local log_file="$LOG_DIR/$name.log"
    local app_file="$APP_ENTRY $params"
    $APP_BIN $app_file >> $log_file 2>&1 &
    echo $! > $pid_file
    echo "$params starting"
}

stop_server() {
    local params="$*"
    local name=$(get_concat_name $params)
    local pid_file="$LOG_DIR/$name.pid"
    chk_pidfile $name && chk_process $name
    if [[ "$PROC_ID" =~ ^[0-9]+$ ]]; then
        kill -9 $PROC_ID
    fi
    rm -f $pid_file
    echo "$params stopped"
}

close_all_server() {
    local params="$*"
    grep_process_pids $params
    if [ ! x"$PROC_ID_LIST" = x ]; then
        kill -9 $PROC_ID_LIST
    fi
    echo "$params closed"
}

keep_server_running() {
    local params="$*"
    grep_process_pids $params
    if [ x"$PROC_ID_LIST" = x ]; then
        start_server $params
    fi
}


case "$1" in
    start)
        for name in "${PROGS[@]}" ; do
            start_server $name
        done
        ;;
    stop)
        for name in "${PROGS[@]}" ; do
            stop_server $name
        done
        ;;
    restart | reload)
        for name in "${PROGS[@]}" ; do
            close_all_server $name
            start_server $name
        done
        ;;
    close)
        for name in "${PROGS[@]}" ; do
            close_all_server $name
        done
        ;;
    kill)
        for name in "${PROGS[@]}" ; do
            close_all_server $name
        done
        ;;
    *)
        for name in "${PROGS[@]}" ; do
            keep_server_running $name
        done
esac