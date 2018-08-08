<?php
namespace app\super\controller;
use think\Controller;
use think\captcha\Captcha;


class Myself extends Controller{
	public function updateMyself(){
		
		//检验验证码
		$captcha = new Captcha();
		if(!$captcha->check(input('captcha1'))){
			$this->error('更新失败，验证码错误','index/index');	
		}
		
		$data = [
				'ad_name'=>input('ad_name'),
				'ad_classname'=>input('ad_classname'),
				'ad_phone'=>input('ad_phone'),
				'ad_wx'=>input('ad_wx'),
				'captcha1' => input("captcha1"),	
				];
				
		//检验填写是否有空
		foreach($data as $input){
			if($input==""||$input==null){
				$this->error('更新失败，填写内容不能为空','index/index');
			}
		};
		
		unset($data['captcha1']);
		
		db('admin')
		->where('ad_id',input('ad_id'))
		->update($data);
		$this->success('更新成功','index/index');
		
	}
}
?>