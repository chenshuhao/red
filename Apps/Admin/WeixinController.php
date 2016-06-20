<?php
namespace Apps\Admin;
//
//AppID(应用ID)
//wx991e0456593ce3f3
//AppSecret(应用密钥)
//cb7d7b26f17d0c3b5cda5bc4bb0ffc21
//
use Apps\BaseController;

class WeixinController extends BaseController
{

	public $token;
	public $config;
	public $userinfo;
	public $wechat;

	public function onConstruct ()
	{
		debug (file_get_contents ('php://input'));

		parent::onConstruct ();
		$this->token = $this->params ('t');
		if ( !$this->token ) exit();

		$this->config = \Config::findFirst (array(
			'conditions' => 'token=:token: ',
			'bind'       => array(
				'token' => $this->token
			)
		));


		if ( !$this->config ) exit();

	}

	public function handlerAction ()
	{
		#接入验证
		if ( $this->params ('echostr') ) {
			if ( $this->checkSignature () ) {
				ob_clean ();
				echo $_GET[ 'echostr' ];
				exit();
			}
		}

		#业务流程
		#实例化
		$this->wechat = $wechat = new \library\WeChat\WeChat();
		$wechat->init ($this->config->appid, $this->config->secret, $this->config->mch_id, $this->config->pay_key);


		$xml = file_get_contents ('php://input');

		$this->userinfo = $arr = $this->XmlToArray ($xml);

		debug (json_encode ($arr));


		if ( $arr[ 'MsgType' ] == 'event' ) {
			if ( $arr[ 'Event' ] == 'subscribe' ) {
				if($this->config->type == 1){
					$userinfo = $wechat->getUserinfoByopenid ($arr[ 'FromUserName' ]);
					$data = array(
						'openid'         => $userinfo[ 'openid' ],
						'subscribe'      => $userinfo[ 'subscribe' ],
						'nickname'       => $userinfo[ 'nickname' ],
						'sex'            => $userinfo[ 'sex' ],
						'city'           => $userinfo[ 'city' ],
						'country'        => $userinfo[ 'country' ],
						'province'       => $userinfo[ 'province' ],
						'language'       => $userinfo[ 'language' ],
						'headimgurl'     => $userinfo[ 'headimgurl' ],
						'subscribe_time' => $userinfo[ 'subscribe_time' ],
						'time'           => time (),
						'formticket'     =>isset($arr['Ticket'])?$arr['Ticket']:0
					);

					$userinfo2 = \Users::findFirst (array(
							'conditions' => 'openid=:openid:',
							'bind'       => array(
								'openid' => $userinfo[ 'openid' ],
							)
						)
					);
					$mo = new \Users();
					if ( !$userinfo2 ) {
						$mo->create ($data);
						$this->redsend();
					}

					if($arr['Ticket']){
						$this->redsend (null,'S');
					}else{
						$this->redsend (null,'T');
					}
				}else{ //非认证服务号
					$data = array(
						'openid'=>$arr[ 'FromUserName' ],
						'token'=>md5($arr[ 'FromUserName' ])
					);

					$userinfo = \Users::findFirst (array(
							'conditions' => 'openid=:openid:',
							'bind'       => array(
								'openid' =>$arr[ 'FromUserName' ]
							)
						)
					);

					if($userinfo){
						$this->sendJie($userinfo->token,$arr[ 'FromUserName' ],$this->config->wid);
						return;
					}else{
						if((new \Users())->create($data)){
							$this->sendJie($data['token'],$arr[ 'FromUserName' ],$this->config->wid);
							return;
						}
					}

				}
			}

			if ( $arr[ 'Event' ] == 'LOCATION' ) {
				$user = \Users::findFirst (
					array(
						'conditions' => 'openid=:openid:',
						'bind'       => array(
							'openid' => $arr[ 'FromUserName' ],
						)
					)
				);

				if ( $user ) {
					$GPS = new \library\GPS\GPS();
					$log = $GPS->gcj_encrypt($arr[ 'Latitude' ],$arr[ 'Longitude'] );
					$user->lat = $log[ 'lat' ];
					$user->lng = $log[ 'lon' ];

					if ( !$user->save () ) {
						error ('地理位置保存失败');
					}
				} else {
					error ('用户不存在');
				}
			}

			if($arr['Event'] == 'SCAN'){
				$user = \Users::findFirst (
					array(
						'conditions' => 'openid=:openid:',
						'bind'       => array(
							'openid' => $arr[ 'FromUserName' ],
						)
					)
				);

				$user->formticket =  $arr['Ticket'];
				$user->save();
				$this->redsend (null,'S');
			}


		}



		if ( $arr[ 'MsgType' ] == 'text' ) {
			#未收集资料的
			if($this->config->type == 1) {
				$userinfo = $wechat->getUserinfoByopenid ($arr[ 'FromUserName' ]);

				$data = array(
					'openid'         => $userinfo[ 'openid' ],
					'subscribe'      => $userinfo[ 'subscribe' ],
					'nickname'       => $userinfo[ 'nickname' ],
					'sex'            => $userinfo[ 'sex' ],
					'city'           => $userinfo[ 'city' ],
					'country'        => $userinfo[ 'country' ],
					'province'       => $userinfo[ 'province' ],
					'language'       => $userinfo[ 'language' ],
					'headimgurl'     => $userinfo[ 'headimgurl' ],
					'subscribe_time' => $userinfo[ 'subscribe_time' ],
					'time'           => time ()
				);

				$userinfo2 = \Users::findFirst (array(
						'conditions' => 'openid=:openid:',
						'bind'       => array(
							'openid' => $userinfo[ 'openid' ],
						)
					)
				);
				$mo = new \Users();
				if ( !$userinfo2 ) {
					$mo->create ($data);
				}
				$voteinfo = \Vote::findFirst (
					array(
						'conditions' => 'key=:key: and uid=:uid:',
						'bind'       => array(
							'key' => $arr[ 'Content' ],
							'uid' => $this->config->uid,
						)
					)
				);

				if ( $voteinfo ) {
					$this->redsend ($voteinfo,'T');
				}
			}
			else
			{

				$voteinfo = \Vote::findFirst (
					array(
						'conditions' => 'key=:key: and uid=:uid:',
						'bind'       => array(
							'key' => $arr[ 'Content' ],
							'uid' => $this->config->uid,
						)
					)
				);

				if(!$voteinfo){
					return;
				}

				if($voteinfo->status != 1){
					$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '活动已经暂停!');
					return;
				}

				if($voteinfo->etime <= time()){
					$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '活动已经结束!');
					return;
				}

				if($voteinfo->stime > time()){
					$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '活动还没开始!');
					return;
				}


