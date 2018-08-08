<?php
	namespace app\common\logic;
	use think\Model;
	
	class CommonLogic extends Model{
		//返回预约的状态
		public function getReserveStatue(){
			return [
				"0"=>"待维修",
				"1"=>"已维修",
				"2"=>"逾期未至"
			];
		}
		
		//返回星期
		public function getWeek(){
			return [
				"0"=>"日",
				"1"=>"一",
				"2"=>"二",
				"3"=>"三",
				"4"=>"四",
				"5"=>"五",
				"6"=>"六",				
			];		
		}
		
		//返回权限
		public function getPower(){
			return[
				"0"=>"普通维修员",
				"1"=>"超级维修员",
			];
		}
		
		//返回是否暂停预约
		public function getBanReserve(){
			return[
				"0"=>"暂停预约",
				"1"=>"开启预约",
			];
		}
	}
?>