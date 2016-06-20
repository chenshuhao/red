<?php

namespace library\WeChat\Shake;

use library\WeChat\Handler\Handler;
use library\WeChat\Result;

class Shake extends \Apps\BaseController
{

	public $getshakeinfoURL = 'https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token=%s';

	public function getShakeUserInfo ( $ticket )
	{
		$rep = \Httpful\Request::post (
			sprintf (
				$this->getshakeinfoURL,
				Result::$accessToken
			))
			->expectsJson ()
			->body (
				json_encode (array(
					'ticket' => $ticket
				), JSON_UNESCAPED_UNICODE)
			)
			->send ();
		$res = Handler::Response ($rep);
		if ( $res ) {
			return $res;
		}
	}
}