<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$id = $_REQUEST['id'];
if(empty($id)){
  ecs_header("Location: ./hotel_list.php\n");
  exit;
}
$sql ='select * from '.$GLOBALS['ecs']->table('hotels').' where link_id ='.$id;

$hotel = $GLOBALS['db']->getRow($sql);
$smarty->assign('hotel',$hotel);
$smarty->display('hotel_info.dwt');