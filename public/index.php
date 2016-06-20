<?php
use Phalcon\Mvc\Application;
try{
	require '../vendor/autoload.php';
	require '../common/func.php';
	require '../common/def.php';
	require '../conf/Services.php';//加载服务
	require '../conf/Loader.php';//注册目录

	$application = new Application($di);
	echo $application->handle()->getContent();
}catch (\Exception $e){
	renderJSON(-1,$e->getMessage(),array(
		'line'=>$e->getLine(),
		'file'=>$e->getFile()
	));
}