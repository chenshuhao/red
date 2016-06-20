<?php
namespace Apps;


use library\WeChat\Wechat;

class BaseController extends \Phalcon\Mvc\Controller
{

	public function __call ( $name, $arguments )
	{
		if(method_exists($this->di,$name)){
			return $this->di[$name];
		}else{
			if(is_dir(APP_ROOT.'library'.DIRECTORY_SEPARATOR.$name)){
				$className = 'library\\'.$name.'\\'.$name;
				return new $className();
			}

			if(file_exists(APP_ROOT.'models'.DIRECTORY_SEPARATOR.$name.'.php')){
				$className = '\\'.$name;
				return new $className();
			}
		}
	}

	# 使用默认模板
	public function viewPick ()
	{

		$dispatcher = $this->dispatcher;
		$namespace = \Phalcon\Text::uncamelize ($dispatcher->getNamespaceName ());
		$controller_name = \Phalcon\Text::uncamelize ($dispatcher->getControllerName ());
		$action_name = \Phalcon\Text::uncamelize ($dispatcher->getActionName ());

		$view = $controller_name .DIRECTORY_SEPARATOR . $action_name;

		debug ("view: {$view}  params:" . json_encode ($this->params (), JSON_UNESCAPED_UNICODE));

		$view_prefix = APP_ROOT . 'tpl' . DIRECTORY_SEPARATOR . $view;
		if ( file_exists ($view_prefix . '.volt') || file_exists ($view_prefix . '.phtml') ) {
			$this->view->setRenderLevel (\Phalcon\Mvc\View::LEVEL_LAYOUT);
			$this->view->pick ($view);
		} else {
			renderJSON ('模板不存在',$view);
		}
	}

	#获取求情数据
	public function params ( $param = NULL )
	{
		if ( $param == NULL ) {
			return $_REQUEST;
		}

		if ( $value = $this->request->get ($param) ) {
			return $value;
		}

		$input_string = file_get_contents ('php://input');
		$input_data = json_decode ($input_string, 1);

		if ( is_array ($input_data) ) {
			if ( isset( $input_data[ $param ] ) ) {
				return $input_data[ $param ];
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}


	public function uploadFiles()
	{
		if ($this->request->hasFiles()) {
			foreach ($this->request->getUploadedFiles() as $file) {
				$mime = explode('/', $file->getRealType());

				$filePath = 'file/' . time().uniqid().$file->getName();//.'.'.$mime[1];

				$file->moveTo($filePath);
				$arr[$file->getKey()] = $filePath;
			}
		}

		return $arr?:false;
	}

	#提交异步任务
	public function asyncPut($data, $tube = null)
	{
		$queue = new \Phalcon\Queue\Beanstalk(
			array(
				'host' => '127.0.0.1',
				'port' => '11300'
			)
		);
		if ($tube) {
			$queue->watch($tube);
		}
		if ($queue->put($data)) {
			return true;
		}
	}

}