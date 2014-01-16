<?php
include 'string.php';
$arr = explode(',',$string);
echo '<pre>';
//print_r(explode(',',$string));
echo '</pre>';
$i = 1;
$a[] = '';
foreach($arr as $key=>$val){

   $a[] = $val;
   if($val=='""' and is_numeric(str_replace('"','',$arr[$key+1]))){
         echo $arr[$key+1];
		 $ar[] = $a;
		 $a[]='';
   }
}
foreach($i=0;$i++;$i<=10){
echo '<pre>';print_R($ar[$i]);echo '</pre>';
}