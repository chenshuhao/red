<?php

namespace library\WeChat\Auth;

use library\WeChat\Handler\Handler;
use library\WeChat\Result;

class UserInfo extends \Apps\BaseController
{
	public $authorizeUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';
	public $accessTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
	public $userinfoUrl = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lWang=zh_CN';
	public $getUserInfoUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN';

	#redirect wechat auth
	public function redirect ( $appid, $redirect_uri, $scope = 'snsapi_userinfo', $state = 'STATE' )
	{
		header ('location:' . sprintf ($this->authorizeUrl, $appid, urlencode($redirect_uri), $scope, $state));
		eixt ();
	}

	public function codeGetUserInfo()
	{
		$rep = \Httpful\Request::get(
			sprintf(
				$this->userinfoUrl,
				Result::$authAccessToken,
				Result::$openid
			))
			->expectsJson()
			->send();
		$res = Handler::Response($rep);
		if($res){
			return $res;
		}
	}

	public function authAccessToken ($code)
	{
		$rep = \Httpful\Request::get(
			sprintf(
				$this->accessTokenUrl,
				\library\WeChat\Result::$appid,
				\library\WeChat\Result::$secret,
				$code
			))
			->expectsJson()
			->send();
		$res = Handler::Response($rep);
		if($res){
			Result::$authAccessToken = $res['access_token'];
			Result::$authRefreshToken = $res['refresh_token'];
			Result::$openid = $res['openid'];
			return $this;
		}
	}


	public function getUserInfoByOpenid($openid){
		$rep = \Httpful\Request::get(
			sprintf(
				$this->getUserInfoUrl,
				Result::$accessToken,
				$openid
			))
			->expectsJson()
			->send();
		$res = Handler::Response($rep);
		if($res){
			return $res;
		}
	}
}