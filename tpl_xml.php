<?php
/*
    文本消息模板

	ToUserName 	开发者微信号
	FromUserName 	发送方帐号（一个OpenID）
	CreateTime 	消息创建时间 （整型）
	MsgType 	text
	Content 	文本消息内容
	MsgId 	消息id，64位整型 
*/
$text ="<xml>
       <ToUserName><![CDATA[%s]]></ToUserName>
       <FromUserName><![CDATA[%s]]></FromUserName>
       <CreateTime>%s</CreateTime>
       <MsgType><![CDATA[%s]]></MsgType>
       <Content><![CDATA[%s]]></Content>
       <FuncFlag>0</FuncFlag>
       </xml>";
/*
	图片消息模板

	ToUserName 	开发者微信号
	FromUserName 	发送方帐号（一个OpenID）
	CreateTime 	消息创建时间 （整型）
	MsgType 	image
	PicUrl 	图片链接
	MediaId 	图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
	MsgId 	消息id，64位整型 
*/
$image ="<xml>
	 <ToUserName><![CDATA[%s]]></ToUserName>
	 <FromUserName><![CDATA[%s]]></FromUserName>
	 <CreateTime>%s</CreateTime>
	 <MsgType><![CDATA[%s]]></MsgType>
	 <PicUrl><![CDATA[%s]]></PicUrl>
	 <MediaId><![CDATA[%s]]></MediaId>
	 <MsgId>%s</MsgId>
	 </xml>";
/*
	语音消息模板

	ToUserName 	开发者微信号
	FromUserName 	发送方帐号（一个OpenID）
	CreateTime 	消息创建时间 （整型）
	MsgType 	语音为voice
	MediaId 	语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
	Format 	语音格式，如amr，speex等
	MsgID 	消息id，64位整型 
*/
$voice ="<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<MediaId><![CDATA[%s]]></MediaId>
	<Format><![CDATA[%s]]></Format>
	<MsgId>%s</MsgId>
	</xml>";



/*
	视频消息模板

	ToUserName 	开发者微信号
	FromUserName 	发送方帐号（一个OpenID）
	CreateTime 	消息创建时间 （整型）
	MsgType 	视频为video
	MediaId 	视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
	ThumbMediaId 	视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
	MsgId 	消息id，64位整型 
*/

$video ="<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<MediaId><![CDATA[%s]]></MediaId>
	<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
	<MsgId>%s</MsgId>
	</xml>";

/*

	地理位置模板

	ToUserName 	开发者微信号
	FromUserName 	发送方帐号（一个OpenID）
	CreateTime 	消息创建时间 （整型）
	MsgType 	location
	Location_X 	地理位置维度
	Location_Y 	地理位置精度
	Scale 	地图缩放大小
	Label 	地理位置信息
	MsgId 	消息id，64位整型 
*/
$location ="<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Location_X>%s</Location_X>
	<Location_Y>%s</Location_Y>
	<Scale>%s</Scale>
	<Label><![CDATA[%s]]></Label>
	<MsgId>%s</MsgId>
	</xml>";


/*
	链接消息模板

	ToUserName 	接收方微信号
	FromUserName 	发送方微信号，若为普通用户，则是一个OpenID
	CreateTime 	消息创建时间
	MsgType 	消息类型，link
	Title 	消息标题
	Description 	消息描述
	Url 	消息链接
	MsgId 	消息id，64位整型 
*/
$link ="<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Title><![CDATA[%s]]></Title>
	<Description><![CDATA[%s]]></Description>
	<Url><![CDATA[%s]]></Url>
	<MsgId>%s</MsgId>
	</xml>";




/**********
事件 xml 模板

**********/
/*
	上报地理位置模板

	ToUserName 	开发者微信号
	FromUserName 	发送方帐号（一个OpenID）
	CreateTime 	消息创建时间 （整型）
	MsgType 	消息类型，event
	Event 	事件类型，LOCATION
	Latitude 	地理位置纬度
	Longitude 	地理位置经度
	Precision 	地理位置精度 
*/
$LOCATION = "<xml>
	<ToUserName><![CDATA[%s]]></ToUserName>
	<FromUserName><![CDATA[%s]]></FromUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[%s]]></MsgType>
	<Event><![CDATA[%s]]></Event>
	<Latitude>%s</Latitude>
	<Longitude>%s</Longitude>
	<Precision>%s</Precision>
	</xml>";