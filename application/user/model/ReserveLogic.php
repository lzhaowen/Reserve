<?php
namespace app\user\model;
use think\captcha\Captcha;
use think\Model;

class ReserveLogic extends Model{
	
	//获取当天的学期周，星期，是否开放预约
	function getTodayStatue(){
		//获取总体设置
		$setting = db('setting')->select()[0];	
		$nowWeek = date ("w");
		$nowTime = date ("Y-m-d H:m:s");
		
		//计算开学第几周
		$startTime = strtotime($setting['startTime']);
		$schoolWeek = ceil((time()-$startTime)/60/60/24/7);
		
		//每天开放预约时间		
		$beginTime = date ("Y-m-d ").$setting["beginTime"];
		$endTime = date ("Y-m-d ").$setting["endTime"];
		
		//每学期开放预约的学期周
		$beginSchoolWeek = $setting["beginSchoolWeek"];
		$endSchoolWeek = $setting["endSchoolWeek"];
		
		//每周开放预约的天
		$beginWeek = $setting["beginWeek"];	
		$endWeek = $setting["endWeek"];
				
		
		
		//当天暂停预约		
		if($setting['banReserve']==0){
			$todayStatue=2;
		}else{		
			//符合预约时间才开放
			if($nowWeek>=$beginWeek && $nowWeek<=$endWeek &&  $schoolWeek>=$beginSchoolWeek && $schoolWeek<=$endSchoolWeek && strtotime($nowTime) >=strtotime($beginTime) && strtotime($nowTime)<=strtotime($endTime)){
				$todayStatue = 1;//当天可预约
			}else if($nowWeek>=$beginWeek && $nowWeek<=$endWeek &&  $schoolWeek>=$beginSchoolWeek && $schoolWeek<=$endSchoolWeek && strtotime($nowTime)>=strtotime($endTime)){
				$todayStatue = 3;//当天可预约,但超出预约时间
			}else{
				//不在预约时间范围内
				$todayStatue = 0;
			};
		}
					
		$data = [
				"nowWeek"=>$nowWeek,//今天星期几
				"todayStatue"=>$todayStatue,//现在是否能预约
				"schoolWeek"=>$schoolWeek,//现在的学期周
				"beginSchoolWeek"=>$beginSchoolWeek,//每学期从第几个学期周开始预约
				"endSchoolWeek"=>$endSchoolWeek,//每学期从第几个学期周结束预约	
				"beginWeek"=>$beginWeek,//每个星期的星期几开始预约
				"endWeek"=>$endWeek,//每个星期的星期几结束预约
				"beginTimeH"=>$setting["beginTime"],//每天预约的开始时间
				"endTimeH"=>$setting["endTime"],	//每天预约的结束时间				
				"sbeginTime"=>$setting["sbeginTime"],	//维修的开始时间			
				"sendTime"=>$setting["sendTime"],	//维修的结束时间				
				"seat"=>$setting["seat"],	//维修地点			
				];
				
		return $data;
	}
	
	//判断是否重复预约，和当天预约的人数是否超过10
	function checkRepeatAndNum($data){
		
		//查询当天预约名单
		$reserveList=db('reserve')->whereTime('time','today')->select();
		$setting=db('setting')->select();
		
		//$otherStatue，0为预约失败，1为预约成功，2为重复预约，3为学号为空,4为验证码错误
		
		
		//判断输入内容是否有空
		foreach($data as $input){
			if($input==""||$input==null){
				$otherStatue = 3;
				return $otherStatue;
			}
		}
			
		//检验验证码
		$captcha = new Captcha();
		if(!$captcha->check($data['captcha'])){
			$otherStatue = 4;	
			return $otherStatue;					
		}	
			
		//判断当天预约是否有重复的
		foreach($reserveList as $list){		
			if($list["uid"]==$data['uid']){
				$validataUid=true;
			}
		};
		

		//判断是否重复预约，		
		if(isset($validataUid)){
			$otherStatue = 2;
		}else{
			$reserveNum=count($reserveList);
			$otherStatue = 1;
			//判断是否预约是否超过最大预约人数
			if($reserveNum>=$setting[0]['maxNum']){
				$otherStatue = 0;
			}
		}	
				
		return $otherStatue;
	}
	
	function hideName($reserveList){
		//将名字隐藏第二个字
		for($i=0;$i<count($reserveList);$i++){
			$name="";
//			$reserveList[$i]['name']=preg_replace(mb_substr($reserveList[$i]['name'],1,1),"＊",$reserveList[$i]['name']);
			for($j=0;$j<strlen($reserveList[$i]['name']);$j++){
				if($j==1){
					$name .="＊";
				}else{
					$name .=mb_substr($reserveList[$i]['name'],$j,1);					
				}
			}
			$reserveList[$i]['name']=$name;
		}
		return $reserveList;
	}
}
?>