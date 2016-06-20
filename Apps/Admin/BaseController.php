<?php

namespace Apps\Admin;

class BaseController extends \Apps\BaseController
{
	public $user = null;
	public function onConstruct(){
		#权限验证
		$adminAuth = $this->session->get('adminAuth')?:null;
		$userAuth = $this->session->get('userAuth')?:null;

		if($adminAuth){
			$this->user = array(
				'type'=>'admin',
				'id'=>$adminAuth['id'],
				'username'=>$adminAuth['username']
			);

			$this->view->setVar('UT','admin');
		}elseif($userAuth){
			$this->user = array(
				'type'=>'user',
				'id'=>$userAuth['id'],
				'username'=>$userAuth['username']
			);
			$this->view->setVar('UT','user');

		}else{
			#no auth

			header('loaction:/admin/login');
			die;
		}

		$this->view->setVar('username',$this->user['username']);
	}
}