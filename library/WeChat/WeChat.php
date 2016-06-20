<?php
namespace library\WeChat;


use library\WeChat\AccessToken\AccessToken;
use library\WeChat\Handler\Handler;
use library\WeChat\JsSdk\JsTicket;
use library\WeChat\JsSdk\Signature;
use library\WeChat\Pay\Pay;
use library\WeChat\Shake\Shake;

class WeChat extends \Apps\BaseController
{
	public function init($appid,$scret,$mchid,$payKey){
		Result::$appid = $appid;
		Result::$secret = $scret;
		Result::$mchid = $mchid;
		Result::$payKey = $payKey;
		Result::$accessToken = (new AccessToken())->AccessToken();
		Result::$JsApiTicket = (new JsTicket())->Ticket();
	}

//	public function acc(){
//		var_dump(Result::$accessToken);
//	}
	
	public function getUserInfo ($redirectUrl)
	{
		$UInfo = new Auth\UserInfo();
		if ( !$_GET[ 'code' ] ) $UInfo->redirect (Result::$appid,$redirectUrl);
		return $UInfo->authAccessToken($_GET['code'])->codeGetUserInfo();
	}

	public function getJsApiSign($url,$jsApiList = null,$debug=false){
		$wxConfig= array(
			'appId'=>Result::$appid,
			'timestamp'=>time(),
			'nonceStr'=>hex(),
			'debug' =>$debug
		);
		$wxConfig['signature'] = Signature::Sign($wxConfig['nonceStr'],$wxConfig['timestamp'],$url);

		if($jsApiList == null){
			$wxConfig['jsApiList'] = array(
				'onMenuShareTimeline',
				'onMenuShareAppMessage',
				'onMenuShareQQ',
				'onMenuShareWeibo',
				'onMenuShareQZone',
				'startRecord',
				'stopRecord',
				'onVoiceRecordEnd',
				'playVoice',
				'pauseVoice',
				'stopVoice',
				'onVoicePlayEnd',
				'uploadVoice',
				'downloadVoice',
				'chooseImage',
				'previewImage',
				'uploadImage',
				'downloadImage',
				'translateVoice',
				'getNetworkType',
				'openLocation',
				'getLocation',
				'hideOptionMenu',
				'showOptionMenu',
				'hideMenuItems',
				'showMenuItems',
				'hideAllNonBaseMenuItem',
				'showAllNonBaseMenuItem',
				'scanQRCode',
				'closeWindow',
				'chooseWXPay',
				'openProductSpecificView',
				'addCard',
				'chooseCard',
				'openCard',
			);
		}

		return $wxConfig;
	}

	public function getShakeRoundInfo($ticket){
		$shake = new Shake();
		if($ret = $shake->getShakeUserInfo($ticket)){
			return array(
				'openid'=>$ret['data']['openid'],
				'major'=>$ret['data']['beacon_info']['major'],
				'minor'=>$ret['data']['beacon_info']['minor'],
				'uuid'=>$ret['data']['beacon_info']['uuid'],
			);
		}
	}

	public function payTiXian($openid,$price){
		$pay = new Pay();

		return $pay->outPay($openid,$price);
	}

	public function getUserinfoByopenid($openid){
		return (new \library\WeChat\Auth\UserInfo())->getUserInfoByOpenid($openid);
	}

	public function sendKeFuMsg($touser,$formuser,$text){
		echo '<xml>
					<ToUserName><![CDATA['.$touser.']]></ToUserName>
					<FromUserName><![CDATA['.$formuser.']]></FromUserName>
					<CreateTime>'.time().'</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA['.$text.']]></Content>
				</xml>';
		die;
	}

	#发送客服消息
	public function sendKfZ($openid,$text){
		$data  = '{
		    "touser":"'.$openid.'",
		    "msgtype":"text",
		    "text":
		    {
		         "content":"'.$text.'"
		    }
		}';

		$rep = \Httpful\Request::post (
			sprintf (
				'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s',
				\library\WeChat\Result::$accessToken
			))
			->expectsJson ()
			->withStrictSSL ()
			->body($data)
			->send ();

		Handler::Response ($rep);
	}

	#发送客服消息图片
	public function sendKfZT($openid,$mid){
		$data  = '{
		    "touser":"'.$openid.'",
		    "msgtype":"image",
		    "image":
		    {
		      "media_id":"'.$mid.'"
		    }
		}';

		$rep = \Httpful\Request::post (
			sprintf (
				'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s',
				\library\WeChat\Result::$accessToken
			))
			->expectsJson ()
			->withStrictSSL ()
			->body($data)
			->send ();

		Handler::Response ($rep);
	}
	#发红包
	public function sendMoneyRed($price,$cert_file,$key_file,$send_name,$openid,$wishing,$act_name){
		$pay = new Pay();

		return $pay->sendRed($price,$cert_file,$key_file,$send_name,$openid,$wishing,$act_name);
	}

	#换取二维码ticket
	public function getQRTicket($tId){

		$data  = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_str": '.$tId.'}}}';

		$rep = \Httpful\Request::post (
			sprintf (
				'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s',
				\library\WeChat\Result::$accessToken
			))
			->expectsJson ()
			->withStrictSSL ()
			->body($data)
			->send ();

		return Handler::Response($rep);
	}
	#上传素材
	public function yongjiusucai($file )
	{

//        $data =  array(
//            'media'=> ROOT_DIR.DIRECTORY_SEPARATOR.'test.jpg'
//        );

		$rep = \Httpful\Request::post(sprintf('https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=image',Result::$accessToken))
			->withStrictSSL()
			->attach(array(
                'media'=> $file
            ))
			->send();

		return Handler::Response($rep);
	}


	
	
}