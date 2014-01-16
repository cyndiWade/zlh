<?php
include 'class/wx.class.php';
define("TOKEN", "weixin");
$AppId     = 'wx994254cb39f55990';
$AppSecret = 'fcec64679e88b8461fea88bd6e8ec4aa';
$obj = new wx($AppId,$AppSecret);
//echo $obj->get_token();
//echo $obj->del_menu(); 
//echo $obj->get_group();

 $data ='{
     "button":[
     {	
		  "type":"view",
          "name":"购买商品",
		  "url" :"http://flowerpacker.com/website/index.php"
      },{  
		   "type":"click",
           "name":"订单服务",
           "key" :"menu_3"
           
       },{    
		   "type":"click",
           "name":"关于我们",
           "key" :"menu_3"
           
       }]
 }';
echo $obj->create_menu($data);
//$data ='{"group": {"id": 107, "name": "test3"}}'; // 创建分组

//echo $obj->creat_group($data);

//$data ='{"group":{"id":102,"name":"102"}}'; // 修改分组名称
//echo $obj->update_group($data);

//$data='{"openid":"oNuXDt2fL3GIeQEL_LgEReh5lUPc","to_groupid":101}'; // 移动用户的分组
//echo $obj->remove_user_to_group($data,$url);

//echo $obj->get_user_info('oNuXDt2fL3GIeQEL_LgEReh5lUPc');  // 获得用户的基本信息


//$NEXT_OPENID = 'oNuXDt60olq4p1osbjqDH5Lbcor0';
//$NEXT_OPENID ='';
//$result = $obj->get_user_list($NEXT_OPENID); // $NEXT_OPENID  默认的是从头开始拉取

//$data='{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}'; //临时
//$data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}'; // 永久的

//echo $obj->get_ticket($data);
//echo $obj->get_two_code($data);
//echo $obj->get_group();