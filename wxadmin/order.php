<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$id = $_REQUEST['id'];
if(!empty($id)){
	$checkoutday = $_GET['checkoutday'];
	$checkinday  = $_GET['checkinday'];

	$datetime1 = new DateTime($checkinday);  
    $datetime2 = new DateTime($checkoutday);  
    $interval = $datetime1->diff($datetime2);  
    $day = str_replace('+','',$interval->format('%R%a')); 
	if(empty($id)){
	  ecs_header("Location: ./hotel_list.php\n");
	  exit;
	}
	$sql ='select * from '.$GLOBALS['ecs']->table('hotels').' where link_id ='.$id;
	
	$hotel = $GLOBALS['db']->getRow($sql);
	$hotel['price'] = $day*$hotel['hotel_1'];
	$smarty->assign('hotel',$hotel);
	$smarty->assign('checkinday',$checkinday);
	$smarty->assign('checkoutday',$checkoutday);
	$smarty->display('order.dwt');

}else{
    $checkinday  = $_POST['checkinday'];
    $checkoutday = $_POST['checkoutday'];
    $house       = $_POST['house'];
    $Inperson    = $_POST['Inperson'];
    $telperson   = $_POST['telperson'];
    $tel         = $_POST['tel'];
    $checkintime = $_POST['checkintime'];
    $yq          = $_POST['yq'];
    $hotel_id    = $_POST['hotel_id'];
    $order_total = $_POST['order_total'];
	$datetime1 = new DateTime($checkinday);  
    $datetime2 = new DateTime($checkoutday);  
    $interval = $datetime1->diff($datetime2);  

    $day = str_replace('+','',$interval->format('%R%a')); 
    $time = time();
$sql = "insert into hotel_order(order_sn,checkinday,checkoutday,house,Inperson,telperson,tel,checkintime,yq,hotel_id,order_total)value($time,'$checkinday','$checkoutday',$house,'$Inperson','$telperson','$tel','$checkintime','$yq',$hotel_id,$order_total)";
    $result = $GLOBALS['db']->query($sql);
	if($result){
	     echo '订单提交成功';
	}else{
	    
	     echo '订单提交失败';
	}
   // echo '<pre>'; print_R($_POST);echo '</pre>';
}