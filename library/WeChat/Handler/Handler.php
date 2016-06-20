<?php

namespace library\WeChat\Handler;

class Handler extends \Apps\BaseController
{
	static public function Response ( $rep, $cache = NULL, $type = 'JSON' )
	{
		$rawBody = $rep->raw_body;

		switch ( $type ) {
			case 'JSON':
				$body = json_decode ($rawBody, 1);#to array
				if ( is_array ($body) ) {
					if ( isset( $body[ 'errcode' ] ) && $body[ 'errcode' ] != 0 ) {
						error ('[' . $body[ 'errcode' ] . ']' . $rep->request->uri);
						error ('[' . $body[ 'errcode' ] . ']' . $body[ 'errmsg' ]);

						return FALSE;//$ret[ 'errcode' ];
					} else {
						if ( $cache != NULL && is_array ($cache) ) {
							foreach ( $body as $key => $item ) {
								foreach ( $cache as $k => $v ) {
									if ( $key == $k ) {
										global $di;
										$di[ 'cache' ]->save ($v, $body[ $key ]);
									}
								}
							}
						}
					}

					return $body;
				}

				return FALSE;
				break;

			case 'XML':
				//TOD
				break;
		}


	}

}