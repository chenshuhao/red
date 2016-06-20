<?php
namespace library\WeChat\JsSdk;

use library\WeChat\Result;

class Signature extends \Apps\BaseController
{

	static public function Sign ( $noncestr, $timestamp, $url )
	{
		if ( !Result::$JsApiTicket ) {
			error ('JsApiTicket fail');

			return FALSE;
		}
		$params = array(
			'nonceStr'     => $noncestr,
			'jsapi_ticket' => Result::$JsApiTicket,
			'timestamp'    => $timestamp,
			'url'          => $url,
		);
		ksort ($params);

		foreach ( $params as $key => $item ) {
			$signature[] = strtolower($key) . '=' . $item;
		}

		return sha1 (join ('&', $signature));
	}

}