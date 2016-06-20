<?php
namespace library\WeChat;

class Result extends \Apps\BaseController
{
	static public $appid;
	static public $secret;
	static public $accessToken;
	static public $authAccessToken;
	static public $authRefreshToken;
	static public $openid;
	static public $mchid;
	static public $payKey;

	#jsapi
	static public $JsApiTicket;
}