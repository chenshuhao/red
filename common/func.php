<?php
#公共函数库

function renderJSON($error, $error_reason = '', $opts = null)
{
    #ob_end_clean();
    header("Content-type: application/json; charset=utf-8");
    $result = array('error' => $error, 'error_reason' => $error_reason);
    if ($opts) {
        $result = array_merge($result, $opts);
    }

    ob_clean();

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function echoLine()
{
    $list = func_get_args();
    echo '[PID ' . posix_getpid() . ']' . implode('', $list) . PHP_EOL;
}


function env($key, $default_value = null)
{
    $env = getenv($key);
    if ($env === false) {
        return $default_value;
    } else {
        return $env;
    }
}


function isPresent($object){
    return !isBlank($object);
}

# 只有null和空串返回true, 0和'0'表示存在
function isBlank($object)
{
    if ($object || (is_numeric($object) && 0 == $object)){
        return false;
    }
    return true;
}

# 转utf8
function force_utf8($str)
{
    if (!$str) {
        return $str;
    }
    $b = unpack('C*', $str);
    $r = array();
    $index = 0;
    $s = '';
    foreach ($b as $key => $value) {
        if ($key < $index) {
            continue;
        }
        if ($value >> 7 == bindec('0')) {
            $r[] = $value;
            $index = $key;
        }
        if ($value >> 5 == bindec('110') && count($b) > ($key + 1) && $b[$key + 1] >> 6 == bindec('10')) {
            $r[] = $value;
            $r[] = $b[$key + 1];
            $index = $key + 1;
        }
        if ($value >> 4 == bindec('1110') && count($b) > ($key + 2) && $b[$key + 1] >> 6 == bindec('10') && $b[$key + 2] >> 6 == bindec('10')) {
            $r[] = $value;
            $r[] = $b[$key + 1];
            $r[] = $b[$key + 2];
            $index = $key + 2;
        }
        if ($value >> 3 == bindec('11110') && count($b) > ($key + 3) && $b[$key + 1] >> 6 == bindec('10') && $b[$key + 2] >> 6 == bindec('10') && $b[$key + 3] >> 6 == bindec('10')) {
            $r[] = $value;
            $r[] = $b[$key + 1];
            $r[] = $b[$key + 2];
            $r[] = $b[$key + 3];
            $index = $key + 2;
        }
        if ($value >> 2 == bindec('111110') && count($b) > ($key + 4) && $b[$key + 1] >> 6 == bindec('10') && $b[$key + 2] >> 6 == bindec('10') && $b[$key + 3] >> 6 == bindec('10') && $b[$key + 4] >> 6 == bindec('10')) {
            $r[] = $value;
            $r[] = $b[$key + 1];
            $r[] = $b[$key + 2];
            $r[] = $b[$key + 3];
            $r[] = $b[$key + 4];
            $index = $key + 3;
        }
        if ($value >> 1 == bindec('1111110') && count($b) > ($key + 5) && $b[$key + 1] >> 6 == bindec('10') && $b[$key + 2] >> 6 == bindec('10') && $b[$key + 3] >> 6 == bindec('10') && $b[$key + 4] >> 6 == bindec('10') && $b[$key + 5] >> 6 == bindec('10')) {
            $r[] = $value;
            $r[] = $b[$key + 1];
            $r[] = $b[$key + 2];
            $r[] = $b[$key + 3];
            $r[] = $b[$key + 4];
            $r[] = $b[$key + 5];
            $index = $key + 4;
        }
    }
    foreach ($r as $value) {
        $s .= pack('C*', $value);
    }
    return $s;
}

function isProduction()
{
    $env = env('friends_env', null);
    return $env === "production";
}


# 生成len字节的字符串
function hex($len = 32)
{
    $str = '1234567890abcdefghijklmnopqrstuvwxyz';
    $rndstr = ''; //用来存放生成的随机字符串
    for ($i = 0; $i < $len; $i++) {
        $rndcode = mt_rand(0, 35);
        $rndstr .= $str[$rndcode];
    }

    return $rndstr;
}




# 异步日志
function asyncLog($message)
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($trace) {
        $file = $trace[0]['file'];
        $line = $trace[0]['line']; # 在那一行调用了debug方法
        $fun = '';
        $class = '';
        if (isset($trace[1])) {
            $fun = $trace[1]['function'];
            if (isset($trace[1]['class'])) {
                $class = $trace[1]['class'];
            }
        }

        $ex = explode(APP_ROOT_DIR, $file);
        $file = $ex[1];
        if (!$class || !$fun) {
            $message = "[{$file}:{$line}] ". $message;
        }else{
            $message = "[{$file}:{$line}]【{$class}:{$fun}】". $message;
        }
    }

    $message = " [pid:".posix_getpid()."] ".$message;

    $di = Phalcon\Di::getDefault();
    $di->get('async_logger')->debug($message);
}

