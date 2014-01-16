<?php
/**
  * wechat php test
  * 业务流程，
  * 1 发送给微信一个信息
  * 2 微信接受到信息到指定的网站url取数据
  * 3 取到数据，返回给微信后台，后台返回给客户端
  */
header('Content-Type:text/html;charset=utf-8');
ini_set('date.timezone','Asia/Shanghai');
//define your token
define("TOKEN", "rikee");	//身份信息
define('IN_ECS', true);
define('ROOT_PATH', str_replace('index.php', '', str_replace('\\', '/', __FILE__)));
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
include 'tpl_xml.php';
include 'city.php';
include 'class/mysql.class.php';
include 'function.php';
include 'class/sms.class.php';
$smsclient = new SMSClient('961958','admin','DULKN1');

global $db;
if(!empty($postStr)){
$postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$fromUsername = $postObj->FromUserName;   // 来自得微信号
$toUsername   = $postObj->ToUserName;       // 发给谁
$msgType      = trim($postObj->MsgType);      // 内容的类型
$time         = time();

//临时图片信息
$logo_img = "http://yunqiserver.xicp.net/ftp/tjr/images/logo.png";
$right_imgs = array(
	"http://yunqiserver.xicp.net/ftp/tjr/images/1.jpg",
	"http://yunqiserver.xicp.net/ftp/tjr/images/2.jpg",
	"http://yunqiserver.xicp.net/ftp/tjr/images/3.jpg",
	"http://yunqiserver.xicp.net/ftp/tjr/images/4.jpg"
);

/*------------------------------------------------------ */
//-- 消息的语音类型
/*------------------------------------------------------ */
tolog('a.txt',$fromUsername.'---'.$msgType);
tolog('ab.txt',$postObj->Event);
if($msgType=='voice' or $msgType=='text' )
{
	$MediaId       = $postObj->MediaId;
	$Format        = $postObj->Format;
	$Recognition   = $postObj->Recognition;
	$MsgId         = $postObj->MsgID;
	$user = $db->getOne("select * from weixin_user where user_code ='".$fromUsername."'");
	if(empty($user)){
		$userinfo = array('user_code'=>$fromUsername);
        $db->autoExecute('weixin_user',$userinfo,'INSERT');
	}
	if(empty($Recognition)){
	  $Recognition       = trim($postObj->Content);
	}
	tolog('a.txt',$Recognition);
	if(preg_match("/^1\d{10}$/",$Recognition)){
		 $No ='您的手机号是：'.$Recognition.'。验证码将在5分钟内发送到您手机上，请您接到验证码后，输入验证码';
		 $yzm = mt_rand(100,999).mt_rand(100,999);
		 $msg = $yzm.'为您的注册验证码，请于30分钟内完成注册，过期作废。如非本人操作请忽略'.'【尊旅会】';
		 $res = $smsclient->sendSMS($Recognition,$msg);
		 $array = array('user_tel'=>$Recognition,'yzm'=>$yzm);
		 $where = "user_code = '".$fromUsername."'";
		 $result = $db->autoExecute('weixin_user',$array,'UPDATE',$where);
		 if($result){

			 $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $No);
			 echo $resultStr;
		 }
		 exit;
	}
	if(preg_match("/[0-9]{6}$/",$Recognition)){
	  if(ver($fromUsername) and ver($fromUsername)==1){
			 $sql ="select yzm from weixin_user where user_code = '".$fromUsername."' ";
			 $yzm = $db->getOne($sql);
			 $sqltel ="select yzm from weixin_user where user_code = '".$fromUsername."' ";
			 $tel = $db->getOne($sqltel);
			 if($Recognition == $yzm){
				 $array = array('is_ver'=>1);
				 $where = "user_code = '".$fromUsername."'";
				 $result = $db->autoExecute('weixin_user',$array,'UPDATE',$where);
				 $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text','恭喜您成为我们尊敬的尊旅会会员，请用文字或语音录入您下榻酒店的城市或酒店名。');
				 echo $resultStr;
				 exit;
			 }elseif($Recognition = !$yzm and !empty($yzm)){
			     $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text','验证码错误');
				 echo $resultStr;
				 exit;
			 }else{
				 $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text','验证码错误');
				 echo $resultStr;
				 exit;
			 } 	     
	  }elseif(ver($fromUsername)==2){
		   $No  = '绑定手机号';
		   $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $No);
		   echo $resultStr;
		   exit;

	  }
	}

	if(ver($fromUsername)== 2 or ver($fromUsername)==1){
	    $No  = '尊敬的尊旅会会员，第一次登陆请回复您的手机号进行身份验证。';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $No);
		echo $resultStr;
		exit;
	}
	$row1 = $db->getRow("select * from location where user_name = '".$fromUsername."' ");
	// 判断订单到哪一步了
	$db->query("delete from `weixin_order_state` where endtime < ".time()); // 清除过时的会话

	$st = $db->getOne("SELECT step FROM `weixin_order_state` where user_code ='".$fromUsername."'");
    $step = empty($st) ? 0 : $st;
	$userinfo = $db->getRow("SELECT * FROM `weixin_order_state` where user_code ='".$fromUsername."'");
	if($step==0){
		preg_match('/'.$city.'/',$Recognition,$m); // 判断语音中说的是哪个城市
		$selectCity = $m[0];
		if(empty($selectCity)){
		   $keyword= '您输入的是"'.$Recognition.'"，不是城市名称';
		   $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $keyword);
		   echo $resultStr;
		   exit;
		}
		 //$sql = "SELECT * FROM `merchant_infor` where city_district_id in ( select district_id from city_city as c join city_district as d on c.city_id = d.city_id where city_name like '%$selectCity%' ) limit 0,6 ";
		$sql ="SELECT * FROM `hotels` where hotel_cs like '%$selectCity%' ";
		$re = $db->getAll($sql);
		if(empty($re)){
		   $keyword= '没有该'.$selectCity.'城市的酒店';
		   $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $keyword);
		   echo $resultStr;
		   exit;
		}
         $num = count($re)+1;
	     $tpl ="<xml>
				   <ToUserName><![CDATA[$fromUsername]]></ToUserName>
				   <FromUserName><![CDATA[$toUsername]]></FromUserName>
				   <CreateTime>$time</CreateTime>
				   <MsgType><![CDATA[news]]></MsgType>
				   <ArticleCount>$num</ArticleCount>
				   <Articles>
				   <item>
					<Title><![CDATA[周边酒店]]></Title> 
					<Description><![CDATA[周边酒店]]></Description>
					<PicUrl><![CDATA[$logo_img]]></PicUrl>
					<Url><![CDATA[http://api.map.baidu.com/staticimage?width=800&height=600&markers=121.51158053065,31.247144882851|121.501208706,31.2514108937|121.505425763,31.2581667744|121.504076371,31.2581195975|&zoom=15&markerStyles=l,0,cccccc|l,1,0xFF3900|l,2,0xFF3900|l,3,0xFF3900|]]></Url>
					</item>";
				   foreach($re as $key=>$v){
		  $tpl .="<item>
					<Title><![CDATA[$v[link_id].$v[link_name]  ￥$v[hotel_1]起 $v[hotel_pf]分]]></Title> 
					<Description><![CDATA[$v[link_name]]]></Description>
					<PicUrl><![CDATA[$right_imgs[1]]]></PicUrl>
					<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/wxadmin/hotel_info.php?id=$r$v[link_id]]]></Url>
					</item>";
					}
		  $tpl .="<item>
					<Title><![CDATA[点击下面查看所有酒店]]></Title> 
					<Description><![CDATA[点击下面查看所有酒店]]]></Description>
					<PicUrl><![CDATA[$right_imgs[2]]]></PicUrl>
					<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/map.php?xy=$xy]]></Url>
					</item></Articles>
				   </xml>";          
           echo $tpl;
           $yo = array(
			  'user_code' =>$fromUsername,
			  'step'      =>1,
			  'hotel_add' =>$selectCity,
			  'starttime' =>time(),
			  'endtime'   => time()+3000
			  );
		   $db->autoExecute('weixin_order_state',$yo,'INSERT');

	}
	// 选择酒店
	elseif($step==1){
		$sql =" SELECT * FROM `hotels` ";
		//判断是数字
		if(is_numeric($Recognition)){
		   $sql .=" where   link_id = ".$Recognition;
		}else{
		   $sql .=" where  `link_name` like '%".$Recognition."%' ";
		}
		$result = $db->getRow($sql);
		if($result){
			$tpl ="<xml>
					   <ToUserName><![CDATA[$fromUsername]]></ToUserName>
					   <FromUserName><![CDATA[$toUsername]]></FromUserName>
					   <CreateTime>$time</CreateTime>
					   <MsgType><![CDATA[news]]></MsgType>
					   <ArticleCount>2</ArticleCount>
					   <Articles><item>
						<Title><![CDATA[$result[link_id].$result[link_name]]]></Title> 
						<Description><![CDATA[$result[link_name]]]></Description>
						<PicUrl><![CDATA[$logo_img]]></PicUrl>
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/wxadmin/hotel_info.php?id=$result[link_id]]]></Url>
						</item>
						<item>
						<Title><![CDATA[$result[link_id] $result[hotel_fx] $result[hotel_1] 元起]]></Title> 
						<Description><![CDATA[$result[hotel_fx].$result[hotel_1]]]></Description>
						<PicUrl><![CDATA[$logo_img]]></PicUrl>
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/wxadmin/hotel_info.php?id=$result[link_id]]]></Url>
						</item>
						</Articles>
						</xml>";
			echo $tpl;
			$yo = array(
				  'user_code' =>$fromUsername,
				  'step'      =>2,
				  'starttime' =>time(),
				  'endtime'   => time()+3000,
				  'hotel_id'  => $result['link_id'],
				  'hotel_name'=> $result['link_name']
				  );
			$where = "user_code = '".$fromUsername."'";
			$db->autoExecute('weixin_order_state',$yo,'UPDATE',$where);
        }else{
		  $info ='输入有误，您输入的是'.$Recognition;
		  $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text',$info);
	      echo $resultStr;
		}
	
	}
	// 选择房间
	elseif($step==2){
           //判断是数字
		   $sql = "SELECT * FROM `hotels` ";
		if(is_numeric($Recognition)){
		   $room_id =$Recognition;
		   $sql .=" where   link_id = ".$Recognition;
		}else{
		   $room_name =$Recognition;
		   $link_id = $userinfo['hotel_id'];
		   $sql .=" where  `hotel_fx` like '%".$Recognition."%' and link_id = $link_id ";
		}
		$room = $db->getRow($sql);
		if(empty($room)){
              $info  = "您说的是'".$Recognition."',请重新输入房间关键词。";
			  $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text',$info);
			  echo $resultStr;
			  exit;
		}
		$info ='你选择了"'.$room['hotel_fx'].'" 价格为'.$room['hotel_1'].'元。请用语音或文字录入入住时间。如：今天，明天，后天，日期(2013年12月13日)';
        $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text',$info);
	    echo $resultStr;
	    $yo = array(
			  'user_code' =>$fromUsername,
			  'step'      =>3,
			  'starttime' =>time(),
			  'endtime'   => time()+3000,
			  'room_id'   =>$room['link_id'] ,
			  'room_name' =>$room['hotel_fx'],
			  'room_price'=>$room['hotel_1']
			  );
	    $where ="user_code='".$fromUsername."'";
		$db->autoExecute('weixin_order_state',$yo,'UPDATE',$where);
	}
	// 入住时间
	elseif($step==3){

		$day = array(1=>'今天',2=>'明天',3=>'后天');
		if( in_array($Recognition,$day ) ){
		  $key = array_keys($day,$Recognition);
		  $key = $key[0];
		}
		if($key){
		  $startrz = time()+$key*86400;
		}else{
		  $startrz = getTime($Recognition);
		}
		if(empty($startrz)){
		  $info = '请规范说出日期，如：2013年12月13日。您说的是'.$Recognition;
		}else{
		  $info = '您的入住时间是"'.$Recognition.'"。请您说出离店日期如：今天，明天，后天，日期(2013年12月13日)';
		  $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text',$info);
		  echo $resultStr;
		  $yo = array(
				  'user_code' =>$fromUsername,
				  'step'      =>4,
				  'starttime' =>time(),
				  'endtime'   => time()+3000,
				  'startrz'   =>$startrz
				  );
		  $where ="user_code='".$fromUsername."'";
		  $db->autoExecute('weixin_order_state',$yo,'UPDATE',$where);
		}
	}
	// 离店时间
	elseif($step==4){
	    $day = array(1=>'今天',2=>'明天',3=>'后天');
		if( in_array($Recognition,$day ) ){
		  $key = array_keys($day,$Recognition);
		  $key = $key[0];
		}
		if($key){
		  $endTimes = time()+$key*86400;
		}else{
		  $endTimes = getTime($Recognition);
		}
		if(empty($endTimes)){
		  $info = '请规范说出日期，如：2013年12月13日。您说的是"'.$Recognition.'"';
		}else{
		  $info = '您的离开时间是'.$Recognition.'。请核对预定信息后，语音或文字录入：确认或付款。如未付款订单将半小时后取消。';
		}
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text',$info);
	    echo $resultStr;
		  $sql ="select * from weixin_order_state where user_code ='".$fromUsername."'";
		  $row  = $db->getRow($sql);
		  $start = date('Y-m-d',$row['startrz']);
		  $end   = date('Y-m-d',$endTimes);
		  $datetime1 = new DateTime($start);  
          $datetime2 = new DateTime($end);  
          $interval = $datetime1->diff($datetime2);  
          $day = str_replace('+','',$interval->format('%R%a')); 
		  $total  = $row['room_price']*$day;
        $yo = array(
			  'user_code' =>$fromUsername,
			  'step'      =>5,
			  'starttime' =>time(),
			  'endtime'   => time()+3600,
			  'endlikai'  =>$endTimes ,
			  'total'=>$total
			  );
	    $where ="user_code='".$fromUsername."'";
		$db->autoExecute('weixin_order_state',$yo,'UPDATE',$where);
	
	}elseif($step==5){
        if($Recognition=='确认' or $Recognition=='付款'){
		  
		  $sql ="select * from weixin_order_state where user_code ='".$fromUsername."'";
		  $re = $db->getRow($sql);
          $info1 ='您预定的酒店是'.$re['hotel_name'];
		  $info2 ='您选择的房型是'.$re['room_name'];
		  $info3 ='您的入住日期是'.date('Y-m-d',$re['startrz']);
		  $info4 ='您的离店日期是'.date('Y-m-d',$re['endlikai']);
		  $info5 ='您的费用'.$re['total'].'元';
		  //	<PicUrl><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/logo.jpg]]></PicUrl>
		  $tpl ="<xml>
					   <ToUserName><![CDATA[$fromUsername]]></ToUserName>
					   <FromUserName><![CDATA[$toUsername]]></FromUserName>
					   <CreateTime>$time</CreateTime>
					   <MsgType><![CDATA[news]]></MsgType>
					   <PicUrl><![CDATA[$logo_img]]></PicUrl>
					   <ArticleCount>6</ArticleCount>
					   <Articles>";

		 $tpl .="		<item>
						<Title><![CDATA[$info1]]></Title> 
						<Description><![CDATA[$info1]]></Description>
						<PicUrl><![CDATA[$logo_img]]></PicUrl>
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/pay.php]]></Url>
						</item>";

	     $tpl .="		<item>
						<Title><![CDATA[$info2]]></Title> 
						<Description><![CDATA[$info2]]></Description>
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/pay.php]]></Url>
						</item>";

		 $tpl .="		<item>
						<Title><![CDATA[$info3]]></Title> 
						<Description><![CDATA[$info3]]></Description>		
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/pay.php]]></Url>
						</item>";



		$tpl .="		<item>
						<Title><![CDATA[$info4]]></Title> 
						<Description><![CDATA[$info4]]></Description>			
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/pay.php]]></Url>
						</item>";

		$tpl .="		<item>
						<Title><![CDATA[$info5]]></Title> 
						<Description><![CDATA[$info5]]></Description>				
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/pay.php]]></Url>
						</item>";
					
		 $tpl .=		"<item><Title><![CDATA[<点我>前往付款页面]]></Title> 
						<Description><![CDATA[$val[room_name].$val[room_price]]]></Description>				
						<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/pay.php]]></Url>
						</item></Articles></xml>";
          $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text',$info);
	      echo $tpl;
		}
	}else{
	
	}
	
}
/*------------------------------------------------------ */
//-- 消息的文本类型
/*------------------------------------------------------ */
/*elseif($msgType=='text')
{
	$keyword = trim($postObj->Content);       // 内容
	//$contentStr = date("Y-m-d H:i:s",time()).'你好';
	$resultStr = sprintf($text, $fromUsername, $toUsername, $time, $msgType, $keyword);
	echo $resultStr;
	
}*/
/*------------------------------------------------------ */
//-- 消息的图片类型
/*------------------------------------------------------ */
elseif($msgType=='image')
{
	
}
/*------------------------------------------------------ */
//-- 消息的视频类型
/*------------------------------------------------------ */
elseif($msgType=='video')
{
	
}
/*------------------------------------------------------ */
//-- 消息的位置类型
/*------------------------------------------------------ */
elseif($msgType=='location')
{
	
}
/*------------------------------------------------------ */
//-- 消息的链接类型
/*------------------------------------------------------ */
elseif($msgType=='link')
{
	
}
/*------------------------------------------------------ */
//-- 消息的事件类型
/*------------------------------------------------------ */
elseif($msgType=='event')
{
	//判断是否是在定酒店的不中国
	$eventType = $postObj->Event; //消息时间类型
/*------------------------------------------------------ */
//-- 关注
/*------------------------------------------------------ */
	if($eventType=='subscribe'){
		// 关注的时候把用户的信息保存起来
		//判断一下数据库中有没有用户的数据
		$user = $db->getOne("select * from weixin_user where user_code ='".$fromUsername."'");
		if(empty($user)){
			$userinfo = array('user_code'=>$fromUsername);
            $db->autoExecute('weixin_user',$userinfo,'INSERT');
		}
		if($postObj->EventKey and $postObj->Ticket){
			$msgType = 'text';
			$contentStr  = '你扫描的是'.$postObj->EventKey.'二维码';
			$contentStr  = '请用文字或语音录入您将要入住的城市名称';
		}else{
		
		   $contentStr  = '请用文字或语音录入您将要入住的城市名称';
		}
		$msgType = 'text';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		echo $resultStr;
	}
/*------------------------------------------------------ */
//-- 取消关注
/*------------------------------------------------------ */
	elseif($eventType=='unsubscribe'){
		$msgType = 'text';
		$contentStr  = '您要无情的取消关注吗？';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		echo $resultStr;
	}
/*------------------------------------------------------ */
//-- 关注后扫描二维码事件
/*------------------------------------------------------ */
	elseif($eventType=='SCAN'){
		$msgType = 'text';
		$contentStr  = '你扫描的是'.$postObj->EventKey.'二维码';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		echo $resultStr;
	}
/*------------------------------------------------------ */
//-- 上报地理位置
/*------------------------------------------------------ */
	elseif($eventType=='LOCATION'){
       $Latitude  = $postObj->Latitude ; //Latitude 	地理位置纬度x
	   $Longitude = $postObj->Longitude ; //Longitude 	地理位置经度y 
	   $Precision = $postObj->Precision ; //Precision 	地理位置精度
       $stepinfo = array('user_name'=>$fromUsername,'location_x'=>$Latitude,'location_y'=>$Longitude,'Precisions'=>$Precision,'times'=>time());
	   if($db->getOne("select user_name from location where user_name='".$fromUsername."'")){
	     $db->autoExecute('location',$stepinfo,'UPDATE');
	   }else{
	     $db->autoExecute('location',$stepinfo,'INSERT');
	   }
	
	   
	}
/*------------------------------------------------------ */
//-- CLICK事件
/*------------------------------------------------------ */
	elseif($eventType=='CLICK'){

	  $EventKey = $postObj->EventKey; //事件key
      $st = $db->getOne("SELECT step FROM `weixin_order_state` where user_code ='".$fromUsername."'");
      if($st>=0){
	    $db->query("delete FROM `weixin_order_state` where user_code ='".$fromUsername."'");
	  }
	  $is_ver = $db->getOne("SELECT is_ver FROM `weixin_user` where user_code ='".$fromUsername."'");
	  if(!$is_ver and $EventKey !='menu_1_2'){
		$contentStr = '尊敬的尊旅会会员，第一次登陆请回复您的手机号进行身份验证。';
	    $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		echo $resultStr;
		exit;
	  }
	  /*------------------------------------------------------ */
      //-- 菜单key
      /*------------------------------------------------------ */
	  if($EventKey=='menu_1_1'){
		  $contentStr =  '请用文字或语音录入您下榻酒店的城市或酒店名';
		  $msgType   = 'text';
		  $db->query("delete from weixin_order_state where user_code ='".$fromUsername."'");
		  $resultStr = sprintf($text, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		  echo $resultStr;
	  }
	  elseif($EventKey=='menu_1_2'){
	  
	      $contentStr =" 客服电话：400-1234-888，在线时间为8点~22点，客服人员将一对一为您服务（温馨提示：按0直接转尊旅会的人工服务哦）";
		  $msgType = 'text';
		  $resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		  echo $resultStr;
	  }
	  elseif($EventKey=='menu_1_3'){
		 $yo = array(
			  'user_code' =>$fromUsername,
			  'step'      =>1,
			  'hotel_add' =>$selectCity,
			  'starttime' =>time(),
			  'endtime'   => time()+3000
			  );
		 $db->autoExecute('weixin_order_state',$yo,'INSERT');
         $sql = "SELECT * FROM `hotels` where 1 limit 0,5";
         //查询酒店
		 $hotel = $db->getAll($sql);
		 $num = count($hotel)+1;
		 $tpl ="<xml>
				   <ToUserName><![CDATA[$fromUsername]]></ToUserName>
				   <FromUserName><![CDATA[$toUsername]]></FromUserName>
				   <CreateTime>$time</CreateTime>
				   <MsgType><![CDATA[news]]></MsgType>
				   <ArticleCount>$num</ArticleCount>
				   <Articles>";
				   foreach($hotel as $key=>$v){
		  $tpl .="<item>
					<Title><![CDATA[$v[link_id].$v[link_name]  ￥$v[hotel_1]起 $v[hotel_pf]分]]></Title> 
					<Description><![CDATA[$v[link_name]]]></Description>
					<PicUrl><![CDATA[$right_imgs[3]]]></PicUrl>
					<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/wxadmin/hotel_info.php?id=$v[link_id]]]></Url>
					</item>";
					}
		  $tpl .="<item>
					<Title><![CDATA[点击下面查看所有酒店]]></Title> 
					<Description><![CDATA[点击下面查看所有酒店]]]></Description>
					<PicUrl><![CDATA[$logo_img]]></PicUrl>
					<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/map.php?xy=$xy]]></Url>
					</item></Articles>
				   </xml>";          
          echo $tpl;
	  }
	  /**************************************************************/
	  //---分享转奖金
	  /**************************************************************/
	  elseif($EventKey=='menu_1_4'){
			$menu_3_1="<xml>
			<ToUserName><![CDATA[$fromUsername]]></ToUserName>
			<FromUserName><![CDATA[$toUsername]]></FromUserName>
			<CreateTime>$time</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>1</ArticleCount>
			<Articles>
			<item>
			<Title><![CDATA[分享赚奖金]]></Title> 
			<Description><![CDATA[ 即日起关注尊旅会微博、微信分享到微信朋友圈或@三位好友转发微博即可获得奖金]]></Description>
			<PicUrl><![CDATA[$logo_img]]></PicUrl>
			<Url><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/wxadmin/wxshare.php]]></Url>
			</item>
			</Articles>
			</xml>";

		echo $menu_3_1;
	  }
	  elseif($EventKey=='menu_2_1'){
		$contentStr ="您尚未订房";
		$msgType = 'text';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		echo $resultStr;
	  }
	  /**************************************************************/
	  //---关于尊旅会
	  /**************************************************************/
	  elseif($EventKey=='gy'){
	    $contentStr ="尊旅会，商旅精英的贴身管家。成为高星级酒店联盟VIP会员，尊享会员超低房价和一站式贴身管家服务，打造高端精英商旅服务新平台。";
		$msgType = 'text';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		echo $resultStr;
	  }
	  /**************************************************************/
	  //---关于尊旅会
	  /**************************************************************/
	  elseif($EventKey=='xz'){
	    $contentStr ="这个年代，再不安装APP就凹凸啦！快点击链接：http://m.zunlvhui.com.cn体验管家式酒店服务吧！如果您是新用户，点击【我的尊旅会】->【会员注册】界面注册，查看【新用户专享】，还有200元酒店入住优惠券哦！";
		
		$msgType = 'text';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		echo $resultStr;
	  }
	  elseif($EventKey=='menu_3_2' or $EventKey=='menu_3_1' or $EventKey=='menu_3_3' or $EventKey=='menu_3_4'  or $EventKey=='menu_3_5' ){

          if($EventKey=='menu_3_2'){
			  $type = 1;
			  $name = '尊旅会-美食';
		  }
		  elseif($EventKey=='menu_3_3'){
		      $type = 2 ;
			  $name = '尊旅会-旅游';
		  }
		  elseif($EventKey=='menu_3_4'){
		      $type = 3 ;
			  $name = '尊旅会-购物';
		  }
		  elseif($EventKey=='menu_3_5'){
		      $type = 4 ;
			  $name = '尊旅会-娱乐';
		  }
		  //美食优惠券
          $sql ="select * from  `weixin_coupon` where type = $type";
		  tolog('a.txt',$sql);
		  $row = $db->getAll($sql);
		  $num = count($row);
		  if(empty($num)){
			$contentStr = '没有此优惠券';
			$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		    echo $resultStr;
		    exit;
		  }
		  $tpl = "<xml>
			<ToUserName><![CDATA[$fromUsername]]></ToUserName>
			<FromUserName><![CDATA[$toUsername]]></FromUserName>
			<CreateTime>$time</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>$num</ArticleCount>
			<Articles>";
			foreach($row as $key=>$val){
			$tpl .="
				<item>
				<Title><![CDATA[$name]]></Title> 
				<Description><![CDATA[$val[des]]]></Description>
				<PicUrl><![CDATA[http://yunqiserver.xicp.net/ftp/tjr/$val[image]]]></PicUrl>
				<Url><![CDATA[url]]></Url>
				</item>";
			}
			$tpl .="
			</Articles>
			</xml> ";
	     echo $tpl;
	  }
	  elseif( $EventKey=='menu_3_1' ){
		  $contentStr =" 正在开发中。。。";
		
		$msgType = 'text';
		$resultStr = sprintf($text, $fromUsername, $toUsername, $time, 'text', $contentStr);
		echo $resultStr;
	  
	  }
	  /*------------------------------------------------------ */
      //-- 菜单key结束
      /*------------------------------------------------------ */

	}

	/*------------------------------------------------------ */
	//-- 消息的事件类型结束
	/*------------------------------------------------------ */

}
/*------------------------------------------------------ */
//-- 消息错误类型
/*------------------------------------------------------ */

else{
	 echo '消息类型不对';
	 exit;
}


}
function ver($user_code){
     global $db ;
	 $sqltel ="SELECT user_tel FROM `weixin_user` where  user_code = '".$user_code."' ";
	 $re = $db->getOne($sqltel);
	 if($re){
		 $sql ="SELECT `is_ver` FROM `weixin_user` where  user_tel != '' and user_code = '".$user_code."' ";
		 $result = $db->getOne($sql);
		 if($result==0){
		   return  1;
		 }else{
		   return 0;
		 }
	 }else{
	    return 2;
	 }
}
?>