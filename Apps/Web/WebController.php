<?php
namespace Apps\Web;


use Apps\BaseController;

class WebController extends BaseController
{
	public $token;
	public $vid;
	public $userinfo;
	public $wechat;

	public function onConstruct(){
		$this->wechat = $this->WeChat();
		$this->wechat->init($this->config->wechat->appid,$this->config->wechat->secret,$this->config->wechat->mchid,$this->config->wechat->payKey);
	}


	public function indexAction(){
		$this->token = $this->params('token');
		$this->vid = $this->params('vid');
		$this->view->error = true;

		$this->view->metoken = uniqid();

		$this->session->set('metoken',$this->view->metoken);

		if(!$this->token || !$this->vid){
			$this->view->error_msg = '活动已经全部暂停';
			$this->viewPick();
			return;
		}

		$this->userinfo = \Users::findFirst(array(
			'conditions'=>'token=:token:',
			'bind'=>array(
				'token'=>$this->token
			)
		));

		if(!$this->userinfo){
			$this->view->error_msg = '系统出错';
			$this->viewPick();
			return;
		}

		#判断活动
		$this->voteInfo = $voteInfo = \Vote::findFirst(array(
			'conditions'=>' id=:id:',
			'bind'=>array(
				'id'=>$this->vid
			)
		));

		$lng = explode(',',$voteInfo->lt);
		$this->view->lat = str_replace(' ','',$lng[0]);
		$this->view->lng = str_replace(' ','',$lng[1]);

		if($voteInfo->d_status == 0){
			$this->view->error_msg = '活动系统审核中!请联系平台管理员!';
			$this->viewPick();
			return;
		}




		if(!$voteInfo){
			$this->view->error_msg = '活动不存在';
			$this->viewPick();
			return;
		}

		#判断活动状态

		if($this->voteInfo->status !=1){
			$this->view->error_msg = '活动已经暂停';
			$this->viewPick();
			return;
		}
		if($this->voteInfo->etime <= time()){
			$this->view->error_msg = '活动已经结束';
			$this->viewPick();
			return;
		}

		if($this->voteInfo->stime > time()){
			$this->view->error_msg = '活动还没开始';
			$this->viewPick();
			return;
		}

		#判断预算额度师傅超出

		$yusuanSum = \Vlog::sum(array(
			'conditions'=>'vid=:vid: and status=:status:',
			'bind'=>array(
				'vid'=>$this->vid,
				'status'=>1
			),
			'column'=>'price'
		))?:0;

		if($voteInfo->money <= $yusuanSum){
			$this->view->error_msg = '经过一轮疯抢,所有红包已经被抢完。活动已经结束';
			$this->viewPick();
			return;
		}

		$this->view->error = false;

		$this->auth();

		$this->view->setVar('qcode',$voteInfo->tup);
		$this->view->setVar('token',$this->token);

		$jsConfig= $this->wechat->getJsApiSign(currentUrl());
		$this->view->setVar('jsConfig',json_encode($jsConfig));

		$this->viewPick();

	}

	public function lotlngAction(){
		$token = $this->params('token');
		$userinfo = \Users::findFirst(array(
			'conditions'=>'token=:token:',
			'bind'=>array(
				'token'=>$token
			)
		));
		if($userinfo){
			$userinfo->lat = $this->params('latitude');
			$userinfo->lng = $this->params('longitude');

			if($userinfo->save()){
				echo 'ok';
			}else{
				echo 'fail';
			}
		}
	}

