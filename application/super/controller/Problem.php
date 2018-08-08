<?php
namespace app\super\controller;
use think\Controller;
use app\super\model\reservePageLogic;
class Problem extends Controller{
	public function add_problem(){	
		if(input('add_problem')!==''&&input('add_answer_list')!==''){
			$data=[
				'problem' => input('problem'),
				'answer' => input('answer_list'),
			];	
		}			
		db('problem')->insert($data);
		return db('problem')->getLastInsID();
	}
	
	public function del_problem(){
		db('problem')->where('problem_id',input('problem_id'))->delete();
		
		$reservePageLogic = new reservePageLogic();
		list($pro_info, $pro_pages) = $reservePageLogic->getpr_page_data(10);	
		$this->success([$pro_info,$pro_pages]);		
	}
	
	public function updata_problem(){
		db('problem')->where('problem_id',input('problem_id'))->update(['problem'=>input('problem')]);
	}
	
	public function updata_answer(){
		db('problem')->where('problem_id',input('problem_id'))->update(['answer'=>input('answer')]);
	}
}
?>