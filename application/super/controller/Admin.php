<?php
namespace app\super\controller;
use think\Controller;

class Admin extends Controller{
	//删除管理员
	public function deleteAdmin()	{	
		$ad_id = input('ad_id');		
		db('admin')->where('ad_id',$ad_id)->delete();	
	}
	
	//升级为超级管理员	
	public function upAdmin()	{	
		$ad_id = input('ad_id');		
		db('admin')->where('ad_id',$ad_id)->update(['ad_power' => 1]);	
	}
	
	//降级为普通管理员	
	public function downAdmin()	{	
		$ad_id = input('ad_id');		
		db('admin')->where('ad_id',$ad_id)->update(['ad_power' => 0]);		
	}
	
}
?>