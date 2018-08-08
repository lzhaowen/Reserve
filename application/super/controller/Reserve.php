<?php
namespace app\super\controller;
use think\Controller;

class Reserve extends Controller{
	//完成维修
	public function finish()	{	
		$id = input('id');		
		$ad_uid = input('ad_uid');
		db('reserve')->where('id',$id)->update(['statue' => 1,'aid'=>$ad_uid]);	
		db('admin')->where('ad_uid',$ad_uid)->setInc('ad_reservenum');
	}
	
	//取消完成
	public function cancel()	{	
		$id = input('id');		
		$ad_uid = input('ad_uid');		
		db('reserve')->where('id',$id)->update(['statue' => 0,'aid'=>null]);	
		db('admin')->where('ad_uid',$ad_uid)->setDec('ad_reservenum');		
	}
	
	//删除预约
	public function deleteReserve()	{	
		$id = input('id');	
		$aid = db('reserve')->where('id',$id)->find()['aid'];
		print_r($aid);	
		if($aid!=='' ||$aid!==null){
			db('admin')->where('ad_uid',$aid)->setDec('ad_reservenum');				
		}
		db('reserve')->where('id',$id)->delete();	
	}
}
?>