<?php
namespace Apps\Admin;

class ConfigController extends BaseController{
	public function configAction(){
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$files = $this->uploadFiles();
			$data['key_file'] = $files['key_file'];
			$data['cert_file'] = $files['cert_file'];
			$data['ca_file'] = $files['ca_file'];
			$data['uid'] = $this->user['id'];
			$data['time'] = time();
			$data['token'] = md5($this->user['id']);
			$mo = new \Config();
			if($this->params('id')){

				$mo->findFirst(array(
					'conditions' => 'uid=:uid:',
					'bind'       => array(
						'uid' => $this->user['id']
					)
				));
				if($mo->save($data)){
					echo '更新成功';
				}else{
					echo '更新失败';
				}
			}else{
				if($mo->create($data)){
					echo '创建成功';
				}else{
					echo '创建失败';
				}
			}
		}else{
			$config = \Config::findFirst(array(
				'conditions' => 'uid=:uid:',
				'bind'       => array(
					'uid' => $this->user['id']
				)
			));


			if($config){
				$this->view->setVar('payconfig',$config);
			}


			$this->view->setVar('action','pay');
			$this->viewPick();
		}

	}
}