<?php
namespace app\super\model;	
use think\Model;

class reservePageLogic extends Model{
	
	
	//获取分页的预约名单
	public function getre_page_data($page){
		//获取预约名单
		$reserveList=db('reserve')
					->alias('a')
					->join('admin b','a.aid = b.ad_uid','left')		
					->order('time desc')
					->paginate($page)
					->toArray();
		
		$order_pages = $reserveList;//分页的信息
		unset($order_pages['data']);
		$order_list = $reserveList['data'];//分页的预约名单
		
		return [$order_list, $order_pages];
	}
	
	//获取分页的管理员名单
	public function getad_page_data($page){
		//获取预约名单
		$adminList=db('admin')
					->alias('a')
					->field('a.ad_id,a.ad_name,a.ad_uid,a.ad_classname,a.ad_phone,a.ad_phone,a.ad_power,a.ad_wx,a.ad_reservenum')
					->order('ad_reservenum desc')
					->paginate($page)
					->toArray();
					
		$admin_pages = $adminList;//分页的信息
		unset($admin_pages['data']);
		$all_info = $adminList['data'];//分页的管理员名单
	
		return [$all_info, $admin_pages];
	}
	
	//获取问题列表
	public function getpr_page_data($page){
		$problemList=db('problem')
					->order('problem_id desc')
					->paginate($page)
					->toArray();
					
		$pro_pages = $problemList;//分页的信息
		unset($pro_pages['data']);
		$pro_info = $problemList['data'];//分页的问题	
		return [$pro_info, $pro_pages];
	}
	
	
}

?>