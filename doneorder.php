<?php
define('IN_ECS', true);
include 'class/mysql.class.php';
$user_code = $_POST['user_code'];
$order = array(
           'order_sn'  => time(),
		   'room_id'   => $_POST['room_id'],
		   'room_name' => $_POST['room_name'],
		   'user_code' => $_POST['user_code'],
		   'user_name' => $_POST['user_name'],
		   'user_card' => $_POST['user_card'],
		   'user_tel'  => $_POST['user_tel'],
		   'user_rz'   => $_POST['user_rz'],
		   'price'     => $_POST['price'],
		   'is_pay'    => 0
         );
$orderinfo = $db->getOne("select * from weixin_order where user_code ='".$user_code."' and is_pay = 0 ");
if(empty($orderinfo)){
  $db->autoExecute('weixin_order',$order,'INSERT');
}else{
  echo '你有未完成订单';
}