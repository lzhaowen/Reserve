<?php
namespace app\socket\controller;
use think\Controller;
use think\Session;
use think\captcha\Captcha;

class Chatlogin extends Controller {
	//打开页面登陆
	public function chatlogin() {
		if (Session::get('username')) {
			//已经登陆过，直接跳转咨询室
			$this -> assign('username', Session::get('username'));
			$this -> success('你已经登陆过，正在跳转到咨询室', 'Chat/chat');
		} else {
			//没有登陆过，跳转登陆页
			return $this -> fetch();
		}
	}

	//验证登陆
	public function save() {
		$captcha = new Captcha();
		$captchain = input('captcha');
		$classname = input('classname');
		$username = input('username');
		//验证验证码
		if ($captcha -> check($captchain)) {
			$str = $classname . "-" . $username;
			Session::set('username', $str);
			return 'ok';
		} else {
			return 'captcha';
		}
	}
	
	//管理员登陆聊天室
	public function adminchat(){
		$username = input('username');
		$str = '【维修员】'.$username;
		Session::set('username', $str);
		return 'ok';
	}

}
?>