	public function linhongbaoAction(){
		$token = $this->params('token');
		$vid = $this->params('vid');
//		$metoken = $this->params('metoken');
//
//		$smetoken = $this->session->get('metoken');
//
//		if(!$metoken || $smetoken != $metoken){
//			renderJSON(-1,'对不起,非法访问');
//			return;
//		}


		$userinfo = \Users::findFirst(array(
			'conditions'=>'token=:token:',
			'bind'=>array(
				'token'=>$token
			)
		));

		if(!$userinfo){
			renderJSON(-1,'非法访问1');
			return;
		}

		if(!$userinfo->lat && !$userinfo->lng){
			renderJSON(-1,'无法获取你的位置');
			return;
		}

		$voteinfo = \Vote::findFirst(array(
			'conditions'=>'id=:id:',
			'bind'=>array(
				'id'=>$vid
			)
		));


		if(!$voteinfo){
			renderJSON(-1,'非法访问2');
			return;
		}

		if($voteinfo->d_status == 0){
			renderJSON(-1,'活动系统审核中!请联系平台管理员!');
			return;
		}

		$userLinqu = \Vlog::count(array(
			'conditions'=>'openid=:openid: and vid=:vid:',
			'bind'=>array(
				'openid'=>$userinfo->openid,
				'vid'=>$voteinfo->id,
			)
		))?:0;

		if($userLinqu >= $voteinfo->num){
			renderJSON(2,'您的领取次数已经用完!赶紧将此页分享给您的朋友吧!让他们也来领取!');
			return;
		}


		if($voteinfo->etime <= time()){
			renderJSON(2,'活动已经结束');
			return;
		}

		if($voteinfo->stime > time()){
			renderJSON(2,'活动还没开始');
			return;
		}


		$yusuanSum = \Vlog::sum(array(
			'conditions'=>'vid=:vid: and status=:status:',
			'bind'=>array(
				'vid'=>$voteinfo->id,
				'status'=>1
			),
			'column'=>'price'
		))?:0;

		if($voteinfo->money <= $yusuanSum){
			renderJSON(-1,'对不起,系统繁忙');
			return;
		}


		$price = mt_rand($voteinfo->smoeny,$voteinfo->emoney);
		$cert_file = APP_ROOT.'pem'.DIRECTORY_SEPARATOR.'cert.pem';
		$key_file = APP_ROOT.'pem'.DIRECTORY_SEPARATOR.'key.pem';



		$userData = array(
			'openid'=>$userinfo->openid,
			'vid'=>$voteinfo->id,
			'price'=>$price,
			'type'=>'S',
			'status'=>0,
			'msg'=>'发放中...',
			'time'=>time(),
			'lng'=>$userinfo->lat,
			'lot'=>$userinfo->lng,
		);

		$userModel = new \Vlog();
		if(!$userModel->create($userData)){
			error('红包发放记录初始化保存失败');
		}



		if($voteinfo->fanwei != 0 ){
			if($userinfo->lat && $userinfo->lng){
				$lotlnt = explode(',',$voteinfo->lt);
				$km = $this->distance($lotlnt['0'],$lotlnt['1'],$userinfo->lat,$userinfo->lng);
				if($km > $voteinfo->fanwei){
					$msg = '对不起,您超出商家红包的发放范围!您距离商家'.number_format($km,2).'千米,商家投放距离'.$voteinfo->fanwei.'千米,赶紧分享给您在范围内的好朋友来领取吧!';
					$userModel->msg= $msg;
					$userModel->save();
					renderJSON(2,$msg);
					return;
				}
			}else{
				$msg = '对不起,系统没有获取到您的位置!';
				$userModel->msg= $msg;
				$userModel->save();
				renderJSON(-1,'对不起,系统没有获取到您的位置!');
				return;
			}
		}




		$res = $this->wechat->sendMoneyRed($price,$cert_file,$key_file,$voteinfo->qname,$userinfo->j_openid,$voteinfo->wsing,$voteinfo->name);
//
//		if($res['status'] == 0){
//			$this->wechat->sendKfZ($openid,'发放中...');
//		}
		$userModel->msg= $res['msg'];
		$userModel->status= $res['status'];

		if(!$userModel->save()) {
			error ('发放记录收尾失败');
		}
		if($res['status']  == 0){
			renderJSON(0,'发放失败');
		}else{
			renderJSON(0,'发放成功,请注意查收!');
		}

	}

	public function qcodeAction(){
		$vid = $this->params('vid');
		if(!$vid){
			return;
		}

		#判断活动
		$voteInfo = \Vote::findFirst(array(
			'conditions'=>' id=:id:',
			'bind'=>array(
				'id'=>$vid
			)
		));

		$lng = explode(',',$voteInfo->lt);
		$this->view->lat = str_replace(' ','',$lng[0]);
		$this->view->lng = str_replace(' ','',$lng[1]);


		$this->view->tu = $voteInfo->tup;

		$this->viewPick();


	}

	public function auth ()
	{
		$userinfo = $this->WeChat ()->getUserInfo (currentUrl ());

		$data = array(
			'j_openid'      => $userinfo[ 'openid' ],
			'nickname'       => $userinfo[ 'nickname' ],
			'sex'            => $userinfo[ 'sex' ],
			'city'           => $userinfo[ 'city' ],
			'country'        => $userinfo[ 'country' ],
			'province'       => $userinfo[ 'province' ],
			'language'       => $userinfo[ 'language' ],
			'headimgurl'     => $userinfo[ 'headimgurl' ],
			'time'           => time ()
		);

		$user = \Users::findFirst(array(
			'conditions'=>'openid=:openid:',
			'bind'=>array(
				'openid'=>$this->userinfo->openid
			)
		));
		if(!$user->nickname){
			$user->save($data);
		}
	}

	function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
	{
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;
		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat/2)*sin($dlat/2)+cos($lat1)*cos($lat2)*sin($dlng/2)*sin($dlng/2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;
		return ($miles ? ($km * 0.621371192) : $km);
	}
}