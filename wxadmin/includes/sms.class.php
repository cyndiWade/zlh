<?php
/*
	SMS Sender
	need xml2array
*/
function xml2array($contents, $get_attributes=1) {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        return array();
    }
    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create();
    xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
    xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
    xml_parse_into_struct( $parser, $contents, $xml_values );
    xml_parser_free( $parser );

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array;

    //Go through the tags.
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = '';
        if($get_attributes) {//The second argument of the function decides this.
            $result = array();
            if(isset($value)) $result['value'] = $value;

            //Set the attributes too.
            if(isset($attributes)) {
                foreach($attributes as $attr => $val) {
                    if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                    /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
                }
            }
        } elseif(isset($value)) {
            $result = $value;
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;

            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                $current = &$current[$tag];

            } else { //There was another element with the same tag name
                if(isset($current[$tag][0])) {
                    array_push($current[$tag], $result);
                } else {
                    $current[$tag] = array($current[$tag],$result);
                }
                $last = count($current[$tag]) - 1;
                $current = &$current[$tag][$last];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;

            } else { //If taken, put all things inside a list(array)
                if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
                    array_push($current[$tag],$result); // ...push the new element into that array.
                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }

    return($xml_array);
}

class SMSClient {

// 	var $userId	= '800004';
// 	var $password	= 'rHbze4';
// 	var $account	= 'admin';


	//var $TokenId	= '36273ebc6f9c4cb783d80278aca8e48c';

	public function __construct($userId=null, $account=null, $password=null) {
		if ($userId) $this->userId = $userId;
		if ($password) $this->password = $password;
		if ($account) $this->account = $account;
	}

	function data_encode($data, $keyprefix = "", $keypostfix = "") {
	  assert( is_array($data) );
	  $vars=null;
	  foreach($data as $key=>$value) {
	    if(is_array($value)) $vars .= SMSClient::data_encode($value, $keyprefix.$key.$keypostfix.urlencode("["), urlencode("]"));
	    else $vars .= $keyprefix.$key.$keypostfix."=".urlencode($value)."&";
	  }
	  return $vars;
	}


