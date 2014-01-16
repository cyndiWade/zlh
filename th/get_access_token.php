<?php

//$token = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx850fc7030b8543bf&secret=ecba75e3fff8e6c081a512ccb055bbc2");


//尊旅会TOken
$token = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx06529b330cb53393&secret=a807b2be47b0a7f11ffea8d1a8d4dc91");

$t = json_decode($token);
return $t->access_token;