function debug($message)
{
    if (!isProduction()) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if ($trace) {
            $file = $trace[0]['file'];
            $line = $trace[0]['line']; # 在那一行调用了debug方法
            $fun = '';
            $class = '';
            if (isset($trace[1])) {
                $fun = $trace[1]['function'];
                if (isset($trace[1]['class'])) {
                    $class = $trace[1]['class'];
                }
            }

            $ex = explode(APP_ROOT_DIR, $file);
            $file = $ex[1];
            if (!$class || !$fun) {
                $message = "[{$file}:{$line}] ". $message;
            }else{
                $message = "[{$file}:{$line}]【{$class}:{$fun}】". $message;
            }
        }

        $di = Phalcon\Di::getDefault();
        $di->get('logger')->debug($message);
    }
}

function info($message)
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($trace) {
        $file = $trace[0]['file'];
        $line = $trace[0]['line']; # 在那一行调用了info方法
        $fun = '';
        $class = '';
        if (isset($trace[1])) {
            $fun =  $trace[1]['function'];
            if (isset($trace[1]['class'])) {
                $class = $trace[1]['class'];
            }
        }

        $ex = explode(APP_ROOT_DIR, $file);
        $file = $ex[1];
        #$message = "[{$file}:{$line}]【{$class}:{$fun}】". $message;
        if (!$class || !$fun) {
            $message = "[{$file}:{$line}] ". $message;
        }else{
            $message = "[{$file}:{$line}]【{$class}:{$fun}】". $message;
        }
    }

    $di = Phalcon\Di::getDefault();
    $di->get('logger')->info($message);

}

function warn($message = '')
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($trace) {
        $file = $trace[0]['file'];
        $line = $trace[0]['line']; # 在那一行调用了warn方法
        $fun = '';
        $class = '';
        if (isset($trace[1])) {
            $fun = $trace[1]['function'];
            if (isset($trace[1]['class'])) {
                $class = $trace[1]['class'];
            }
        }

        $ex = explode(APP_ROOT_DIR, $file);
        $file = $ex[1];
        #$message = "[{$file}:{$line}]【{$class}:{$fun}】". $message;
        if (!$class || !$fun) {
            $message = "[{$file}:{$line}] ". $message;
        }else{
            $message = "[{$file}:{$line}]【{$class}:{$fun}】". $message;
        }
    }

    $di = Phalcon\Di::getDefault();
    $di->get('logger')->warning($message);
}

function error($message = '')
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
    if ($trace) {
        $file = $trace[0]['file'];
        $line = $trace[0]['line']; # 在那一行调用了warn方法
        $fun = '';
        $class = '';
        if (isset($trace[1])) {
            $fun = $trace[1]['function'];
            if (isset($trace[1]['class'])) {
                $class = $trace[1]['class'];
            }
        }

        $ex = explode(APP_ROOT_DIR, $file);
        $file = $ex[1];
        $message = "[{$file}:{$line}]【{$class}:{$fun}】". $message."\n debug_backtrace:".json_encode($trace);
    }

    $di = Phalcon\Di::getDefault();
    $di['logger']->error($message);
}

function beginOfHour($time = null)
{
    if (!$time) {
        $time = time();
    }
    return strtotime(date('Y-m-d H:00:00', $time));
}

function endOfHour($time = null)
{
    if (!$time) {
        $time = time();
    }
    return strtotime(date('Y-m-d H:59:59', $time));
}

function beginOfDay($time = null)
{
    if (!$time) {
        $time = time();
    }
    return strtotime(date('Y-m-d 00:00:00', $time));
}

function endOfDay($time = null)
{
    if (!$time) {
        $time = time();
    }
    return strtotime(date('Y-m-d 23:59:59', $time));
}


function beginOfMonth($time = null)
{
    if (is_null($time)) {
        $time = time();
    }
    return strtotime(date('Y-m-01 00:00:00', $time));
}

function endOfMonth($time = null)
{
    if (is_null($time)) {
        $time = time();
    }

    return strtotime(date("Y-m-t 23:59:59", $time));
}

function beginOfYear($time){
    if(!$time){
        $time = time();
    }
    return strtotime(date('Y-01-01 00:00:00',$time));
}

function endOfYear($time){
    if(!$time){
        $time = time();
    }
    $year = date("Y", $time);
    $year += 1;
    return strtotime("{$year}-01-01 00:00:00") - 1;
}

function currentUrl(){
    return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function toYuan($price){
    return number_format($price/100,2);
}

function toFen($price){
    return $price*100;
}