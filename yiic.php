<?php

// change the following paths if necessary
ini_set('display_errors', 'off');
error_reporting(E_ALL & ~E_NOTICE);

//define('FF_ROOT', dirname(__FILE__));
//define('FF_LOG_DIR',FF_ROOT."/logs");

$yiic=dirname(__FILE__).'/lib/yii-1.1.15/framework/yiic.php';
//define('FF_LOG_DIR',FF_ROOT."/logs");
//$arr = array('127.0.0.1','::1','10.0.1.209');
$config=dirname(__FILE__).'/include/config/test.php';
/*if (in_array($_SERVER['SERVER_ADDR'], $arr)) {
	$config=dirname(__FILE__).'/include/config/test.php';
} else {
	$config=dirname(__FILE__).'/include/config/test.php';
}*/

require_once($yiic);