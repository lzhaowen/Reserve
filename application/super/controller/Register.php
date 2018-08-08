<?php
namespace app\super\controller;
use think\Controller;
use think\captcha\Captcha;

class Register extends Controller{
	//跳转注册页
	public function index(){
		return $this->fetch();
	}
	
	
	//注册
	public function register(){	
		
		// result,0为学号已存在，1为可注册，2为秘钥不正确，3为输入有空，4为验证码错误
		
		$data = [		
			'ad_name' => input('ad_name'),		
			'ad_uid' => input('ad_uid'),
			'ad_password' => input('ad_password'),			
			'ad_problem' => input('ad_problem'),			
			'ad_answer' => input('ad_answer'),						
			'ad_classname' => input('ad_classname'),
			'ad_phone' => input('ad_phone'),
			'ad_wx' => input('ad_wx'),
			'captcha' => input('captcha'),
		];
		
		//检查输入的是否有空
		foreach($data as $input){
			if($input==""||$input==null){
				$result = 3;
				$this->assign('result',$result);					
				return $this->fetch('resultpage');					
			}
		}
		unset($data['captcha']);
		
		//检验验证码
		$captcha = new Captcha();
		if(!$captcha->check(input('captcha'))){
			$result = 4;
			$this->assign('result',$result);					
			return $this->fetch('resultpage');
		}	
				
		//检查输入的注册秘钥是否正确
		$key = db('setting')->find()['key'];
		if($key!==input('key')){
			$result = 2;	
		}else{
			//秘钥正确，检查是否重复注册
			$exist = db('admin')->where('ad_uid',input('ad_uid'))->find();	
			if(isset($exist)){
				//已存在，不可注册
				$result = 0;			
			}else{
				//可注册
				$result = 1;		
				$data['ad_password']	=md5(input('ad_password'));	
				db('admin')->insert($data);		
			}
		}		
			
		$this->assign('result',$result);
		return $this->fetch('resultpage');
			
	}
	
	
	
	
	
}
?>