<?php
namespace Apps\Admin;

use Apps\BaseController;

class LoginController extends BaseController{
	public function loginAction(){
		#login

		if($this->request->isPost() == true){
			$userinfo = \Admin::findFirst(array(
				'conditions' => 'username=:username: and password=:password: and status=:status:',
				'bind'       => array(
					'username' => $this->params('username'),
					'password' => md5($this->params('password')),
					'status' => 1,
				)
			));

			if($userinfo->etime && $userinfo->etime < time()){
				echo '<script>alert("账号有效期已过!");history.go(-1);</script>';
				return;
			}



			if($userinfo){
				if($userinfo->type == 'U'){
					$this->session->set('userAuth',array(
						'id'=>$userinfo->id,
						'username'=>$userinfo->username,
						'type'=>'user'
					));

					$this->response->redirect("admin/index");
				}elseif('A' == $userinfo->type){
					$this->session->set('adminAuth',array(
						'id'=>$userinfo->id,
						'username'=>$userinfo->username,
						'type'=>'admin'
					));
					$this->response->redirect("admin/index");
				}else{
					echo '用户名密码错误';
					die;
				}
			}else{
				echo '用户名密码错误';
				die;
			}

		}else{

			$this->viewPick();
		}
	}

	public function loginoutAction(){
		$this->session->set('adminAuth',null);
		$this->session->set('userAuth',null);
		$this->response->redirect("admin/login");

	}
}