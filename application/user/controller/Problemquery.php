<?php
namespace app\user\controller;
use think\Controller;
use app\user\model\ProblemLogic;

class ProblemQuery extends Controller{
	public function index(){
		$ProblemLogic = new ProblemLogic();
		list($pro_info, $pro_pages) = $ProblemLogic->getpr_page_data(10);	
		$this->assign('pro_list',json_encode($pro_info));		
		$this->assign('pro_pages',json_encode($pro_pages));		
		
		$page = input('page');//判断是否有切换分页
				
		//如果有切换分页判断是切换哪个分页，然后返回新列表，如果没有切换分页，则首次打开		
		if(!$page){
			return $this -> fetch();			
		}else{
			$this->success([$pro_info,$pro_pages]);
		}
	}
	
	public function query(){
		$ProblemLogic = new ProblemLogic();
		list($pro_info, $pro_pages) = $ProblemLogic->getprquery_page_data(10);	
		$this->assign('pro_list',json_encode($pro_info));		
		$this->assign('pro_pages',json_encode($pro_pages));		
		$this->success([$pro_info,$pro_pages]);
	}
}
?>