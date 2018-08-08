<?php
namespace app\socket\controller;
use think\Controller;
use think\Session;

class Chat extends Controller{
	//登陆聊天室
	public function chat(){
		//已登陆
		if(Session::get('username')){
			$username = Session::get('username');
			$this->assign('username',json_encode($username));
			return $this->fetch();			
		//未登陆	
		}else{
			$this->error('请登陆后再进入咨询室','Chatlogin/chatlogin');
		}			
	}	
	
	//退出登陆
	public function quit(){
		Session::delete('username');
	}
}
?>
