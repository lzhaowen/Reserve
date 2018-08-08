<?php
namespace app\super\controller;
use think\Controller;
use think\captcha\Captcha;

class Setting extends Controller{
	
	
	//开启、暂停预约
	public function updateBanReserve()	{	
		$changeStatue = input('changeStatue');
		if($changeStatue==1){
			db('setting')->where('id',1)->update(['banReserve' => 0]);					
		}else{
			db('setting')->where('id',1)->update(['banReserve' => 1]);							
		}	
		return $changeStatue;
	}
	
	//更新系统设置
	public function updateAll(){
		
		//检验验证码
		$captcha = new Captcha();
		if(!$captcha->check(input('captcha2'))){
			$this->error('更新失败，验证码错误','index/index');	
		}
		
		$data = [
			'startTime' => input("startTime"),
			'beginTime' => input("beginTime"),
			'endTime' => input("endTime"),
			'beginSchoolWeek' => input("beginSchoolWeek"),
			'endSchoolWeek' => input("endSchoolWeek"),
			'beginWeek' => input("beginWeek"),
			'endWeek' => input("endWeek"),
			'key' => input("key"),
			'maxNum' => input("maxNum"),
			'sbeginTime' => input("sbeginTime"),
			'sendTime' => input("sendTime"),		
			'captcha2' => input("captcha2"),		
			'seat' => input("seat"),		
		];
		
		//检验填写是否有空
		foreach($data as $input){
			if($input==""||$input==null){
				$this->error('更新失败，填写内容不能为空','index/index');
			}
		};
		
		unset($data['captcha2']);
		db('setting')->where('id',1)->update($data);			
		$this->success('更新成功','index/index');
	}
}
?>