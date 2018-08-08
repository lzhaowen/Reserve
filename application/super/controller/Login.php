<?php
namespace app\super\controller;
use think\Controller;
use think\Session;
use think\captcha\Captcha;

class Login extends controller{
	//跳转登录页面
	function index(){	
		if(Session::get('ad_uid')){
			$this->success("你已登录，正在跳转到后台管理",'index/index');		
		}else{
			return $this->fetch();		
		}
	}
	
	
	//登录
	function login(){	
		$data = [
			"ad_uid"=>input("ad_uid"),
			"ad_password"=>input("ad_password"),
			"captcha"=>input("captcha"),
		];
		
		//返回值，0为输入为空 ，1为验证码错误，2为账号密码错误，3为登录成功
		
		//检查输入的是否有空
		foreach($data as $input){
			if($input==""||$input==null){
				return 0;	
			}
		}
		
		//检验验证码
		$captcha = new Captcha();
		if(!$captcha->check(input('captcha'))){
			return 1;
		}	
		
		//查询输入的学号的信息
		$info = db('admin')->where('ad_uid',input('ad_uid'))->find();
		if($info){
			//检查学号密码是否正确
			if($info['ad_password']==md5(input('ad_password'))){
				Session::set('ad_uid',input('ad_uid'));
				return 3;
			}
		}
		return 2;
	}
	
	
	
	//跳转找回密码页面
	function findPassword(){
		return $this->fetch();
	}
	
	//根据学号返回问题
	function returnProblem(){
		//填写为空
		if(input('captcha')==""||input('ad_uid')==""||input('captcha')==null||input('ad_uid')==null){
			return "填写内容不能为空";
		}
		
		//检验验证码
		$captcha = new Captcha();
		if(!$captcha->check(input('captcha'))){
			return "验证码错误";			
		}
		
		//验证学号是否存在
		$find=db('admin')->where('ad_uid',input('ad_uid'))->find();
		if(!$find){
			return "学号不存在";			
		}else{
			return [$find['ad_problem']];
		}
		
		
	}
	
	//验证学号与答案是否匹配
	function validate_answer(){
		//填写为空
		if(input('ad_answer')==""||input('ad_uid')==""||input('ad_answer')==null||input('ad_uid')==null){
			return "填写内容不能为空";
		}
		//验证输入的答案
		if(input('ad_uid')&&input('ad_answer')){
			$info = db('admin')->where('ad_uid',input('ad_uid'))->find();
			if($info['ad_answer']==input('ad_answer')){
				return 1;
			}else{
				return "答案不正确";
			}
		}					
	}
	
	//设置新密码
	function new_password(){
		//密码正则
		$regexp='/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/';
		//填写为空
		if(input('ad_password')==""||input('ad_password1')==""||input('ad_uid')==""||input('ad_answer')==""||input('ad_password')==null||input('ad_password1')==null||input('ad_uid')==null||input('ad_answer')==null){
			return "填写内容不能为空";
		}
		
		if(input('ad_uid')&&input('ad_password')&&input('ad_password1')&&input('ad_answer')){
			//验证密码是否一致
			if(input('ad_password')!==input('ad_password1')){
				return "输入的密码不一致";
			//验证密码格式
			}else if(preg_match($regexp,input('ad_password'))==0){
				return "密码由6-16位数字和字母的组合";	
			//修改密码			
			}else{
				$info = db('admin')->where('ad_uid',input('ad_uid'))->find();
				if($info['ad_answer']==input('ad_answer')){
					db('admin')->where('ad_uid',input('ad_uid'))->update(['ad_password'=>md5(input('ad_password'))]);
					return 1;
				}
			}		
		}				
	}
	
	function changeSuccess(){
		$this->success('密码修改成功','login/index');
	}
}
?>
