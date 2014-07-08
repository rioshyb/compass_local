<?php

class myax extends spController
{

	function compute(){
		// 接收ajax提交的num值
		$num = $this->spArgs('num');
		// 返回（显示）num的平方
		echo $num * $num;
	}
	//根据国家返回学校select
	function univ(){
		$ctry = $this->spArgs("ctry"); //接受国家参数
		$university = spClass("university");
		$condition = array('ctry_univ'=>$ctry);
		$this->university = $university->findAll($condition);
		$this -> display('admin/ajuniv.html');  
	}
	//根据学校返回学院select
	function schl(){
		$univ = $this->spArgs("univ"); //接受学校参数
		$school = spClass("school");
		$condition = array('univ_schl'=>$univ);
		$this->school = $school->findAll($condition);
		$this -> display('admin/ajschl.html');  
	}
	//根据专业大类返回专业小类select
	function clss(){
		$cate = $this->spArgs("cate"); //接受专业大类参数
		$classes = spClass("classes");
		$condition = array('cate_clss'=>$cate);
		$this->classes = $classes->findAll($condition);
		$this -> display('admin/ajclss.html');  
	}
}