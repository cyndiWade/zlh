<?php
header('Content-Type:text/html;charset=utf-8');
class wx{
	
	private $appid  = '';
	private $secret = '';

    /*
	 *设置 appid $secret
	 */
	function __construct($appid,$secret){
		$this->appid  = $appid ;
		$this->secret = $secret ;
	}

	/*------------------------------------------------------ */
	//-- 获得微信的 token   get_token()
	/*------------------------------------------------------ */
	public function get_token(){
		 $token = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->secret);
		 $t = json_decode($token);
		 return $t->access_token;
	}

	/*------------------------------------------------------ */
	//-- 删除微信的菜单  del_menu()
	/*------------------------------------------------------ */
	public function del_menu(){
      $token = $this->get_token();
	  return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$token);
	}
	/*------------------------------------------------------ */
	//-- 微信的分组  get_group()
	/*------------------------------------------------------ */
	public function get_group(){
	  $token = $this->get_token();
	  return file_get_contents("https://api.weixin.qq.com/cgi-bin/groups/get?access_token=".$token);
	}
	/*------------------------------------------------------ */
	//-- 微信的创分组的方法  creat_group($data)
	/*------------------------------------------------------ */	
	public function create_group($data){
	   $url ='https://api.weixin.qq.com/cgi-bin/groups/create?access_token=';
	   return $this->post_method($data,$url);
	}
	/*------------------------------------------------------ */
	//-- 微信的修改分组的方法  update_group($data)
	/*------------------------------------------------------ */	
	public function update_group($data){
	   $url ='https://api.weixin.qq.com/cgi-bin/groups/update?access_token=';
	   return $this->post_method($data,$url);	
	}


	/*------------------------------------------------------ */
	//-- 移动用户的分组的  remove_user_to_group($data)
	/*------------------------------------------------------ */	
	public function remove_user_to_group($data){
	   $url ='https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token='; // 移动用户的分组
	   return $this->post_method($data,$url);	
	}


    /*------------------------------------------------------ */
	//-- 获得用户的信息  get_user_info($openid)
	/*------------------------------------------------------ */	
    public function get_user_info($openid){
	  $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->get_token()."&openid=".$openid;
	  return file_get_contents($url);
	}
    
	public function get_user_list($NEXT_OPENID){
		$url ="https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->get_token();
	    if($NEXT_OPENID){
		  $url .= "&next_openid=".$NEXT_OPENID;
		}
		return file_get_contents($url);
	}

	/*------------------------------------------------------ */
	//-- 微信的创建菜单的方法  creat_menu($data)
	/*------------------------------------------------------ */	
	public function create_menu($data){
       $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';
	   return $this->post_method($data,$url);	
	}
	
    /*------------------------------------------------------ */
	//-- 获得创建二维码的ticket  get_ticket($data,$url)
	/*------------------------------------------------------ */
    public function get_ticket($data){
	
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=';
		$result = json_decode($this->post_method($data,$url));
		return $result->ticket;
	}
    

	public function get_two_code($data){
		return $url ="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".UrlEncode($this->get_ticket($data));
		return $this->PE_img_by_path($url);
	}
    /*
	 * 网页授权的 token and openid 
	 */
	public function get_token_openid($code){
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx06529b330cb53393&secret=a807b2be47b0a7f11ffea8d1a8d4dc91&code=".$code."&grant_type=authorization_code";
		$result = file_get_contents($url);
		$t = json_decode($result);
		$arr = array();
		$t->access_token;
		$t->openid;
		return $arr('access_token'=>$t->access_token,'openid'=>$t->openid);
	
	}
    public function get_ver_user_info($code){
		$arr = $this->get_token_openid($code);
		$get_user_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
		return file_get_contents($url);

	}
    public 
function PE_img_by_path($PE_imgpath = "")
{
	if (!empty($PE_imgpath)) {
		$PE_imgarray = pathinfo($PE_imgpath);
		print_R($PE_imgarray);
		$iconcontent = file_get_contents($PE_imgpath);
		header("Content-type: image/jpg" );
		header("Content-length:". strlen($iconcontent));
		echo $iconcontent;
		die(0);
	}
	return false;
}

	/*------------------------------------------------------ */
	//-- 微信的post方法  post_method($data,$url)
	//
	/*------------------------------------------------------ */	
    public function post_method($data,$url){
		$ch = curl_init();
		$url = $url.$this->get_token();
		curl_setopt($ch, CURLOPT_URL,$url);  
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if (curl_errno($ch)) {
			 return curl_error($ch);
		}
		curl_close($ch);
		return $tmpInfo;	
	}


    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

}

?>