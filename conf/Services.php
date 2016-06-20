<?php
use Phalcon\DI\FactoryDefault;
use Phalcon\DI\FactoryDefault\CLI;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Db\Adapter\Pdo\Postgresql as DbAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Logger\Adapter\File as LoggerAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;
use Phalcon\Http\Response as Response;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Http\Response\Cookies;
use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Session\Adapter\Files as Session;


$di = new FactoryDefault();

$config = require "../conf/Config.php";

$di->setShared ('config', $config);
#Setting up the view component
$di->set ('view', function () use ( $config ) {

	$view = new View();
	$view->setViewsDir ($config->application->viewsDir);
	$view->registerEngines (array(
		'.volt'  => function ( $view, $di ) use ( $config ) {

			$volt = new VoltEngine($view, $di);
			$volt->setOptions (array(
				'compiledPath'      => $config->application->viewsCacheDir,
				'compiledSeparator' => '_'
			));
//            $compiler = $volt->getCompiler();
//            //将utils函数注入
//            $compiler->addExtension(new \PhpFunctionExtension());
			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));

	return $view;
}, TRUE);

#注入日志
$di->set ('logger', function () use ( $config ) {
	$dir = $config->application->loggerDir;
	if ( !file_exists ($dir) ) {
		mkdir ($dir, 0700, TRUE);
		chmod ($dir, 0700);
	}

	$file = "{$dir}log_" . date ('Ymd', time ()) . ".log";
	if ( !isProduction () ) {
		$file = "{$dir}log.log";
	}
	if ( !file_exists ($file) ) {
		$fp = fopen ($file, "w");
		chmod ($file, 0777);
		fclose ($fp);
	}

	$formatter = new LineFormatter('[%date%][%type%]%message%', 'Y-m-d H:i:s');
	$logger = new LoggerAdapter($file);
	$logger->setFormatter ($formatter);

	return $logger;
});

#异步日志
$di->set ('async_logger', function () use ( $config ) {
	$dir = $config->application->loggerDir;
	if ( !file_exists ($dir) ) {
		mkdir ($dir, 0700, TRUE);
		chmod ($dir, 0700);
	}

	$file = "{$dir}async_" . date ('Ymd', time ()) . ".log";
	if ( !isProduction () ) {
		$file = "{$dir}async.log";
	}
	if ( !file_exists ($file) ) {
		$fp = fopen ($file, "w");
		chmod ($file, 0777);
		fclose ($fp);
	}

	$formatter = new LineFormatter('[%date%][%type%]%message%', 'Y-m-d H:i:s');
	$logger = new LoggerAdapter($file);
	$logger->setFormatter ($formatter);

	return $logger;
});

#Database connection is created based in the parameters defined in the configuration file
$di->setShared ('db', function () use ( $config ) {

	$database = array( 'host'     => $config->database->host,
	                   'port'     => $config->database->port,
	                   'username' => $config->database->username,
	                   'password' => $config->database->password,
	                   'dbname'   => $config->database->dbname );

	$connection = new DbAdapter($database);

	$eventsManager = new Phalcon\Events\Manager();
	#Listen all the database events
	$eventsManager->attach ('db', function ( $event, $connection ) use ( $config ) {

		$dir = $config->application->loggerDir;
		if ( !file_exists ($dir) ) {
			mkdir ($dir, 0700, TRUE);
			chmod ($dir, 0700);
		}

		$file = "{$dir}db_" . date ('Ymd', time ()) . ".log";
		if ( !isProduction () ) {
			$file = "{$dir}db.log";
		}
		if ( !file_exists ($file) ) {
			$fp = fopen ($file, "w");
			chmod ($file, 0777);
			fclose ($fp);
		}

		if ( !isProduction () ) {
			$logger = new LoggerAdapter($file);
			if ( $event->getType () == 'beforeQuery' ) {
				$logger->log ($connection->getSQLStatement (), \Phalcon\Logger::DEBUG);
			}
		}
	});

	#Assign the eventsManager to the db adapter instance
	$connection->setEventsManager ($eventsManager);

	return $connection;
});

#设置路由
$di->set ("router", function () {
	$router = require 'Router.php';

	return $router;
});


$di->setShared ('session', function () {
	$session = new Session();
	$session->start ();

	return $session;
});


$di->set ('cookies', function () use ( $di ) {
	$cookies = new Cookies();
	$cookies->useEncryption ($di->get ('crypt'));

	return $cookies;
});

$di->setShared ('crypt', function () use ( $config ) {
	$crypt = new Phalcon\Crypt();
	$crypt->setKey ($config->crypt_key);

	return $crypt;
});


$di->setShared ('cache', function () use ( $config ) {
	return new BackFile(
		new FrontData(
			array(
				"lifetime" => $config->time->cacheTime
			)),
		array(
			"cacheDir" => $config->application->cacheDir
		)
	);
});


$di->set('response',function(){
	return new \Phalcon\Http\Response();
});