	function _curl_post($url, $vars) {       

	    $ch = curl_init();    
	    curl_setopt($ch, CURLOPT_URL, $url);     
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不向网页输出 
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1);//POST请求
	    curl_setopt($ch, CURLOPT_POSTFIELDS, substr(SMSClient::data_encode($vars), 0, -1));//POST字段     
	    curl_setopt($ch, CURLOPT_VERBOSE, 1 );//启用时会汇报所有的信息     
	    $data = curl_exec($ch);
	    curl_close($ch);      
	    if ($data)
		return $data;     
	    else
		return false;     
	} 

	public function login() { 
		$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/UserLogin";
		$output = SMSClient::_curl_post($url, array('UserID'=>$this->userId, 'Account'=>$this->account, 'Password'=>$this->password));
		$data = xml2array($output);
		/*$fd = fopen('/tmp/sms.log', 'a');
		fwrite($fd, sprintf("[%s] %s\n", date('Y-m-d H:i:s'), serialize($data)));
		fclose($fd);*/

		if ($data) {
			$ret = $data['ROOT'];
			if ($ret['RetCode']['value'] == 'Sucess') {
				$info = array('SegmentUpperLimit'=> $ret['SegmentUpperLimit']['value'],
					'UserRight'=>$ret['UserRight']['value'],
					'LongSmsLen'=>$ret['LongSmsLen']['value'],
					'Token'=>$ret['Token']['value'],
					'SmsStock'=>$ret['SmsStock']['value'],
					'FetchSendStat'=>$ret['FetchSendStat']['value']
				);
				$this->TokenId = $info['Token'];
				return array('errorno'=>0, 'info'=>$info);
			} else {
				return array('errorno'=>-1, 'info'=>$ret['Message']['value']);
			}
		} 
		return array('errorno'=>-1, 'info'=>'发送登陆请求失败');
	}

	public function getStockDetails() {
		$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/GetStockDetails";
		$output = SMSClient::_curl_post($url, array('Token'=>$token?$token:$this->TokenId));
		$data = xml2array($output);
		if ($data) {
			$ret = $data['ROOT'];
			if ($ret['RetCode']['value'] == 'Sucess') {
				$info = array('stockRemain'=>$ret['StockRemain']['value'],
					'points'=>$ret['Points']['value'],
					'sendTotal'=>$ret['SendTotal']['value'],
					'curDaySend'=>$ret['CurDaySend']['value']);

				return array('errorno'=>0, 'info'=>$info);
			} else {
				return array('errorno'=>-1, 'info'=>$ret['Message']['value']);
			}

		} 
		return array('errorno'=>-2, 'info'=>'发送请求失败');

	}

	public function logout($token=null) {
		$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/UserLogOff";
		$output = SMSClient::_curl_post($url, array('Token'=>$token?$token:$this->TokenId));

		$data = xml2array($output);
		if ($data) {
			$ret = $data['ROOT'];
			if ($ret['RetCode']['value'] == 'Sucess') {
				return array('errorno'=>0);
			} else {
				return array('errorno'=>-1, 'info'=>$ret['Message']['value']);
			}
		}
		return array('errorno'=>-1, 'info'=>'发送注销请求失败');
	}

	public function sendSMS($phones, $content, $postFixNumber=1, $sendTime='', $sendType=1) {
		/*if ($this->TokenId) {
			$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/MessSendSMS";
			$param = array('Token'=>$this->TokenId, 'Phones'=>$phones, 'Content'=>$content, 'SendTime'=>$sendTime, 'SendType'=>$sendType, 'PostFixNumber'=>$postFixNumber);
		} else {*/
			$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/DirectSend";
			$param = array('UserID'=>$this->userId, 'Account'=>$this->account, 'Password'=>$this->password,
				'Phones'=>$phones, 'Content'=>$content, 'SendTime'=>$sendTime, 'SendType'=>$sendType, 'PostFixNumber'=>$postFixNumber);
		//}
		/*echo "sendSMS $phones, $content, $sendTime\n";
		print_r($param);
		return array('errorno'=>0, 'info'=>array('jobid'=>10));*/
		$output = SMSClient::_curl_post($url, $param);

		$data = xml2array($output);
		//print_r($data);
		if ($data) {
			$ret = $data['ROOT'];
			$info = array('jobid'=>$ret['JobID']['value']
				);

			if ($ret['RetCode']['value'] == 'Sucess') {
				return array('errorno'=>0, 'info'=>$info);
			} else {
				return array('errorno'=>-1, 'info'=>$ret['Message']['value']);
			}
		}
		return array('errorno'=>-1, 'info'=>'发送短信请求失败');
	}

	public function fetchSMS() {
		/*$ret = array('errorno'=>0, 
			'info'=>array(
				'count'=>2,
				'smsgroup'=>array(
					'SMSGroup'=>array('value'=>'你们好啊','attr'=>array('Phone'=>'13810163123', 'RecDateTime'=>'2008-9-24 10:32')),
						array('value'=>'你也好啊','attr'=>array('Phone'=>'13810163123', 'RecDateTime'=>'2008-9-24 10:32'))
				)
			)
		);
		return $ret;*/

		if ($this->TokenId) {
			$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/FetchSMS";
			$param = array('Token'=>$this->TokenId);

		} else {
			$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/DirectFetchSMS";
			$param = array('UserID'=>$this->userId, 'Account'=>$this->account, 'Password'=>$this->password);
		}
		$output = SMSClient::_curl_post($url, $param);
		$data = xml2array($output);
		//print_r($data);
		/*$fd = fopen('/tmp/sms.log', 'a');
		fwrite($fd, sprintf("[%s] %s\n", date('Y-m-d H:i:s'), serialize($data)));
		fclose($fd);*/

		if ($data) {
			$ret = $data['ROOT'];
			if ($ret['RetCode']['value'] == 'Sucess') {
				if ($ret['Count']['value']) {
					$first_key = array_pop(array_keys($ret['Nodes']['SMSGroup']));

					$info = $ret['Count']['value']>1?$ret['Nodes']['SMSGroup']:array($ret['Nodes']['SMSGroup']);
				} else {
					$info = array();
				}


				return array('errorno'=>0, 'info'=>$info);
			} else {
				return array('errorno'=>-1, 'info'=>$ret['Message']['value']);
			}
		}
		return array('errorno'=>-1, 'info'=>'发送短信请求失败');
	}

	public function addNewAccount($account, $pwd, $signNameID, $memo='UCS Created') {
		if (!$this->TokenId) die('未登录');
		$url = "http://www.mxtong.net.cn/GateWay/Services.asmx/AddSubAccount";
		$param = array('Token'=>$this->TokenId, 'Account'=>$account, 'Password'=>$pwd, 'SignNameID'=>$signNameID, 'Memo'=>$memo);
		$output = SMSClient::_curl_post($url, $param);
		$data = xml2array($output);
		//print_r($data);
		if ($data) {
			$ret = $data['ROOT'];

			if ($ret['RetCode']['value'] == 'Sucess' || !$info['count']) {
				return array('errorno'=>0);
			} else {
				return array('errorno'=>-1, 'info'=>$ret['Message']['value']);
			}
		}
		return array('errorno'=>-1, 'info'=>'发送短信请求失败');
	}
}


/**
例子：
if ($argv) {
	print "[SMSClient run...]\n";
	$smsclient = new SMSClient();
	//$login_ret = $smsclient->login();
	//print_r($login_ret);
	//$logout_ret = $smsclient->logout();
	//print_r($logout_ret);
	//$sent_ret = $smsclient->sendSMS('13810163123;','sample message');
	//print_r($sent_ret);
	$fetch_ret = $smsclient->fetchSMS();
	print_r($fetch_ret);

	print "[SMSClient run end]\n";
	exit;
}
*/


$smsclient = new SMSClient('961958','admin','DULKN1');
$sent_ret = $smsclient->sendSMS('13761951734;','201255为您的注册验证码，请于30分钟内完成注册，过期作废。如非本人操作请忽略'.'【尊旅会】');
print_r($sent_ret);
?>
