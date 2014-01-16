<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$sql ='select * from '. $GLOBALS['ecs']->table('hotels') .' where 1';
$hotel = $GLOBALS['db']->getAll($sql);

$smarty->assign('hotel_list',$hotel);
$smarty->display('hotel_list.dwt');