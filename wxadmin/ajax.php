<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$act = $_REQUEST['act'];

if($act=='changePrice'){

    $checkoutday = $_GET['checkoutday'];
	$checkinday  = $_GET['checkinday'];
    $house       = $_GET['house'];
	$id          = $_GET['link_id'];
	$datetime1 = new DateTime($checkinday);  
    $datetime2 = new DateTime($checkoutday);  
    $interval = $datetime1->diff($datetime2);  
    $day = str_replace('+','',$interval->format('%R%a')); 
	$sql ='select * from '.$GLOBALS['ecs']->table('hotels').' where link_id ='.$id;
	
	$hotel = $GLOBALS['db']->getRow($sql);
	$hotel['price'] = $day*$hotel['hotel_1']*$house;
    echo $hotel['price'];

}