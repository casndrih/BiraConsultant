#!/system/bin/sh
# Start BiraConsultant inside the Oreshnik workspace.
APP_FILES=/data/user/0/com.example.oreshik.debug/files
BIRACONSULTANT="$APP_FILES/workspace/BiraConsultant"
PHP="$APP_FILES/runtimes/php/bin/php"
PID_FILE="$BIRACONSULTANT/storage/server.pid"
LOG_FILE="$BIRACONSULTANT/storage/server.log"

export LD_LIBRARY_PATH="$APP_FILES/runtimes/libs/lib:$APP_FILES/runtimes/clang/lib"
export TMPDIR="$BIRACONSULTANT/storage/tmp"

if [ -f "$PID_FILE" ]; then
    server_pid="$(cat "$PID_FILE")"
    if kill -0 "$server_pid" 2>/dev/null; then
        echo "BiraConsultant server already running (pid $server_pid)"
        exit 0
    fi
fi

cd "$BIRACONSULTANT/public" || exit 1
nohup "$PHP" -d opcache.enable=0 -d opcache.enable_cli=0 \
    -d upload_max_filesize=10M -d post_max_size=12M \
    -S 127.0.0.1:8000 "$BIRACONSULTANT/storage/router.php" \
    >"$LOG_FILE" 2>&1 </dev/null &
server_pid=$!
printf '%s\n' "$server_pid" > "$PID_FILE"
sleep 1
if kill -0 "$server_pid" 2>/dev/null; then
    echo "BiraConsultant server started at 127.0.0.1:8000 (pid $server_pid)"
    exit 0
fi

echo "BiraConsultant server failed to start; see $LOG_FILE"
exit 1
