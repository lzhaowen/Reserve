<?php
namespace app\super\controller;
use think\Controller;
use app\common\logic\CommonLogic;
use think\Session;
use app\super\model\reservePageLogic;
class Index extends Controller  {	
	
	public function index() {
		//判断是否登录
		$uid = Session::get('ad_uid');
		if($uid){
			//当前管理员信息
			$info = db('admin')
					->alias('a')
					->where('ad_uid',$uid)
					->field('a.ad_id,a.ad_name,a.ad_uid,a.ad_power,a.ad_uid,a.ad_classname,a.ad_phone,a.ad_wx,a.ad_reservenum')
					->find();			
			$this->assign('info',json_encode($info));
			
			$reservePageLogic = new reservePageLogic();
			
			
			$ad_power=0;
			$setting=null;
			$pro_info=null;
			$pro_pages=[];
			//如果当前管理员的权限为1，查询系统设置
			$ad_power= db("admin")->where("ad_uid",$uid)->find()["ad_power"];
			if($ad_power==1){			
				$setting = db("setting")->select()[0];					
				//获取问题分页的信息
				list($pro_info, $pro_pages) = $reservePageLogic->getpr_page_data(10);						
			}

			$this->assign('setting',json_encode($setting));				
			$this->assign('ad_power',$ad_power);			
			
			
			//预约订单信息		
			//获取预约的状态
			$CommonLogic = new CommonLogic();
			$this->assign('reserveStatue',json_encode($CommonLogic->getReserveStatue()));
			$this->assign('powerStatue',json_encode($CommonLogic->getPower()));
			$this->assign('getBanReserve',json_encode($CommonLogic->getBanReserve()));
			$this->assign('getWeek',json_encode($CommonLogic->getWeek()));
						
		
			//获取预约名单分页的信息
			list($reserveList, $order_pages) = $reservePageLogic->getre_page_data(10);
			//获取管理员名单分页的信息
			list($all_info, $admin_pages) = $reservePageLogic->getad_page_data(10);
			
			
			$this->assign('reserveList',json_encode($reserveList));		//预约列表						
			$this->assign('order_pages',json_encode($order_pages));		//预约分页的信息			
			
			$this->assign('all_info',json_encode($all_info));		//管理员信息
			$this->assign('admin_pages',json_encode($admin_pages));		//管理员分页的信息	
			
			$this->assign('pro_info',json_encode($pro_info));		//问题信息
			$this->assign('pro_pages',json_encode($pro_pages));		//问题分页的信息	
			
				
			$page = input('page');//判断是否有切换分页
			$page_id = input('page_id');//获取切换的是哪个分页 ，1为预约名单，2为管理员名单,3为问题列表
			
			//如果有切换分页判断是切换哪个分页，然后返回新列表，如果没有切换分页，则首次打开		
			if(!$page){
				return $this -> fetch();			
			}else if($page&&$page_id==1){
				$this->success([$reserveList,$order_pages]);
			}else if($page&&$page_id==2){
				$this->success([$all_info,$admin_pages]);					
			}else if($page&&$page_id==3){
				$this->success([$pro_info,$pro_pages]);					
			}		
							
		}else{
			$this->error('请登录','login/index');
		}
		
	}
	
	
	//注销账号
	public function exitUid(){
		Session::delete('ad_uid');
		$this->success('退出成功','login/index');
	}
	
	

}