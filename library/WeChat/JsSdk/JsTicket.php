<?php

namespace library\WeChat\JsSdk;

use library\WeChat\Handler\Handler;
use library\WeChat\Result;

class JsTicket extends \Apps\BaseController
{
	public $getticketURL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi';

	public function Ticket ()
	{
		if ( $this->cache->exists (md5(Result::$appid)."JsApiTicket") && ( $JsApiTicket = $this->cache->get (md5(Result::$appid).'JsApiTicket', 6000) ) && $JsApiTicket != null ) {
			return $JsApiTicket;
		}
		$rep = \Httpful\Request::get (
			sprintf (
				$this->getticketURL,
				\library\WeChat\Result::$accessToken
			))
			->expectsJson ()
			->withStrictSSL ()
			->send ();
		if($res = Handler::Response ($rep,array('ticket'=>md5(Result::$appid).'JsApiTicket'))){
			return $res['ticket'];
		}
	}
}