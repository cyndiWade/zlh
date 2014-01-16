<?php
define(URLKEY,'rikee');
/**
 * 加密
 * @param string $txt  加密内容
 * @param string $key	解密时的钥匙
 */
function passport_encrypt($txt, $key) {
	srand((double)microtime() * 1000000);
	$encrypt_key = md5(rand(0, 32000));
	$ctr = 0;
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
	}
	return base64_encode(passport_key($tmp, $key));
}

/**
 * 解密
 * @param string $txt	passport_encrypt()加密后的字符
 * @param $string $key	解密时的钥匙
 * @return Ambigous <string, boolean>
 */
function passport_decrypt($txt, $key) {
	$txt = passport_key(base64_decode($txt), $key);
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$md5 = $txt[$i];
		$tmp .= $txt[++$i] ^ $md5;
	}
	return $tmp;
}
//加密算法
function passport_key($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
      
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
}

function JSON($array) {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
}


/**
 * 计算二个经纬度之间的距离
 * @param unknown_type $d
 * @return number
 */
function rad($d) {
	return $d * 3.1415926535898 / 180.0;
}
function GetDistance($lat1, $lng1, $lat2, $lng2)	{//lat纬度(短的)，lng经度(长的)
	$EARTH_RADIUS = 6371;
	$radLat1 = rad($lat1);

	$radLat2 = rad($lat2);
	$a = $radLat1 - $radLat2;
	$b = rad($lng1) - rad($lng2);
	$s = 2 * asin(sqrt(pow(sin($a/2),2) +
			cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
	$s = $s *$EARTH_RADIUS;
	$s = round($s * 10000) / 10000;
	return $s;
}

/**
 *用指定经纬度，计算指定范围内，存在的数据
 *@param lng float 经度		(长的)		121.473704
 *@param lat float 纬度		(短的)		31.230393
 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米 (范围)
 *@return array 正方形的四个点的经纬度坐标
 *
 *参考资料：http://www.flyphp.cn/phpmysql-%E6%A0%B9%E6%8D%AE%E4%B8%80%E4%B8%AA%E7%BB%99%E5%AE%9A%E7%BB%8F%E7%BA%AC%E5%BA%A6%E7%9A%84%E7%82%B9%EF%BC%8C%E8%BF%9B%E8%A1%8C%E9%99%84%E8%BF%91%E7%9A%84%E4%BA%BA%E6%9F%A5%E8%AF%A2.html
 */
function _SquarePoint($lng, $lat,$distance = 2.5){		//经度、纬度、范围

	define('EARTH_RADIUS', 6371);	//地球半径，平均半径为6371km
	$dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);

	$dlat = $distance/EARTH_RADIUS;
	$dlat = rad2deg($dlat);

	//返回，经纬度坐标点内，正方形4个点的经纬度
	return array(
			'left-top'=>array('lng'=>$lng-$dlng,'lat'=>$lat + $dlat),				//左上：经度、纬度
			'right-top'=>array('lng'=>$lng + $dlng,'lat'=>$lat + $dlat),			//右上：经度、纬度
			'left-bottom'=>array('lng'=>$lng - $dlng,'lat'=>$lat - $dlat),		//左下：经度、纬度
			'right-bottom'=>array('lng'=>$lng + $dlng,'lat'=>$lat - $dlat)		//又下：经度、纬度
	);
}


/*
 * 记录日志的函数
*/
function tolog($path,$content){
		
		$path = ROOT_PATH.$path;
		if (!file_exists($path)) {
			file_put_contents($path,'create_file');
		}
		$file_content	=	file_get_contents($path);		
		$new_content = "\r\n".date('Y-m-d H:i:s').' -> '.$content;
		$file_content  .= $new_content;
		file_put_contents($path,$file_content);
		return $new_content;
}


// 时间的是函数
function getTime($date){

  $date = str_replace('年','-',$date);
  $date = str_replace('月','-',$date);
  $date = str_replace('日','',$date);
  $date = str_replace('号','',$date);

  return strtotime($date);
}

function get_price($id){
	global $db;
	$sql ="SELECT reject_price FROM  `merchant_infor` as m left join `hotel_room` as hr on m.merchant_id = hr.merchant_id join hotel_room_selfservice as hs on hs.room_id = hr.room_id where m.merchant_id =147 ";
	return $db->one($sql);

}