				$data = array(
					'openid'=>$arr[ 'FromUserName' ],
					'token'=>md5($arr[ 'FromUserName' ])
				);

				$userinfo = \Users::findFirst (array(
						'conditions' => 'openid=:openid:',
						'bind'       => array(
							'openid' =>$arr[ 'FromUserName' ]
						)
					)
				);

				if($userinfo){
					$this->sendJie($userinfo->token,$arr[ 'FromUserName' ],$this->config->wid,$voteinfo);
					return;
				}else{
					if((new \Users())->create($data)){
						$this->sendJie($data['token'],$arr[ 'FromUserName' ],$this->config->wid,$voteinfo);
						return;
					}
				}
			}




			return;
		}


	}

	private function checkSignature ()
	{
		$signature = $this->params ('signature');
		$timestamp = $this->params ('timestamp');
		$nonce = $this->params ('nonce');
		$token = $this->token;
		$tmpArr = array( $token, $timestamp, $nonce );
		sort ($tmpArr, SORT_STRING);
		$tmpStr = implode ($tmpArr);
		$tmpStr = sha1 ($tmpStr);
		if ( $tmpStr == $signature ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	protected function XmlToArray ( $xml )
	{
		return json_decode (json_encode (simplexml_load_string ($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), TRUE);
	}


	public function redsend ( $voteinfo = NULL ,$type='S')
	{
		if ( null == $voteinfo ) {
			$voteinfo = \Vote::findFirst (array(
				'conditions' => 'uid=:uid: and status=:status:',
				'bind'       => array(
					'uid'    => $this->config->uid,
					'status' => 1,
				)
			));
		}


		if ( !$voteinfo || $voteinfo->status != 1 ) {
			$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '活动已暂停!');
		}

		if ( time () < $voteinfo->stime ) {
			$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '活动时间还没有到!');
		}

		if ( time () > $voteinfo->etime ) {
			$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '活动已经结束!');
		}

		$this->asyncPut(array(
			'vid'=>$voteinfo->id,
			'openid'=>$this->userinfo[ 'FromUserName' ],
			'type'=>$type,
			'run'=>'T'
		),'red');

		#领取时间段 未写

		$userLinqu = \Vlog::count(array(
			'conditions'=>'openid=:openid: and vid=:vid:',
			'bind'=>array(
				'openid'=>$this->userinfo[ 'FromUserName' ],
				'vid'=>$voteinfo->id,
			)
		))?:0;

			if ( $userLinqu >= $voteinfo->num ) {
				$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '您领取次数已经用完!');
			}

		#添加异步任务
		$this->asyncPut(array(
			'vid'=>$voteinfo->id,
			'openid'=>$this->userinfo[ 'FromUserName' ],
			'type'=>$type,
			'run'=>'R'
		),'red');



		$this->wechat->sendKeFuMsg ($this->userinfo[ 'FromUserName' ], $this->config->wid, '[现金红包]系统正在为您发放红包,如果您没有收到红包请再试一次!');

	}

	public function sendJie($token,$toUser,$fromUser,$voteinfo=null){

		if ( null == $voteinfo ) {
			$voteinfo = \Vote::findFirst (array(
				'conditions' => 'uid=:uid: and status=:status:',
				'bind'       => array(
					'uid'    => $this->config->uid,
					'status' => 1,
				)
			));
		}

		echo '
		<xml>
			<ToUserName><![CDATA['.$toUser.']]></ToUserName>
			<FromUserName><![CDATA['.$fromUser.']]></FromUserName>
			<CreateTime>'.time().'</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>1</ArticleCount>
			<Articles>
				<item>
					<Title><![CDATA[点击领取您的红包!]]></Title> 
					<Description><![CDATA[点击拆开属于您的红包!记得同意获取您的位置哦,否则系统获取不到位置无法发送红包!]]></Description>
					<PicUrl><![CDATA[http://www.lexiongmao.cn/images/red.png]]></PicUrl>
					<Url><![CDATA[http://www.lexiongmao.cn/red/lin?token='.$token.'&vid='.$voteinfo->id.']]></Url>
				</item>
			</Articles>
		</xml>
		';

		return;
	}
}

