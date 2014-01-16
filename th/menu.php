<?php
ini_set('max_execution_time',0);
header("Content-type: text/html; charset=utf-8");
$token = include 'get_access_token.php';
define("ACCESS_TOKEN", $token);
//echo $token.'</br>';
$KF='{
    "touser":"oNuXDt2fL3GIeQEL_LgEReh5lUPc",
    "msgtype":"text",
    "text":
    {
         "content":"Hello World"
    }
}';

//echo KF($KF);
/*$data = '{
     "button":[
	 {
           "name":"订酒店",
           "sub_button":[
           {	
               "type":"click",
               "name":"所有城市酒店",
               "key":"All"
            },
            {
               "type":"click",
               "name":"附近的酒店",
               "key":"fjjd"
            },
            {
               "type":"click",
               "name":"今夜特价酒店",
               "key":"jytj"
            },
			{
               "type":"click",
               "name":"下载APP更方便",
               "key":"xz"
            }
			,
			{
               "type":"click",
               "name":"新用户优惠",
               "key":"xyhyh"
            }]
     },
     {	
          "name":"我的账户",
		  "sub_button":[
		  {
		     "type":"click",
			 "name":"我的订单",
			 "key" :"wddd"
		  },
		  {
		     "type":"click",
			 "name":"积分兑换酒店",
			 "key" :"jf"
		  },
		  {
		     "type":"click",
			 "name":"个人账户设置",
			 "key" :"gr"
		  },
		  {
			  "type":"click",
			  "name":"分享赚奖金",
			  "key":"fx"
		  },
		  {
		     "type":"click",
			 "name":"奖金申请提现",
			 "key" :"jd"
		  }]
      },
      {
           "name":"转人工服务",
           "sub_button":[
		  {
		     "type":"click",
			 "name":"尊旅会400电话",
			 "key" :"dh"
		  },
		  {
		     "type":"click",
			 "name":"尊旅会微信客服",
			 "key" :"kf"
		  },
		  {
			 "type" : "click",
			 "name" : "关于尊旅会",
			 "key"	: "gy"
		  }]
      }
      ]
 }';
*/
//echo createMenu($data);//创建菜单



//函数

//创建菜单
function createMenu($data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);  //菜单
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	     return curl_error($ch);
	}
	curl_close($ch);
    return $tmpInfo;
}
//发送客服消息
function KF($data){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".ACCESS_TOKEN);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	     return curl_error($ch);
	}
	curl_close($ch);
    return $tmpInfo;
}
//创建二维码
function chuangjian($data){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".ACCESS_TOKEN);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	     return curl_error($ch);
	}
	curl_close($ch);
	$t = json_decode($tmpInfo);

    return $t->ticket;
}
// 获得用户的昵称
function get_nickname($token,$openid){

$info = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid");
$obj = json_decode($info);

return array('nickname'=>$obj->nickname,'openid'=>$obj->openid);
}
?>