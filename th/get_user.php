<?php
ini_set('max_execution_time',0);
header("Content-type: text/html; charset=utf-8");
$token = include 'get_access_token.php';
$openid = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token=$token");
$obj = json_decode($openid);
$obj->data;
$Allopenid = $obj->data->openid;
//print_R( $obj->data->openid);
foreach($Allopenid as $key=>$val){

  $name[] = get_nickname($token,$val);
}  
echo'<pre>';print_R($name);echo'</pre>';

// 获得用户的昵称
function get_nickname($token,$openid){

$info = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid");
$obj = json_decode($info);

return array('nickname'=>$obj->nickname,'openid'=>$obj->openid);
}