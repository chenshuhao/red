<?php

return new \Phalcon\Config(array(
	'crypt_key'   => env ('object_crypt_key', md5 ('微信红包')),
	'database'    => array(
		'adapter'  => "Postgresql",
		'host'     => env ('object_db_host', 'localhost'),
		'port'     => env ('object_db_port', 5432),
		'username' => env ('object_db_username', 'red'),
		'password' => env ('object_db_password', ''),
		'dbname'   => env ('object_db', 'red'),
	),
	'application' => array(
		'controllersDir' => APP_ROOT . 'apps/',
		'modelsDir'      => APP_ROOT . 'models/',
		'tasksDir'       => APP_ROOT . '/Apps/Tasks',
		'cacheDir'       => APP_ROOT . 'cache/',
		'libraryDir'     => APP_ROOT . 'library/',
		'loggerDir'      => APP_ROOT . 'logs/',
		'viewsDir'       => APP_ROOT . 'tpl/',
		'viewsCacheDir'  => APP_ROOT . 'cache/tpl/',
		'cacheDir'       => APP_ROOT . 'cache/file/',
	),
	'time' =>array(
		'cacheTime'=> 6000
	),
	'ip' =>array(
		'ip'=> '192.168.1.102'
	),
	'wechat'=>array(
//		'appid'=>'wx990feb3ba8f04269', //wechat appi
		'appid'=>'wx991e0456593ce3f3', //wechat appi
//		'secret'=>'d807ae95840d31e15e362f0d5d4a8cba', //secret
		'secret'=>'cb7d7b26f17d0c3b5cda5bc4bb0ffc21', //secret
		'mchid'=>'1347695101', //secret
		'payKey'=>'qwertyuiopasdfghjklzxcvbnmqwerty', //secret
		'token'=>'yqg123', //secret
	)
//    'beanstalk' => array(
//        'host' => '127.0.0.1',
//        'port' => 11200,
//        'pool_size' => env('beanstalk_pool_size', 22),
//        'tubes' => array('mail' => 10, 'stat' => 3, 'common' => 8, 'default' => 1) #pool_size多余的tube是default = (pool_size-mail-stat)
//    )
));
