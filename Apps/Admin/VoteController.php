<?php

namespace Apps\Admin;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class VoteController extends BaseController{
	public function vListAction(){

		$userlist = \Vote::find(array(
			'conditions' => 'uid=:uid:',
			'bind'       => array(
				'uid' => $this->user['id']
			)
		));
		$currentPage = $this->params('page')?:0;
		$paginator = new PaginatorModel(
			array(
				"data"  => $userlist,
				"limit" => 15,
				"page"  => $currentPage
			)
		);

		$page = $paginator->getPaginate ();

		foreach ( $page->items as $item ) {
			$data[] = array(
				'id'=>$item->id,
				'name'    => $item->name,
				'num'    => $item->num,
				'fanwei'    => $item->fanwei,
				'mfanwei'    => toYuan($item->smoeny) .' - '. toYuan($item->emoney),
				'status'    => $item->status,
				'sd'    => $item->sd.' - '.$item->ed,
				'money'    => toYuan($item->money),
				'stime'    => date('Y-m-d H:i:s',$item->stime).'-'.date('Y-m-d H:i:s',$item->etime),
				'time'    => date('Y-m-d H:i:s',$item->time),
				'lingrenshu'    => $this->renshu($item->id),
				'lingjine'    => toYuan($this->hejiprice($item->id)),
			);
		}


		$listData = array(
			'data'        => $data,
			'current'     => $page->current,
			'next'        => $page->next,
			'total_items' => $page->total_items,
			'total_pages' => $page->total_pages
		);

		$this->view->setVar('list',$listData);

		$this->viewPick();



		$this->view->setVar('action','vlist');

		$this->viewPick();
	}

	public function vaddAction(){
		if($this->params('voteid')){
			$voteInfo = \Vote::findFirst(array(
				'conditions' => 'uid=:uid: and id=:id:',
				'bind'       => array(
					'uid' => $this->user['id'],
					'id' => $this->params('voteid')
				)
			));

			if(!$voteInfo){
				$this->response->redirect("/admin/tiao?title=活动不存在&url=/admin/vlist")->sendHeaders();
			}else{

				$voteInfo->stime = date('Y-m-d H:i:s',$voteInfo->stime);
				$voteInfo->etime = date('Y-m-d H:i:s',$voteInfo->etime);
				$voteInfo->smoeny = toYuan($voteInfo->smoeny);
				$voteInfo->emoney = toYuan($voteInfo->emoney);
				$voteInfo->money = toYuan($voteInfo->money);

				$this->view->setVar('voteinfo',$voteInfo);
			}
		}
		if($this->request->isPost()){
			$files = $this->uploadFiles();

			$data = $this->request->getPost();
			$data['stime'] = strtotime($data['stime']);
			$data['etime'] = strtotime($data['etime']);
			$data['money'] = toFen(str_replace(',','',$data['money']));
			$data['smoeny'] = toFen($data['smoeny']);
			$data['emoney'] = toFen($data['emoney']);
			$data['sd'] = $data['sd']?:0;
			$data['ed'] = $data['ed']?:0;
			$data['uid'] = $this->user['id'];
			$data['time'] = time();
			$data['status'] = 0;
			$data['d_status'] = 0;

			if(is_array($files)){
				if(count(explode('.', $files['tup'])) == 2){
					$data['tup'] = $files['tup'];
				}
			}

			if(isset($data['id'])){
				$vinfo = \Vote::findFirst(array(
					'conditions' => 'id=:id:',
					'bind'       => array(
						'id' => $data['id']
					)
				));
				if(!$data['tup']){
					$data['tup'] = $vinfo->tup;
				}
				if($vinfo->save($data)){
					$this->response->redirect("/admin/tiao?title=更新成功&url=/admin/vlist")->sendHeaders();
				}else{
					$this->response->redirect("/admin/tiao?title=更新失败&url=/admin/vlist")->sendHeaders();
				}
			}else{
				if(!$data['tup']){
					$data['tup'] = 'images/default/hb_1.jpg';
				}
				if((new \Vote())->create($data)){
					$this->response->redirect("/admin/tiao?title=创建成功&url=/admin/vlist")->sendHeaders();
				}else{
					$this->response->redirect("/admin/tiao?title=创建失败&url=/admin/vlist")->sendHeaders();
				}
			}

		}else{
			$this->view->setVar('action','vadd');

			$this->viewPick();
		}
	}

	public function voteStatusAction(){

		$voteInfo = \Vote::findFirst(array(
			'conditions' => 'uid=:uid: and id=:id:',
			'bind'       => array(
				'uid' => $this->user['id'],
				'id' => $this->params('id')
			)
		));

		if($voteInfo){
			$voteInfo->status = $voteInfo->status?0:1;
			if($voteInfo -> save()){
				renderJSON(0,'更新成功');
			}else{
				renderJSON(-1,'更改失败');
			}
		}else{
			renderJSON(-1,'活动不存在');
		}
	}

	public function renshu($vid){
		$num = \Vlog::count(array(
			'conditions' => 'vid=:vid: and status=:status: ',
			'bind'       => array(
				'vid' => $vid,
				'status' => 1
			),
		    "group" => "openid",
		));

		return is_numeric($num->count())?$num->count():0;
	}


	public function hejiprice($vid){
		$userlingquSUM = \Vlog::sum (
								array(
									"column"     => "price",
									'conditions' => 'vid=:vid: and status=:status:',
									'bind'       => array(
										'vid' => $vid,
										'status' => 1
									)
								)
							);

		return $userlingquSUM?:0;
	}
}
