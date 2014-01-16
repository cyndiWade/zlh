<?php
ini_set('display_error',1);
error_reporting(E_ALL);
include 'class/wx.class.php';
include 'tpl.php';
if(!empty($postStr)){
$postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$fromUsername = $postObj->FromUserName;   // 来自得微信号
$toUsername   = $postObj->ToUserName;       // 发给谁
$msgType      = trim($postObj->MsgType);      // 内容的类型
$time         = time();

$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

}
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

	$eventType = $postObj->Event; //消息事件类型
/*------------------------------------------------------ */
//-- 关注
/*------------------------------------------------------ */
	if($eventType=='subscribe'){
		if($postObj->EventKey and $postObj->Ticket){
			$msgType = 'text';
			$contentStr  = '你扫描的是'.$postObj->EventKey.'二维码';
		}else{
		
		    $contentStr  = '谢谢关注';
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
   
	}
/*------------------------------------------------------ */
//-- CLICK事件
/*------------------------------------------------------ */
	elseif($eventType=='CLICK'){

	}
}
