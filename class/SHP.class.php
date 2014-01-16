<?php

/**
 * 商讯短信
 * 短信发送处理类
 * 网址：http://www.139000.com/index.asp
 * 接口文档地址：http://www.139000.com/mingshangtongAPI/index.asp
 */

class SHP { 
		
	private $name;		//账号名
	private $pwd;		//密码

	public function __construct($name,$pwd) {
		$this->name = $name;
		$this->pwd = $pwd;
	}
	
	/**
	 * 发送接口
	 * @param num(11) $phone		电话号码。$phones= array(1371345678,18613245678);
	 * @param string $msg				短信消息
	 * @param string $time				定时发送，格式：YYYYMMDDHHMM
	 * @return array		
	 */
	public function send($phone,$msg,$time='') {
		
		if (is_array($phone)) {
			$phone = implode(',',$phone);
		}
		
		if (!empty($time)) $time = date('YYYYMMDDHHMM',$time);
		
		$msg = urlencode(@iconv('UTF-8', 'GB2312', $msg));		//字体编码转换
		//请求地址
		$url = "http://203.81.21.34/send/gsend.asp?name=$this->name&pwd=$this->pwd&dst=$phone&msg=$msg&time=$time";
		$string = @file_get_contents($url);		//获取服务器发送的状态：结果为：（num=2&success=1393710***4,1393710***5&faile=&err=发送成功&errid=0 ）

		/* 对数据进行数组处理 */
		$arr1 = explode('&',$string);		
		$arr2 = array();
		foreach ($arr1 AS $key=>$val) {
			$tmp_arr = explode('=',$val);
			$arr2[$tmp_arr[0]] =  $tmp_arr[1];
		}
		
		/* 处理返回状态 */
		if ($arr2['num'] > 0) {
			$result['status'] = true;
			$result['info']['success'] = $arr2['success'];		//成功的号码
			$result['info']['faile'] = $arr2['faile'];					//失败的号码
			$result['msg'] = "短信发送成功！";
		} else {
			$result['status'] = false;
			$result['info'] = $arr2['faile'];								//失败的号码
			$result['msg'] = "发送失败，请重新尝试！";
		}
		return $result;
	}
	
}




?>