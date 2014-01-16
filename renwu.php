<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title></title>
<script>
</script>
</head>
<body>
<?php
define('IN_ECS',true);
include 'mysql.class.php';
global $db;
if($_GET['act']=='done'){
      $startnum  = $_POST['startnum'];
	  $endnum    = $_POST['endnum'];
	  $enddate   = strtotime($_POST['enddate']);
	  $mima      = $_POST['mima'];
	  if(md5($mima)!='e10adc3949ba59abbe56e057f20f883e'){
	    
		exit;
	  }
	  if(empty($startnum) or empty($endnum) or empty($enddate)){
		  return false;
	  }
	  $renwu = array('startnum'=>$startnum,'endnum'=>$endnum,'enddate'=>$enddate,'is_over'=>0);
      $db->autoExecute('renwu',$renwu,'INSERT');
	  header("location:renwu.php");
      exit;
}
$result = $db->getAll("select * from renwu where is_over = 0");
foreach ($result as $key=>$val){
echo "<div style='border:#ccc solid 1px;float:left;margin:2px 5px;' >到期时间<span style='color:red;'>".date('Y-m-d',$val['enddate'])."</span></br>";
	for($i=$val['startnum'];$i<=$val['endnum'];$i++){
?>

<a target='_blank' href="http://www.qianbao666.com/task/doTask.html?id=<?php echo $i; ?>"><?php echo $i; ?></a></br>

<?php
	}
echo '</div>';
}
?>
<div style="clear:both;"></div>
<form action="renwu.php?act=done" method="post" >
<table>
<tr>
	<td>任务开始编号<input type="text"  name="startnum" /></td>
	<td>任务结束编号<input type="text"  name="endnum" /></td>
	<td>任务结束时间<input type="text"  name="enddate" /></td>
	<td>任务结束时间<input type="password"  name="mima" /></td>
	<td><input type="submit" value="添加" /></td>
</tr>
</table>
</form>
</body>
</html>