<?php

namespace library\WeChat\AccessToken;

use library\WeChat\Handler\Handler;

class AccessToken extends \Apps\BaseController
{
	public $tokenURL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

	public function AccessToken ()
	{
		if ( $this->cache->exists (md5(\library\WeChat\Result::$appid)."accessToken") && ( $accessToken = $this->cache->get (md5(\library\WeChat\Result::$appid).'accessToken', 6000) ) && $accessToken != null ) {
			return $accessToken;
		}
		$rep = \Httpful\Request::get (
			sprintf (
				$this->tokenURL,
				\library\WeChat\Result::$appid,
				\library\WeChat\Result::$secret
			))
			->expectsJson ()
			->withStrictSSL ()
			->send ();
		if($res = Handler::Response ($rep,array('access_token'=>md5(\library\WeChat\Result::$appid).'accessToken'))){
			return $res['access_token'];
		}
	}
}