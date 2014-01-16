<?php
header('Content-Type:text/html;charset=utf-8');
include 'function.php';

//$a = $_GET['xy'];
$arr = json_decode(urldecode(passport_decrypt($_GET['xy'],URLKEY)));
/*header("Cache-Control: public");
header("Pragma: cache");
$offset = 30*60*60*24; // cache 缓存一个月
$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT";
header($ExpStr);*/
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>地图</title>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<script type="text/javascript" src="RichMarker_min.js"></script>
<style>
body{margin:0;padding:0}

.left { float:left; }
.right { float:right; }
.autohei { height:auto; overflow:hidden; zoom:1; }
.clear { font-size:0; line-height:0; clear:both; overflow:hidden; zoom:1; }
.blank { height:10px; font-size:0; line-height:0; clear:both; overflow:hidden; }
.sm_img{display:inline;margin-right:10px}
.sm_img img{width:80px;height:80px;}
.info_box{width:420px;height:227px;background:url(images/map_background.png) left top no-repeat; position: absolute;padding:10px;font-family:"黑体";}
.info_txt1{line-height:30px;padding:5px 0;}
.info_txt1 span.left{font-size:24px;color:#fff;}
.info_txt1 span.right{color:#8c8c8c;}
.info_txt2{line-height:30px;padding:5px 0;font-size:24px;} 
.info_txt2 span.left{color:#90ccc0;}
.info_txt2 span.right{color:#f1b72f;}
.info_txt2 span.right small{font-size:18px;}
.info_txt3{line-height:30px;padding:10px 0 0;color:#fff;font-size:24px;}
</style>
</head>
<body>
<div style="width:950px;height:1250px;border:1px solid gray;margin:10px;" id="container">
</div>
<script type="text/javascript">
var map = new BMap.Map("container");
map.centerAndZoom(new BMap.Point(<?php echo $arr['0']->y.','.$arr['0']->x; ?>), 11);
map.enableScrollWheelZoom();
<?php foreach($arr as $key=>$val){
 //echo $val->y.','.$val->x;
 if($key==0)continue;
?>
var htm<?php echo $key; ?> = "<div id='overLay'  class='info_box'>"
			  +"<span class='left sm_img'><img style='margin-left:9px;margin-top: 8px;' src='images/small.jpg' /></span>"
			  +"<div class='autohei'>"
					+"<div class='autohei info_txt1'><span class='left'>上海浦东四级酒店</span><span class='right'>5星级</span></div>"
					+"<div class='autohei info_txt2'><span class='left'>5分</span><span class='right'><small>￥</small>400起</span></div>"
			  +"</div>"
			  +"<div class='clear'></div>"
			  +"<div class='info_txt3'>浦东陆家嘴金融中心</div>"
	      + "</div>",
myRichMarker<?php echo $key; ?> = new BMapLib.RichMarker(htm<?php echo $key; ?>,  new BMap.Point(<?php echo $val->y.','.$val->x; ?>),{
                                                  "anchor" : new BMap.Size(-47, -116),
												  "enableDragging" : true});
map.addOverlay(myRichMarker<?php echo $key; ?>);
<?php
}
foreach($arr as $key=>$val){
 //echo $val->y.','.$val->x;
 if($key==0)continue;
?>
myRichMarker<?php echo $key; ?>.addEventListener("onclick", function(e) {
	window.location.href='http://baidu.com?id='+<?php echo $val->id ; ?> ;
});
<?php } ?>
</script>
</body>
</html>
