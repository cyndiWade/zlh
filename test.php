<?php
ini_set('date.timezone','Asia/Shanghai');
ini_set('display_errors',1);
error_reporting(E_ALL);
header('Content-Type:text/html;charset=utf-8');
define('IN_ECS', true);
include 'class/mysql.class.php';
global $db;
$sqlroom = "SELECT * FROM `hotel_room` where merchant_id = 352";
$sql ="select * from merchant_infor";
$sqls ="SELECT * FROM `merchant_infor` WHERE `merchant_name` LIKE '%北京中环假日%' ";
$re['startrz']  = '1389930617';
$re['endlikai'] = '1390103420';
 echo $start = date('Y-m-d',$re['startrz']);
		 echo $end   = date('Y-m-d',$re['endlikai']);
		  $datetime1 = new DateTime($start);  
          $datetime2 = new DateTime($end);  
          $interval = $datetime1->diff($datetime2);  

          $day = str_replace('+','',$interval->format('%R%a')); 
		  echo $day;