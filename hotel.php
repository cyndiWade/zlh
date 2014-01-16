<?php
define('IN_ECS', true);
include 'class/mysql.class.php';
$sql="SELECT * FROM `merchant_infor` where merchant_id =".$_GET['id'];
$row = $db->getRow($sql);
$sqlroom = "SELECT * FROM `hotel_room` where merchant_id =".$_GET['id'];
$room = $db->getAll($sqlroom);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>快速订酒店</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<style>
body{ font-family:Arial, Helvetica, sans-serif; background-color:#f7f7f7; padding:0px 15px;}
button{background:none; border:none; margin:0; padding:0;}
.h,tr{border-bottom:#edeef0 solid 1px;}
.btn{width:90px; height:17px;}
table{width:100%;}
td{padding:5px 0px;}
.zong{color:#d65859; margin:0px 20px; font-weight:bold;}
input{width:128px;}
select{width:55px;}
.btn-large{padding:10px 5px;}
span a{color:#FFF;}
.disabled{color:#CCC;}
</style>
<script type="text/javascript" >
function getInfo(id){
  var rztime = document.getElementById('rztime').value; // 入住时间
  var rzvalue =  document.getElementById('rzvalue').value; // 入住几个晚上
  var user_code  = document.getElementById('user_code').value;  // 房间的价格
  var price  = document.getElementById('price'+id).value;  // 房间的价格
  document.write("<form action='order.php' method='post' name='form1'>");
  document.write("<input type='hidden' name='rztime' value="+rztime+" />");
  document.write("<input type='hidden' name='rzvalue' value="+rzvalue+" />");
  document.write("<input type='hidden' name='user_code' value="+user_code+" />");
  document.write("<input type='hidden' name='price' value="+price+" />");
  document.write("<input type='hidden' name='room_id' value="+id+" />");
  document.write("</form>");
  document.form1.submit();
  //location.href="order.php?rztime="+rztime+'&rzvalue='+rzvalue+'&roomid='+id;
}

</script>
</head>
<body>
<div class="h">
<input type="hidden" id='user_code' name='user_code' value="<?php echo $_GET['code'] ; ?>" />
<h4><?php echo $row['merchant_name'] ; ?></h4>
<p>电话:<?php echo $row['cs_telephone'] ; ?></p>
<p>地址:<?php echo $row['merchant_location'] ; ?></p>
入住：<input size="14" type="text" id='rztime' value="<?php echo date('Y-m-d',time()) ; ?>" readonly class="form_datetime">
<select id='rzvalue'>
    <option>1</option>
    <option>2</option>
    <option>3</option>
    <option>4</option>
    <option>5</option>
</select>
<button><img src="img/20131209014113376_easyicon_net_32.png"></button>
</div>
<table>
<?php foreach($room as $key=>$val){ ?>
	<tr><td><?php echo $val['room_name'] ; ?></td>		
	<td><span class="zong">￥<?php echo $val['room_price'] ; ?><input type='hidden' id='price<?php echo $val['room_id']; ?>' value="<?php echo $val['room_price'] ; ?>" /></span></td>		
	<td align="right"><span class="btn btn-large btn-primary disabled"><a href="javascript:void(0);" onclick="getInfo(<?php echo $val['room_id']; ?>)">官网直销</a></span></td>
	</tr>
<?php } ?>
	<tr><td>单人间(内宾)</td>	
	<td><span class="zong">￥188</span></td>		
	<td align="right"><span class="btn btn-large btn-primary disabled">官网直销</span></td>
	</tr>
    <tr><td>大床房(内宾)</td>	
	<td><span class="zong">￥208</span></td>		
	<td align="right"><span class="btn btn-large btn-primary disabled">满&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;房</span></td>
	</tr>
</table>
<p align="center"><a href="#">查看全部房型</a></p>
<p align="center"><img src="img/picf.png"></p>
<p align="center"><img src="img/picg.png"></p>
<script src="js/jquery.min.1.9.1.js" type="text/javascript"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
         format: 'yyyy-mm-dd',
				language:  'zh-CN',
		        weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				forceParse: 0

    });
</script>
</body>
</html>
