<?php
/**
 * Created by PhpStorm.
 * User: chenshuhao
 * Date: 16-4-28
 * Time: 上午11:47
 */

$router = new \Phalcon\Mvc\Router();


#后台
$router->add("/(admin)/(index)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Index',
	'action' => 'show',
	'params' => 1
));
$router->add("/(admin)/(add)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Index',
	'action' => 'add',
	'params' => 1
));

$router->add("/(admin)/(login)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Login',
	'action' => 'login',
	'params' => 1
));
$router->add("/(admin)/(loginout)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Login',
	'action' => 'loginout',
	'params' => 1
));
$router->add("/(admin)/(vlist)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Vote',
	'action' => 'vList',
	'params' => 1
));
$router->add("/(admin)/(vadd)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Vote',
	'action' => 'vadd',
	'params' => 1
));
$router->add("/(admin)/(vstatus)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Vote',
	'action' => 'voteStatus',
	'params' => 1
));
$router->add("/(admin)/(config)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Config',
	'action' => 'config',
	'params' => 1
));
$router->add("/(weixin)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Weixin',
	'action' => 'handler',
	'params' => 1
));
$router->add("/(admin)/(tiao)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Tiao',
	'action' => 'index',
	'params' => 1
));


$router->add("/(admin)/(data)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Data',
	'action' => 'index',
	'params' => 1
));
$router->add("/(admin)/(mapData)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Data',
	'action' => 'mapData',
	'params' => 1
));

$router->add("/(admin)/(changepassword)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Member',
	'action' => 'index',
	'params' => 1
));
$router->add("/(red)/(lin)", array(
	'namespace' => 'Apps\Web',
	'controller' => 'Web',
	'action' => 'index',
	'params' => 1
));
$router->add("/(red)/(lng)", array(
	'namespace' => 'Apps\Web',
	'controller' => 'Web',
	'action' => 'lotlng',
	'params' => 1
));
$router->add("/(red)/(linhongbao)", array(
	'namespace' => 'Apps\Web',
	'controller' => 'Web',
	'action' => 'linhongbao',
	'params' => 1
));
$router->add("/(red)/(qcode)", array(
	'namespace' => 'Apps\Web',
	'controller' => 'Web',
	'action' => 'qcode',
	'params' => 1
));

$router->add("/(admin)/(shenhe)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Index',
	'action' => 'votesList',
	'params' => 1
));
$router->add("/(admin)/(sheheok)", array(
	'namespace' => 'Apps\Admin',
	'controller' => 'Index',
	'action' => 'sheheok',
	'params' => 1
));

$router->notFound(array('controller' => 'error', 'action' => 'error404'));

return $router;
