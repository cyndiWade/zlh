<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$id = $_REQUEST['id'];
if(empty($id)){
  ecs_header("Location: ./hotel_list.php\n");
  exit;
}
//获取当前酒店数据
//$sql ='select * from '.$GLOBALS['ecs']->table('hotels').' where link_id ='.$id;
//$hotel = $GLOBALS['db']->getRow($sql);

//获取所有酒店数据
$sql1 ='select * from '.$GLOBALS['ecs']->table('hotels').' where 1';
$result = $GLOBALS['db']->getAll($sql1);

//按照酒店ID，键组合数组
$result = regroupKey($result,'link_id',true);

//获取当前酒店数据
$hotel = $result[$id];	
// dump($result);
//exit; 
$smarty->assign('id',$id);
$smarty->assign('hotel_list',$result);
$smarty->assign('hotel',$hotel);
$smarty->display('hotel_map.dwt');

?>