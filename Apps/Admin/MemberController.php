<?php
namespace Apps\Admin;

class MemberController extends BaseController
{
	public function indexAction ()
	{
		if($this->request->isPost()){
			$old= $this->params('oldmima');
			$password= $this->params('password');


			$userinfo = \Admin::findFirst(array(
				'conditions'=>'username=:username: and password=:password:',
				'bind'=>array(
					'username'=>$this->user['username'],
					'password'=>md5($old)
				)
			));

			if(!$userinfo){
				$this->response->redirect("/admin/tiao?title=原密码错误&url=/admin/changepassword")->sendHeaders();
				return;
			}

			$userinfo->password = md5($password);

			if($userinfo->save()){
				$this->response->redirect("/admin/tiao?title=修改成功&url=/admin/changepassword")->sendHeaders();
				return;
			}else{
				$this->response->redirect("/admin/tiao?title=修改失败&url=/admin/changepassword")->sendHeaders();
				return;
			}


		}else{

			$this->viewPick();
		}
	}
}