<?php
/**
 * This is the configuration for web application dev
 *
 */
$_main = require dirname(__FILE__) . '/main.php';
unset($_main['components']['cache']);
unset($_main['components']['db']);
return CMap::mergeArray($_main, array(
    'import' => array(
        'ext.yiidebugtb.*'
    ),

    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            'generatorPaths' => array(
                'application.gii',
                'ext.YiiMongoDbSuite.gii'
            ),
            'ipFilters' => array('*.*.*.*'),
        )
    ),

    'components' => array(
//        'cache' => array(
//            'class' => 'FMemCache',
//            'keyPrefix' => 'pinge.focus.cn',
//            'masterServers' => $_SERVER['JIAJING_MEMCACHED_SERVERS'],
//            'slaveServers' => $_SERVER['JIAJING_MEMCACHED_MIRROR_SERVERS'],
//        ),
        'mongodb' => array(
            'class'            => 'EMongoDB',
            'connectionString' => 'mongodb://zc:test@182.92.81.13:27017',
            'dbName'           => 'mydb',
            'fsyncFlag'        => true,
            'safeFlag'         => true,
            'useCursor'        => false
        ),
        'db' => array(
            'class' => 'FDbConnection',
            'connectionString' => "mysql:host=localhost;dbname=huala;port=3306",
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Hello0617',
            'charset' => 'UTF8',
            'tablePrefix' => 'h_',
            'enableParamLogging' => YII_DEBUG,
            //'schemaCacheID' => 'cache',
            'schemaCachingDuration' => FF_DEBUG ? 0 : 1800,
            'slaves' => array(
                array(
                    'connectionString' => "mysql:host=localhost;dbname=huala;port=3306",
                    'emulatePrepare' => true,
                    'username' => 'root',
                    'password' => 'Hello0617',
                    'charset' => 'UTF8',
                    'tablePrefix' => 'h_',
                    'enableParamLogging' => YII_DEBUG,
                    'schemaCacheID' => 'cache',
                    'schemaCachingDuration' => 0,
                )
            )
        ),
        'db_zhongqiOA' => array(
            'class' => 'FDbConnection',
            'connectionString' => "mysql:host=localhost;dbname=oa;port=3306",
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Hello0617',
            'charset' => 'UTF8',
            'tablePrefix' => 'OA_',
            'enableParamLogging' => YII_DEBUG,
            //'schemaCacheID' => 'cache',
            'schemaCachingDuration' => FF_DEBUG ? 0 : 1800,
            'slaves' => array(
                array(
                    'connectionString' => "mysql:host=localhost;dbname=oa;port=3306",
                    'emulatePrepare' => true,
                    'username' => 'root',
                    'password' => 'Hello0617',
                    'charset' => 'UTF8',
                    'tablePrefix' => 'OA_',
                    'enableParamLogging' => YII_DEBUG,
                    'schemaCacheID' => 'cache',
                    'schemaCachingDuration' => 0,
                )
            )
        ),
        'mail' => array(
            'class' => 'ext.yiimail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false,
            'transportType'=>'smtp',
            'transportOptions' => array(
                'host' => 'smtpcom.263xmail.com',
                'port' => '25',
                'username' => 'zhangchao@aladdin-holdings.com',
                'password' => '123.com'
            )
        ),

        'clientScript' => array(
            'class' => 'CClientScript',
        ),
    ),

    'params' => array(
        'adminEmail' => 'xxx@xxx.com',
        'app_id' => 3,
        'app_secret' => 'k0jh4Fw0rz1JkIgRQ~xwabRo5c7PRGf2',
        'name'		=> 	'JIAJINGINFO',
        'crypt_key'	=>	'J2X8jExm1',
        'wapToPc'	=>	'wapToPc',
        'focus_appid'=>1021,
        'focus_key'=>'123456',
        'focus_url'=>'http://passportcs-test.apps.sohuno.com/p/user/nolog',
    ),

));
