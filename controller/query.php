<?php
class query extends spController
{
	function dpach(){
		$type = $this->spArgs('type');
		$keywords = urldecode($this->spArgs("kwds"));
		//返回留学资讯，成功案例
		if($type=="succ" || $type=="info" || $type=="help"){
			
			if($keywords !=""){
				$news = spClass("news"); 
                
                $conditions = "select `ctry_univ`,`id_clss`,tb_news.`id_univ`,`appdate_new`,`keywords_news`,`type_news`,`time_news`,`content_news`,`title_news`,`id_news`  
					from tb_news,tb_university where tb_news.id_univ = tb_university.id_univ 
                    and title_news like '%$keywords%' and type_news = '$type'";
				$this->results = $news->spPager($this->spArgs('page', 1), 10)->findSql($conditions);
        		$this->pager = $news->spPager()->getPager();
        		if($type=="info"){
        			$this->position = "留学资讯";
        		}else if($type=="succ"){
        			$this->position = "成功案例";
        		}else{
        			$this->position = "帮助中心";
        		}
        		$this->type = $type;
        		$this->kwds = $keywords;
       			$this->display("bigshow/q_news.html");
			}else{
				$this->display("bigshow/q_news.html");
			}
       	//返回学校名	
		}else if($type=="univ"){
			if($keywords !=""){
				$university = spClass("university"); 
        		$conditions = " cname_univ like '%$keywords%' ";
				$this->results = $university->spPager($this->spArgs('page', 1), 15)->findAll($conditions);
        		$this->pager = $university->spPager()->getPager();
        		$this->position = "学校目录";
        		$this->type = $type;
        		$this->kwds = $keywords;
       			$this->display("bigshow/q_univ.html");
			}else{
				$this->display("bigshow/q_univ.html");
			}
		}else{
			$major = spClass("major"); 
			if($keywords !=""){
        		$conditions = "select cname_ctry,id_majr,cname_majr,ename_majr,cname_univ,ename_univ
							from tb_major,tb_university,tb_country  
							where univ_majr = id_univ
							and ctry_univ = id_ctry 
				        	and cname_majr like '%$keywords%' ";
				$this->results = $major->spPager($this->spArgs('page', 1), 15)->findSql($conditions);
        		$this->pager = $major->spPager()->getPager();
        		$this->position = "专业筛选";
        		$this->type = $type;
        		$this->kwds = $keywords;
       			$this->display("bigshow/q_majr.html");
			}else{
				$this->display("bigshow/q_majr.html");
			}
		}
	}

	//留学资讯搜素
	function infos(){
		$keyword = urldecode($this->spArgs("kwds"));
		$keyword = spClass("comn")->tag_replace($keyword);
		if($keyword){
			$sqls = "";
			$keywords = explode(" ",$keyword);	
			foreach($keywords as $value){ 
				$sqls = $sqls." title_news like '%". $value."%' and"; 
			} 

			$news = spClass("news"); 
                
            $conditions = "select *	from tb_news where ".$sqls." type_news = 'info' order by `time_news` desc";
			$this->results = $news->spPager($this->spArgs('page', 1), 10)->findSql($conditions);
        	$pager = $news->spPager()->getPager();

			$this->pager = $pager;
			$this->curpage = floor($pager['current_page']/10)*10;
        		
        	$this->position = "留学资讯";
        	$this->kwds = $keyword;
       		$this->display("bigshow/info_search.html");
		}else{
			$this->error("输入内容为空或含有非法字符，请重新输入，谢谢！");
		}			
	}

	//成功案例搜索
	function succs(){
		$keyword = urldecode($this->spArgs("kwds"));
		$keyword = spClass("comn")->tag_replace($keyword);
		if($keyword !=""){
			$sqls = "";
			$keywords = explode(" ",$keyword);	
			foreach($keywords as $value){ 
				$sqls = $sqls." title_news like '%". $value."%' and"; 
			} 

			$news = spClass("news"); 
                
            $conditions = "select `ctry_univ`,tb_news.`id_univ`,`keywords_news`,`type_news`,`time_news`,`content_news`,`title_news`,`id_news`  
					from tb_news,tb_university where tb_news.id_univ = tb_university.id_univ 
                    and ".$sqls." type_news in ('acpt','succ') order by `time_news` desc";
			$succ = $news->spPager($this->spArgs('page', 1), 6)->findSql($conditions);
        	
        	for($i=0;$i<count($succ);$i++){
        		if(strstr($succ[$i]['content_news'], 'spptest-upload')){
					$succ[$i]['img'] = substr(strchr($succ[$i]['content_news'],"http://spptest-upload.stor.sinaapp.com"),0,68);
				}else{
					$succ[$i]['img'] = substr(strchr($succ[$i]['content_news'],"/tpl/include/kindeditor/php/upload/image/"),0,74);
				}		
			}
			$this->succ = $succ;

        	$pager = $news->spPager()->getPager();
			$this->pager = $pager;
			$this->curpage = floor($pager['current_page']/10)*10;
        		
        	$this->position = "成功案例";
        	$this->kwds = $keyword;
       		$this->display("bigshow/succ_search.html");
		}else{
			$this->error("输入内容为空或含有非法字符，请重新输入，谢谢！");
		}		
	}
	//学校搜索
	function univs(){
		$keyword = urldecode($this->spArgs("kwds"));
		$keyword = spClass("comn")->tag_replace($keyword);
		if($keyword !=""){
			$sqls = "";
			$keywords = explode(" ",$keyword);	
			$counts = count($keywords)-1;
			for($i=0; $i<$counts; ++$i){
				$sqls = $sqls." cname_univ like '%". $keywords[$i]."%' and"; 
			}
			$sqls = "select * from tb_university where".$sqls." cname_univ like '%". $keywords[$counts]."%'";

			$university = spClass("university"); 
			$this->university = $university->spPager($this->spArgs('page', 1), 15)->findSql($sqls);
        	
        	$pager = $university->spPager()->getPager();
			$this->pager = $pager;
			$this->curpage = floor($pager['current_page']/10)*10;
        		
        	$this->position = "学校";
        	$this->kwds = $keyword;
       		$this->display("bigshow/univ_search.html");
		}else{
			$this->error("输入内容为空或含有非法字符，请重新输入，谢谢！");
		}			
	}
	//专业搜索
	function majrs(){
		$keyword = urldecode($this->spArgs("kwds"));
		$keyword = spClass("comn")->tag_replace($keyword);
		if($keyword !=""){
			$sqls = "";
			$keywords = explode(" ",$keyword);	
			for($i=0; $i<count($keywords); ++$i){
				$sqls = $sqls." and ( cname_majr like '%". $keywords[$i]."%' or cname_univ like '%". $keywords[$i]."%')"; 
			}

			$conditions = "select id_majr,cname_majr,cname_schl,ename_majr,
							cname_univ,logo_univ,desc_majr,ename_univ
							from tb_major,tb_university,tb_school   
							where univ_majr = id_univ and schl_majr = id_schl".$sqls;

			$major = spClass("major");
			$this->major = $major->spPager($this->spArgs('page', 1), 15)->findSql($conditions);
        	
        	$pager = $major->spPager()->getPager();
			$this->pager = $pager;
			$this->curpage = floor($pager['current_page']/10)*10;
        		
        	$this->position = "专业";
        	$this->kwds = $keyword;
       		$this->display("bigshow/majr_search.html");
		}else{
			$this->error("输入内容为空或含有非法字符，请重新输入，谢谢！");
		}	
	}
}	