<?php
include 'includes/wx.class.php';
$appid  = 'wx06529b330cb53393';
$secret = 'a807b2be47b0a7f11ffea8d1a8d4dc91';
$wx = new wx($appid,$secret);
$code = $_GET['code'];
//echo "<span style='color:red'>".$_GET['code'].'</span>';
$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx06529b330cb53393&secret=a807b2be47b0a7f11ffea8d1a8d4dc91&code=".$_GET['code']."&grant_type=authorization_code";
$result = file_get_contents($url);

//echo "<span style='color:red;font-size:19px;'>".$result.'</span>';

 $t = json_decode($result);
 $t->access_token;
$get_user_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$t->access_token.'&openid='.$t->openid.'&lang=zh_CN';
$result = file_get_contents($get_user_url);

echo "<span style='color:red;font-size:19px;'>".$result.'</span>';