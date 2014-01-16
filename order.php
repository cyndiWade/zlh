<?php
define('IN_ECS', true);
$start = strtotime($_POST['rztime']);  // 用户选择的开始入住时间戳
$end = $start + $_POST['rzvalue']*86400;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>预定酒店</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>
body{ font-family:Arial, Helvetica, sans-serif; background-color:#f7f7f7;}
.top{background-color:#585f65;}
.top, .bottom{padding:10px 15px;}
.zong{color:#f48e3c; margin:0px 20px;}
.white{color:white; font-weight:bold;}
input{width:280px;}
.mop{ padding:10px 0 0 5px; text-align:left; font-weight:600;}
.inp1{ height:30px !important; line-height:30px !important;}
</style>
<script type="text/javascript" >
function checkinfo(){
  var user_name = document.getElementById('user_name').value;
  var user_card = document.getElementById('user_card').value;
  var user_tel = document.getElementById('user_tel').value;
  if(!user_name){
	  alert('姓名不能为空');
   return false;
  }
  if(!user_card){
     alert('身份证号不能为空');
   return false;
  }
  if(!user_tel){
   alert('电话不能为空');
   return false;
  }
  document.orderform.submit();
}

</script>
</head>
<body>
<div class="top">
<p style="color:#a8adb1;">您将预定：</p>
<p class="white">为波-上海登星酒店</p>
<p class="white">家庭房(无窗)	1间	<strong class="zong">￥248<?php echo $_POST['rzvalue']*$_POST['price'] ; ?></strong></p>
<p class="white">入住时间：<?php echo date('m/d',$start).'-'.date('m/d',$end).'('.$_POST['rzvalue'].'晚)'; ?></p>
</div>
<div class="bottom" align="center">
<p class="mop">填写入住信息，完成预定</p>
<form method='post' name='orderform' action='doneorder.php' >
<input type="hidden" name='room_id' value="<?php echo $_POST['room_id'] ; ?>" />
<input type="hidden" name='user_code' value="<?php echo $_POST['user_code'] ; ?>" />
<input type="hidden" name='user_rz' value="<?php echo date('m/d',$start).'-'.date('m/d',$end).'('.$_POST['rzvalue'].'晚)'; ?>"/>
<input type='hidden' name='price' value="<?php echo $_POST['rzvalue']*$_POST['price'] ; ?>" />
<input type="text" id='user_name' name="user_name" class="inp1" placeholder="请输入您的姓名">
<input type="text" id='user_card' name='user_card' class="inp1" placeholder="身份证号，用来向酒店提交订单">
<input type="tel"  id='user_tel' name="user_tel" class="inp1" placeholder="手机号用来确认订单">
<a href="javascript:void(0);" onclick="checkinfo();" /><img src="img/pict.png"  /></a>
</form>
</div>
</body>
</html>