<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'huala',
    'language' => 'zh_cn',
    'charset' => 'UTF-8',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'ext.yiimail.YiiMailMessage',
        'ext.YiiMongoDbSuite.*',
        'application.extensions.PHPExcel.*',
    ),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
        'user' => array(
            'class' => 'FUser',
        ),

        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'showScriptName'=>false,    // 这一步是将代码里链接的index.php隐藏掉。
            'urlFormat'=>'path',
            'rules'=>array(
                //'<module:\w+>/<controller:\w+>/<action:\w+>/p<page:\d+>' => '<module>/<controller>/<action>',
                //'<controller:\w+>/<action:\w+>/<id:\d+>' 	=> 	'<controller>/<action>',
                '<controller:\w+>/p<page:\d+>' 			    => 	'<controller>',
                //'<controller:\w+>/<action:\w+>/p<page:\d+>' => '<controller>/<action>',

                '<controller:e>'							=> 	'employ/employList',
                '<controller:e>/<eid:\d+>'					=> 	'employ/getEmployInfo',
                '<controller:emp>'	            => 	'employ/',
                '<controller:emp>/<action:getEmployByIds>'	=> 	'employ/getEmployByIds',
                '<controller:emp>/<action:getEmployByIds>/<ids:\d+>'	=> 	'employ/getEmployByIds',

                //其他
                '<controller:home>'							=>	'site/index',
                '<controller:index>'						=>	'site/index',
                '<controller:login>'						=>	'login/login',
                '<controller:register>'						=>	'login/register',
                '<controller:help>'							=>	'site/help',
                '<controller:404>'							=>	'site/error',
                '<controller:msg>'							=>	'site/msg',
                '<controller:app>'							=>	'site/app',
                '<controller:plans>'                          =>  'site/plans',
                '<controller:downloadapp>'					=>	'mobile/downloadapp',

            ),
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
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'info,error,warning,trace',
                ),
                array(
                    'class'=>'CWebLogRoute',
                    'levels'=>'trace',//提示的级别
                    'categories'=>'system.db.*',
//还可以通过 'showInFireBug'=>true, //显示在Firebug里
//显示在Firebug里我们就可以调整提示级别，来显示更多
//例如'levels'=>'trace,info,error,warning,xdebug',
                ),
            )
        ),
        'clientScript' => array(
            'class' => 'FClientScript',
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);


/**
 * ./configure --prefix=/usr/local/nginx --sbin-path=/usr/local/nginx/sbin/nginx --conf-path=/usr/local/nginx/nginx.conf --pid-path=/usr/local/nginx/nginx.pid --user=nginx --group=nginx --with-http_ssl_module --with-http_flv_module --with-http_mp4_module --with-http_stub_status_module --with-http_gzip_static_module --http-client-body-temp-path=/var/tmp/nginx/client/ --http-proxy-temp-path=/var/tmp/nginx/proxy/ --http-fastcgi-temp-path=/var/tmp/nginx/fcgi/ --http-uwsgi-temp-path=/var/tmp/nginx/uwsgi --http-scgi-temp-path=/var/tmp/nginx/scgi --with-pcre=/usr/local/src/pcre-8.39 --with-zlib=/usr/local/src/zlib-1.2.11 --with-openssl=/usr/local/src/openssl-1.1.0b
 */