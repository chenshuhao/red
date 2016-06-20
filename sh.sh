while true
do
    ps -ef|grep -v grep|grep 'php cli.php'
    if [ $? -ne 0 ]; then
        echo '进程已挂,正在重启..'
        echo 'staring..'
        php /webroot/hb/cli.php
        echo 'ok..'
    fi
    sleep 10
done
