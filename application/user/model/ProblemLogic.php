<?php
namespace app\user\model;
use think\Model;

class ProblemLogic extends Model{
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
	
	//获取查询问题列表
	public function getprquery_page_data($page){
		$problemList=db('problem')
					->where('problem','like',input('problem'))
					->paginate($page)
					->toArray();
							
		$pro_pages = $problemList;//分页的信息
		unset($pro_pages['data']);
		$pro_info = $problemList['data'];//分页的问题	
		return [$pro_info, $pro_pages];
	}
}

?>