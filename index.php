<?php
define("SP_PATH",dirname(__FILE__)."/SpeedPHP");
define("APP_PATH",dirname(__FILE__));
define("DM_UNIV","upload/univ/");
define("DM_NEWS","upload/news/");
define("DM_OFFER","upload/offer/");
define("DM_CSV","upload/csv/");
define("WEBSITE","指南者教育");
define("LOGOURL", "tpl/bigshow/img/favicon.ico");

$spConfig = array(
	"db" => array( // 数据库设置
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'way_db_35',
		'prefix' => 'tb_',
	),
	'mode' => 'debug', // 部署模式
	'include_path'=>array(
		APP_PATH.'/plug',
	),
	'view' => array(
		'enabled' => TRUE, // 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/tpl', // 模板目录
			'compile_dir' => APP_PATH.'/tmp', // 编译目录
			'cache_dir' => APP_PATH.'/tmp', // 缓存目录
			'left_delimiter' => '<{',  // smarty左限定符
			'right_delimiter' => '}>', // smarty右限定符
		),
	),
	'launch' => array( 
		 'router_prefilter' => array( 
			array('spAcl','mincheck') // 开启有限的权限控制
			// array('spAcl','maxcheck') // 开启强制的权限控制
		 )
	 ),
	 'ext' => array( // 扩展设置
	 	'spAcl' => array( // acl扩展设置
	 		 
	 		// 在acl中，设置无权限执行将user类的acljump函数
	 		'prompt' => array("user", "acljump"),
	 	), 
	 )
);

require(SP_PATH."/SpeedPHP.php");
import('md5password.php');
import(APP_PATH.'/include/keywords.php');
spRun(); // SpeedPHP 3新特性