<?php

namespace library\WeChat\Pay;

use library\WeChat\Handler\Handler;
use library\WeChat\Result;

class Pay extends \Apps\BaseController
{
	public function outPay($openid,$price){
		$payData = array(
			'mch_appid' =>Result::$appid,
			'mchid' =>Result::$mchid,
			'nonce_str'=>hex(),
			'partner_trade_no'=>$this->partnerTradeNo(),
			'openid'=>trim($openid),
			'check_name'=>'NO_CHECK',
			'amount'=>$price,
			'desc'=>'摇钱罐提现',
			'spbill_create_ip'=>$_SERVER["SERVER_ADDR"],
		);
		$payData['sign'] = $this->sign($payData);

		#写入订单信息
		$Tixian = new \TixianLog();
		if(!$Tixian->create(array_merge($payData,array(
			'time'=>time()
		)))){
			error('提现订单创建失败'.json_encode(array_merge($payData,array(
					'time'=>time()
				))));
			return false;
		}
		
		$xml = $this->arrToXml($payData);
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

		$cert_pem = APP_ROOT.DIRECTORY_SEPARATOR.'pem'.DIRECTORY_SEPARATOR.'apiclient_cert.pem';
		$key_pem = APP_ROOT.DIRECTORY_SEPARATOR.'pem'.DIRECTORY_SEPARATOR.'apiclient_key.pem';

		$result = \Httpful\Request::post($url)
			->withStrictSSL()
			->authenticateWithCert($cert_pem,$key_pem)
			->body($xml)
			->send();

		libxml_disable_entity_loader(true);
		$xmlObj =  simplexml_load_string($result->body, 'SimpleXMLElement', LIBXML_NOCDATA);
		$xmlRet = json_decode(json_encode($xmlObj),1);

		if($xmlRet['return_code'] == 'SUCCESS' && $xmlRet['result_code'] == 'SUCCESS'){
			$Tixian->status = 1;
			if(!$Tixian->save()){
				error('提现订单状态更新失败,ID'.$Tixian->id);
			}
			return $Tixian->id;
		}else{
			$Tixian->status = 2; #提现失败
			$Tixian->msg = $xmlRet['return_code'].':'.$xmlRet['return_msg'].$xmlRet['err_code'].':'.$xmlRet['err_code_des'];
			if(!$Tixian->save()){
				error('提现订单状态更新失败,ID'.$Tixian->id);
			}
			return false;
		}
	}

	public function partnerTradeNo(){
		return time().date('YmdHis').mt_rand(10000,999999);
	}

	public function sign($payData){
		ksort($payData);
		foreach ($payData as $k=>$v){
			$strArr[] = $k.'='.$v;
		}
		$strArr[] = 'key='.Result::$payKey;
		

		return strtoupper(md5(join('&',$strArr)));
	}

	public function arrToXml($payData){
		$xml = '<xml>';

		foreach($payData as $k=>$v){
			$xml .= '<'.$k.'>'.$v.'</'.$k.'>';
		}
		$xml.= '</xml>';

		return $xml;
	}

	#现金红包
	public function sendRed($price,$cert_file,$key_file,$send_name,$openid,$wishing,$act_name){

		$data = array(
			'nonce_str'=>hex(),
			'mch_billno'=>time().date('Hiss').mt_rand(1,99).mt_rand(1,99).mt_rand(1,99),
			'mch_id'=>Result::$mchid,
			'wxappid'=>Result::$appid,
			'send_name'=>$send_name,
			're_openid'=>$openid,
			'total_amount'=>$price,
			'total_num'=>1,
			'wishing'=>$wishing,
			'client_ip'=> $this->config->ip->ip,
			'act_name'=>$act_name,
			'remark'=>'红包多多',
		);

		$data['sign'] = $this->sign($data);
		$xml = $this->arrToXml($data);
		try{
			$result = \Httpful\Request::post('https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack')
				->withStrictSSL()
				->authenticateWithCert($cert_file,$key_file)
				->body($xml)
				->send();
		}catch (\Exception $e){
			return array('status'=>0,'msg'=>$e->getMessage());
		}


		libxml_disable_entity_loader(true);
		$xmlObj =  simplexml_load_string($result->body, 'SimpleXMLElement', LIBXML_NOCDATA);
		$xmlRet = json_decode(json_encode($xmlObj),1);


		if($xmlRet['return_code'] == 'SUCCESS' && $xmlRet['result_code'] == 'SUCCESS'){
			return array('status'=>1,'msg'=>'发放成功');
		}else{
			return array('status'=>0,'msg'=>$xmlRet['return_msg'].$xmlRet['err_code_des']);
		}

	}
}