<?php
class news extends spModel
{
	var $pk = "id_news"; // 每个留言唯一的标志，可以称为主键
	var $table = "news"; // 数据表的名称
		
	var $verifier = array( // 留言内容验证规则
		"rules" => array( 
			'title' => array(
				'notnull' => TRUE,
				'minlength' => 3,
				'maxlength' => 30
			),
		),
		"messages" => array( 
			'title' => array(
				'notnull' => "标题不能为空",
				'minlength' => "标题必须大于3个字符",
				'maxlength' => "标题必须小于30个字符"
			),
		)
	);
	
	// 请注意，这里我们覆盖了spModel的create函数，以方便我们对新增的记录加入时间与用户名
}