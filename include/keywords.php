<?php
function panduan_url()
{
	if($_COOKIE['Cm']==1){
		include "tpl/bigshow/bottom_cm.html";
		exit();
	}elseif($_SERVER['HTTP_REFERER']==""){
	    include "tpl/bigshow/bottom.html";
        exit();
	}else{
		$host=parse_url($_SERVER['HTTP_REFERER']);
		//判断访问来路的主机{//百度或者360搜索指南者教育关键词的用户设置users
	    if($host['host']=="www.baidu.com"||$host['host']=="pos.baidu.com"||$host['host']=="cros.baidu.com"||$host['host']=="www.so.com"||$host['host']=="so.360.cn"){
	    	if (strstr(urldecode($_SERVER['HTTP_REFERER']),"指南者教育")||strstr(urldecode($_SERVER['HTTP_REFERER']),"%E6%8C%87%E5%8D%97%E8%80%85%E6%95%99%E8%82%B2")){
	    		include "tpl/bigshow/bottom.html";
                exit();
		    }else{
		    	setcookie("Cm", 1, time()+3600);	
		    	include "tpl/bigshow/bottom_cm.html";
		    	exit();
		    }
	    //}
	    //推广用户或者其他用户设置uid
	    //else if($host['host']=="www.compassedu.hk"){
	    //    include "tpl/bigshow/bottom.html";
	    //    exit();
	    }else{
	    	include "tpl/bigshow/bottom.html";
	    	exit();
	    }
	}
}
// 将get_cookie注册到模板中使用，所以需要加入getcookie函数进行一次封装 
function v_url()
{
	return panduan_url();
}
spAddViewFunction('geturl','v_url');
?>