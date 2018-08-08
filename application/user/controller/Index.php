<?php
namespace app\user\controller;
use think\Controller;
use app\common\logic\CommonLogic;
use app\user\model\ReserveLogic;

class Index extends Controller  {
	
	//获取状态逻辑，获取已预约的名单，跳转首页	
	public function index() {	
				
		//获取预约的状态
		$CommonLogic = new CommonLogic();
		$this->assign('reserveStatue',json_encode($CommonLogic->getReserveStatue()));
		
		//获取当天的学期周，星期，预约状态
		$reserveLogic = new ReserveLogic();
		$todayStatue =$reserveLogic->getTodayStatue();
		
		$this->assign('nowWeek',$CommonLogic->getWeek()[$todayStatue["nowWeek"]]);	//今天星期几
		$this->assign('todayStatue',$todayStatue["todayStatue"]);	//现在是否能预约
		$this->assign('schoolWeek',$todayStatue["schoolWeek"]);		//现在的学期周
		
		//获取所有设置的信息
		$this->returnSetting();	
		
		//获取预约名单
		$reserveList=db('reserve')
					->alias('a')
					->join('admin b','a.aid = b.ad_uid','left')
					->whereTime('time','d')
					->field('a.name,a.classname,a.statue,b.ad_name,b.ad_classname,b.ad_phone,b.ad_wx')
					->order('statue asc')
					->select();

		//将名字隐藏第二个字
		$reserveList = $reserveLogic->hideName($reserveList);
		
		$this->assign('reserveList',json_encode($reserveList));
		$reserveNum=count($reserveList);
		$this->assign('reserveNum',json_encode($reserveNum));
		
		//获取预约最大人数
		$maxNum=db("setting")->select();
		$this->assign('maxNum',$maxNum[0]["maxNum"]);
		$queryInfo=null;
		$this->assign('queryInfo',json_encode($queryInfo));
		return $this -> fetch();
	}
	
	//检验预约数是否超过，未超过则储存预约订单
	public function order(){		
		//获取所有设置的信息
		$this->returnSetting();	
		
		$data = [		
				'uid' => input('uid'),
				'classname' => input('classname'),
				'name' => input('name'),
				'phone' => input('phone'),
				'wx' => input('wx'),
				'time' => date("Y-m-d H:i:s"),
				'describe' => input('describe'),			
				'captcha' => input('captcha'),			
			];
		
		//获取预约是否重复
		$reserveLogic = new ReserveLogic();
		$orderStatue = $reserveLogic->checkRepeatAndNum($data);
		//清除验证码
		unset($data['captcha']);
			
		//预约状态为成功，存入数据
		if($orderStatue==1){				
			db('reserve')->insert($data);
		}
				
		$this->assign('orderStatue',json_encode($orderStatue));	
		return $this->fetch();
	}
	
	public function query(){
		$name = trim(input('queryname'))	;
		$queryInfo=db('reserve')
					->alias('a')
					->join('admin b','a.aid = b.ad_uid','left')
					->where('name',$name)
					->field('a.name,a.classname,a.statue,b.ad_name,b.ad_classname,b.ad_phone,b.ad_wx')
					->order('statue desc')
					->select();
		return $queryInfo;
	}
	
	public function returnSetting(){
		//获取当天的学期周，星期，预约状态
		$reserveLogic =new ReserveLogic();
		$todayStatue =$reserveLogic->getTodayStatue();
		$CommonLogic = new CommonLogic();
			
		$this->assign('beginSchoolWeek',$todayStatue["beginSchoolWeek"]);	//每学期从第几个学期周开始预约
		$this->assign('endSchoolWeek',$todayStatue["endSchoolWeek"]);		//每学期从第几个学期周结束预约	
		$this->assign('beginWeek',$CommonLogic->getWeek()[$todayStatue["beginWeek"]]);  //每个星期的星期几开始预约
		$this->assign('endWeek',$CommonLogic->getWeek()[$todayStatue["endWeek"]]);	//每个星期的星期几结束预约
		$this->assign('beginTimeH',$todayStatue["beginTimeH"]);		//每天预约的开始时间
		$this->assign('endTimeH',$todayStatue["endTimeH"]);		//每天预约的结束时间	
		$this->assign('sbeginTime',$todayStatue["sbeginTime"]);	//维修的开始时间
		$this->assign('sendTime',$todayStatue["sendTime"]);	//维修的结束时间		
		$this->assign('seat',$todayStatue["seat"]);	//维修的地点		
	}
}
