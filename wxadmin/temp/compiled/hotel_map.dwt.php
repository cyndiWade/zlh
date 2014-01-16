<!DOCTYPE html>
<html>
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}
</style>
<link rel="stylesheet" type="text/css" href="themes/default/default.css" />
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=9a9dIm29kxLcKLANg1FAM6tE"></script>
<title>酒店地图</title>
</head>
<body>

<!-- 
<?php $_from = $this->_var['hotel_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
<?php echo $this->_var['val']['link_name']; ?><?php echo $this->_var['val']['link_id']; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
-->


<div id="allmap"></div>
</body>
</html>


<script type="text/javascript">

// 百度地图API功能
var map = new BMap.Map("allmap");
var point = [];			//定位坐标
var marker = [];		//坐标标签
var coverage = [];		//弹出层





//添加所有酒店坐标红点
<?php $_from = $this->_var['hotel_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['val']):
?>

	point[<?php echo $this->_var['key']; ?>] = new BMap.Point(<?php echo $this->_var['val']['hotel_location_x']; ?>,<?php echo $this->_var['val']['hotel_location_y']; ?>);	//坐标
	marker[<?php echo $this->_var['key']; ?>] = new BMap.Marker(point[<?php echo $this->_var['key']; ?>]);	//标签
	map.addOverlay(marker[<?php echo $this->_var['key']; ?>]);				//写入红点定位
	
	//为每张红点定位的添加填出图层
	var tmp_html  = "<div id='<?php echo $this->_var['key']; ?>'  class='	'>"
		 +"<span class='left sm_img'><img style='margin-left:9px;margin-top: 8px;' src='themes/default/images/small.jpg' /></span>"
		 +"<div class='autohei'>"
		 +"<div class='autohei info_txt1'><span class='left'><?php echo $this->_var['val']['link_name']; ?></span><span class='right'><?php echo $this->_var['val']['hotel_xj']; ?>星级</span></div>"
		 +"<div class='autohei info_txt2'><span class='left'><?php echo $this->_var['val']['hotel_pf']; ?>分</span><span class='right'><small>￥</small><?php echo $this->_var['val']['hotel_1']; ?>起</span></div>"
		 +"</div>"
		 +"<div class='clear'></div>"
		 +"<div class='info_txt3'><?php echo $this->_var['val']['link_name']; ?></div>"
		 + "</div>";
	coverage[<?php echo $this->_var['key']; ?>] = new BMap.InfoWindow(tmp_html);

	marker[<?php echo $this->_var['key']; ?>].addEventListener("click", function(){this.openInfoWindow(coverage[<?php echo $this->_var['key']; ?>]);});
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

map.centerAndZoom(point[<?php echo $this->_var['id']; ?>], 15);	//定位



/*
var sContent = "<div id=''  class='info_box'>"
 +"<span class='left sm_img'><img style='margin-left:9px;margin-top: 8px;' src='themes/default/images/small.jpg' /></span>"
 +"<div class='autohei'>"
 +"<div class='autohei info_txt1'><span class='left'><?php echo $this->_var['hotel']['link_name']; ?></span><span class='right'><?php echo $this->_var['hotel']['hotel_xj']; ?>星级</span></div>"
 +"<div class='autohei info_txt2'><span class='left'><?php echo $this->_var['hotel']['hotel_pf']; ?>分</span><span class='right'><small>￥</small><?php echo $this->_var['hotel']['hotel_1']; ?>起</span></div>"
 +"</div>"
 +"<div class='clear'></div>"
 +"<div class='info_txt3'><?php echo $this->_var['hotel']['link_name']; ?></div>"
 + "</div>";

//定位当前酒店地图位置
var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
marker.addEventListener("click", function(){          
   this.openInfoWindow(infoWindow);
   //图片加载完毕重绘infowindow
   document.getElementById('imgDemo').onload = function (){
       infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
   }
});
*/


</script>



