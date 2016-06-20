<?php
namespace Apps\Admin;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class DataController extends BaseController{
	public function indexAction(){
		$page = $this->params('page');
		$vid = $this->params('vid');

		$devlist = \Vlog::find (array(
			'conditions'=>'vid=:vid:',
			'bind'=>array(
				'vid'=>$vid?:0
			)
		));


		$paginator = new PaginatorModel(
			array(
				"data"  => $devlist,
				"limit" => 20,
				"page"  => $page
			)
		);
		$paginator = $paginator->getPaginate ();

		foreach ( $paginator->items as $key => $item ) {
			$data[] = array(
				'id'        => $item->id,
				'openid'        => $this->getUserinfo($item->openid),
				'vid'        => $item->vid,
				'price'        => toYuan($item->price),
				'status'        => $item->status,
				'msg'        => $item->msg,
				'time'        => date('Y-m-d H:i:s',$item->time),
			);
		}

		$this->view->setVar('next',$paginator->next);
		$this->view->setVar('pre',$paginator->before);
		$this->view->setVar('last',$paginator->last);

		$this->view->setVar ('list', $data);

		$this->viewPick();
	}

	public function getUserinfo($opneid){
		$userinfo = \Users::findFirst(array(
			'conditions'=>'openid=:openid:',
			'bind'=>array(
				'openid'=>$opneid
			)
		));

		return $userinfo;
	}

	public function mapDataAction(){
		$vid = $this->params('vid');

		$vinfo = \Vote::findFirst(array(
			'conditions'=>'id=:id:',
			'bind'=>array(
				'id'=>$vid
			)
		));

		$this->view->setVar('vinfo',$vinfo);

		$usersList = 'Users';
		$vlogList = 'Vlog';
		$phql = "SELECT * FROM {$usersList} as su , {$vlogList} as dl WHERE dl.status=1 AND dl.vid={$vid} AND dl.openid=su.openid ORDER by dl.time desc";
		$list = $this->modelsManager->executeQuery($phql);

//		var_dump($list);
//		die;


		$ShopsUsers = 'users';
		$ShopsDrives = 'vlog';
		foreach ($list as $item) {

			$data[] = array(
				'id' => $item->$ShopsUsers->id,
				'nickname' => $item->$ShopsUsers->nickname,
				'lat' => $item->$ShopsUsers->lat,
				'lng' => $item->$ShopsUsers->lng,
				'headimgurl' => $item->$ShopsUsers->headimgurl,
				'price' => $item->$ShopsDrives->price,
				'msg' => $item->$ShopsDrives->msg,
				'time' => date('Y-m-d H:i:s', $item->$ShopsDrives->time),
				'status' => $item->$ShopsDrives->status
			);
		}

		$this->view->setVar('list',json_encode($data));

		$this->viewPick();
	}
}