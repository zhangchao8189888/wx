<?php
header("Content-type: text/html; charset=utf-8");
header('Expires: Thu, 01 Jan 1970 00:00:01 GMT');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Apache rewrite 修正
ini_set('date.timezone','Asia/Shanghai');
ini_set("safe_mode",true);
define('FF_ROOT', dirname(__FILE__));
if (isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"]=="on")
{
    define('FF_DOMAIN', 'https://' . $_SERVER['HTTP_HOST']);
} else {
    define('FF_DOMAIN', 'http://' . 'zc.zhongqijiye.com/wx');
}


define('FF_CURRENT_URL', current_url());
define('FF_CURRENT_URL_NO_QUERY', current_url(false));

define('FF_STAMP_TIME', time());
define('FF_DATE_TIME', date('Y-m-d H:i:s', FF_STAMP_TIME));

define('FF_STATIC_BASE_URL', FF_DOMAIN."/upload");


ini_set('memory_limit', '128M');


$arr = array('127.0.0.1','::1','182.92.81.13');

if (in_array($_SERVER['SERVER_ADDR'], $arr)) {

    ini_set('display_errors', 'on');
    error_reporting(E_ALL & ~E_NOTICE);
    define('YII_DEBUG', false);
    define('YII_TRACE_LEVEL', 3);
    $GLOBALS['debug'] =false;
    $config = FF_ROOT . '/include/config/test.php';
} else {


    ini_set('display_errors', 'off');
    define('YII_DEBUG', false);
    $GLOBALS['debug'] =false;

    $config = FF_ROOT . '/include/config/main.php';

}

// change the following paths if necessary
$yii=dirname(__FILE__).'/./lib/yii-1.1.15/framework/yii.php';

require_once($yii);

Yii::createWebApplication($config)->run();
function current_url($flag = true){
    $curl = '';
    if(isset($_SERVER['SCRIPT_URL'])) {
        $curl = $_SERVER['SCRIPT_URL'];
    } elseif (isset($_SERVER['REDIRECT_URL'])) {
        $curl = $_SERVER['REDIRECT_URL'];
    }
    if($curl && $_SERVER['QUERY_STRING'] && $flag) {
        $curl .= '?' . $_SERVER['QUERY_STRING'];
    }
    return ($curl ? $curl : $_SERVER['REQUEST_URI']);
}