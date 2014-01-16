<?php

include 'class/wx.class.php';

$AppId     = 'wx06529b330cb53393';
$AppSecret = 'a807b2be47b0a7f11ffea8d1a8d4dc91';

$obj = new wx($AppId,$AppSecret);
$data ='{
     "button":[
     {	
          "name":"订酒店",
          "sub_button":[{
               "type":"click",
               "name":"智能订房",
               "key":"menu_1_1"
		  },{
               "type":"click",
               "name":"人工订房",
               "key":"menu_1_2"
		  },{
               "type":"click",
               "name":"每日特惠",
               "key":"menu_1_3"
		  },{
               "type":"click",
               "name":"分享赚奖金",
               "key":"menu_1_4"
		  }]
      },
      {
           "name":"我的账户",
		   "sub_button":[{
               "type":"click",
               "name":"我的订单",
               "key":"menu_2_1"
		  },{
               "type":"click",
               "name":"积分兑换酒店",
               "key":"menu_2_2"
		  },{
               "type":"click",
               "name":"个人账户设置",
               "key":"menu_2_3"
		  },{
               "type":"click",
               "name":"奖金申请提现",
               "key":"menu_2_4"
		  }]
           
      },
      {
           "name":"私人定制",
           "sub_button":[
           {
               "type":"click",
               "name":"租   车",
               "key":"menu_3_1"
            },
            {
               "type":"click",
               "name":"美   食",
               "key":"menu_3_2"
            },
            {
               "type":"click",
               "name":"旅   游",
               "key":"menu_3_3"
            },
            {
               "type":"click",
               "name":"购   物",
               "key":"menu_3_4"
            },
            {
               "type":"click",
               "name":"娱   乐",
               "key":"menu_3_5"
            }]
       }]
 }';

echo $obj->create_menu($data);


?>