<?php
class admin extends spController
{
	// 首页
	public function index(){
		$this->display("admin/index.html"); // 显示模板
	}
	public function front(){
		$this->display("admin/main.html"); // 显示模板
	}
	// 导航
	public function top(){
		$this->display("admin/header.html"); // 显示模板
	}
	// 新增咨询
	public function add(){

        //返回国家信息
		$country = spClass("country");
		$this->country = $country->findAll(); 

		//返回专业大类列表
		$category = spClass("category");
		$this->category = $category->findAll(); 

		$this->display("admin/add.html"); // 显示模板
	}
	// 新增学院
	public function addl(){
		$this->display("admin/schladd.html"); // 显示模板
	}
	// 左菜单
	public function menu(){
		$this->display("admin/menu.html"); // 显示模板
	}
	// 改密码
	public function pswd(){
		$this->display("admin/pswd.html"); // 显示模板
	}	
	// 新增国家
	public function addc(){
		$this->display("admin/ctryadd.html"); // 显示模板
	}
	// 新增专业大类
	public function addy(){
		$this->display("admin/cateadd.html"); // 显示模板
	}
	// 新增专业小类
	public function adds(){
		$category = spClass("category");
		$this->category = $category->findAll(); 
		$this->display("admin/clssadd.html"); // 显示模板
	}
	// 新增学校
	public function univadd(){
        $this->arrcount = array(1,2,3,4,5);
		$country = spClass("country");
		$this->country = $country->findAll(); 
		$this->display("admin/univdd.html"); // 显示模板
	}

	// 新增学校
	public function addr(){
		//返回国家信息
		$country = spClass("country");
		$this->country = $country->findAll(); 
		//返回专业大类列表
		$category = spClass("category");
		$this->category = $category->findAll(); 
		$this->display("admin/majrdd.html"); // 显示模板
	}
	
	//显示留学咨询
	public function news(){
		// 这里的功能：
		// 1. 分页查找留言，通过$this->spArgs("page",1)获取到提交的页码，后面的1是默认值，在没有页面的时候就是1页
		// 2. findAll的参数，首先是NULL（无条件，查找全部）；"ctime DESC"是排序，这里按时间倒序；"gid,uname,title,ctime"是希望返回gid，用户名，标题和时间，而不获取contents（内容）以节省系统资源。
		// 3. 通过$this->results传递到模板上。
		$news = spClass("news");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$condition = array('type_news'=>"info");
		$this->results = $news->spPager($this->spArgs('page', 1), 10)->findAll($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $news->spPager()->getPager();
		$this->position = "留学资讯";
		$this->display("admin/news.html"); // 显示模板
	}
	
	//显示评估表列表
	public function msge(){
		// 这里的功能：
		// 1. 分页查找留言，通过$this->spArgs("page",1)获取到提交的页码，后面的1是默认值，在没有页面的时候就是1页
		// 2. findAll的参数，首先是NULL（无条件，查找全部）；"ctime DESC"是排序，这里按时间倒序；"gid,uname,title,ctime"是希望返回gid，用户名，标题和时间，而不获取contents（内容）以节省系统资源。
		// 3. 通过$this->results传递到模板上。
		$user = spClass("user");
		$conditions = " name_user = 'send' ";
        $this->mail = $user->findAll($conditions); 
        
		$message = spClass("message");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$this->results = $message->spPager($this->spArgs('page', 1), 20)->findAll(null,"id_msge DESC"); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $message->spPager()->getPager();
		$this->position = "评估信息";
		$this->display("admin/msge.html"); // 显示模板
	}
	
	//自动发送修改
	function sendset(){
		$mail = $this->spArgs("mail"); //接受参数name
		$pswd = $this->spArgs("auto"); //接受参数group
        $user = spClass("user");
		$condition = array('name_user'=>'send'); // 获取ID             
		$row = array('email_user'=>$mail,'pswd_user'=>$pswd);
		$this->info = $user->update($condition, $row); 
     	$this->success("自动发送设置成功！", spUrl("admin","msge")); 	
	}
	
	//显示评估表详细内容
	public function msgc(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			$message = spClass("message");
        	$condition = array('id_msge'=>$id); // 获取ID
        	$info = $message->findAll($condition);  //根据ID搜索留学资讯
        	$this->info = $info;
        	$send = $this->spArgs("sd");
			if($send == "0"){
        		$this->display("admin/msgc.html"); // 显示模板
			}else if($send == "2"){	
				$this->display("admin/send.html"); // 显示模板			
			}else{
				
                $email = $this->spArgs("email");//发送邮件
				
				$gender = "女";
				if ($info[0]['gender_msge'] == 1){
					$gender="男";
				}
	
				$money="30万RMB以上";
				if ($info[0]['money_msge']==1){
					$money="10万RMB以下";
				}else if($info[0]['money_msge']==2){
					$money="10-20万RMB";
				}else if($info[0]['money_msge']==3){
					$money="20-30万RMB";
				}
				
				$enter = "\n";
				$content = "个人信息".$enter
				."姓名:".$info[0]['name_msge'].$enter
				."性别:".$gender.$enter
				."邮箱:".$info[0]['mail_msge'].$enter
				."手机:".$info[0]['tel_msge'].$enter
				."入学年份:".$info[0]['adm_msge'].$enter.$enter
				."教育背景".$enter
				."毕业学校:"	.$info[0]['univ_msge'].$enter
				."所在院系:".$info[0]['schl_msge'].$enter
				."所学专业:".$info[0]['majr_msge'].$enter
				."平均成绩:".$info[0]['gpa_msge'].$enter.$enter
				."英语成绩:".$enter
				."英语4级:".$info[0]['cet4_msge'].$enter
				."英语6级:".$info[0]['cet6_msge'].$enter
				."雅思/托福:".$info[0]['it_msge'].$enter
				."GRE/GMAT:".$info[0]['et_msge'].$enter.$enter
				."申请目标:".$enter
				."申请学位:".$info[0]['obj_msge'].$enter
				."留学区域:".$info[0]['ctry_msge'].$enter
				."留学动机:".$info[0]['purp_msge'].$enter
				."存款证明:".$money.$enter
				."补充信息:".$enter
				."申请学位:".$info[0]['content_msge'].$enter
                                ."留言时间:".$info[0]['time_msge'];	
				
                //sae发送邮件		
                /*$mail = new SaeMail();
                
                $mail->setOpt( 
                	array(
        			'from' => 'admin@compassedu.hk',
        			'to' => $email,
            		'smtp_host' => 'smtp.exmail.qq.com', 
            		'smtp_username' => 'admin@compassedu.hk',  
           			'smtp_password' => 'zhinanzhe123',  
            		'subject' => '评估表：'.$info[0]['name_msge'],  
            		'content' => $content, 
            		) 
            	); 
				$ret = $mail->send(); 
	
                if ($ret === false){
					var_dump($mail->errno(), $mail->errmsg());
				}else{                
                        $row = array('send_msge'=>date('Y-m-d H:i:s'),'recer_msge'=>$email);
        				$this->refresh = $message->update($condition, $row);   //根据ID修改留学资讯
						$this->success("邮件发送成功！", spUrl("admin","msge"));
                                        
				}*/

				//虚拟机邮件发送	
                $mail = spClass('spEmail');
                $mailsubject = '评估表：'.$info[0]['name_msge'];//邮件主题
                $mailbody = $content;//邮件内容
                $mailtype = "TXT";//邮件格式（HTML/TXT）,TXT为文本邮件
                if($mail->sendmail($email, $mailsubject, $mailbody, $mailtype)){
                	 $row = array('send_msge'=>date('Y-m-d H:i:s'),'recer_msge'=>$email);
        			$this->refresh = $message->update($condition, $row);   //根据ID修改留学资讯
					$this->success("邮件发送成功！", spUrl("admin","msge"));                   
                };
			}
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","login"));
		}
	}
	//显示留学咨询
	public function cate(){
		// 这里的功能：
		// 1. 分页查找留言，通过$this->spArgs("page",1)获取到提交的页码，后面的1是默认值，在没有页面的时候就是1页
		// 2. findAll的参数，首先是NULL（无条件，查找全部）；"ctime DESC"是排序，这里按时间倒序；"gid,uname,title,ctime"是希望返回gid，用户名，标题和时间，而不获取contents（内容）以节省系统资源。
		// 3. 通过$this->results传递到模板上。
		$category = spClass("category");
		$this->position = "专业大类";
		$this->category = $category->findAll(); 
		// 这里获取分页数据并发送到smarty模板内
		$this->display("admin/cate.html"); // 显示模板
	}
	
	//显示学校信息
	public function univ(){
		//输出国家选项
		$country = spClass("country");
		$this->country = $country->findAll(); 
		//输出学校列表
		$ctry = $this->spArgs("cy"); //接受参数cy 国家
		$univ = $this->spArgs("univ");//接受参数univ 学校
		$university = spClass("university");
		if($univ==""){
			$condition = " ctry_univ = '$ctry'";
		}else{
			$condition = " cname_univ like '%$univ%' and ctry_univ = '$ctry'";
		} 
		$this->university = $university->spPager($this->spArgs('page', 1), 15)->findAll($condition," rank_univ ASC "); 
		$this->pager = $university->spPager()->getPager();
		$this->ctry = $ctry;
		$this->univ = $univ;
		$this->position = "学校信息";
		
		$this->display("admin/univ.html"); // 显示模板
	}
	
	//显示专业小类信息
	public function clss(){
		//输出国家选项
		$category = spClass("category");
		$this->category = $category->findAll(); 
		//输出学校列表
		$cate = $this->spArgs("cs"); //接受参数cy
		$classes = spClass("classes");
		$condition = array('cate_clss'=>$cate);
		$this->classes = $classes->spPager($this->spArgs('page', 1), 15)->findAll($condition); 
		$this->pager = $classes->spPager()->getPager();
		$this->cate = $cate;
		$this->position = "专业小类";
		$this->display("admin/clss.html"); // 显示模板
	}
	
	//显示学校界面国家列表信息
	public function univc(){
		$country = spClass("country");
		$this->country = $country->findAll(); 
		$this->position = "学校信息";
		$this->display("admin/univc.html"); // 显示模板
	}
	
	//显示专业小类界面专业大类列表信息
	public function clssc(){
		$category = spClass("category");
		$this->category = $category->findAll(); 
		$this->position = "专业小类";
		$this->display("admin/clssc.html"); // 显示模板
	}
	
	//显示成功案例
	public function succ(){
		// 这里的功能：
		// 1. 分页查找留言，通过$this->spArgs("page",1)获取到提交的页码，后面的1是默认值，在没有页面的时候就是1页
		// 2. findAll的参数，首先是NULL（无条件，查找全部）；"ctime DESC"是排序，这里按时间倒序；"gid,uname,title,ctime"是希望返回gid，用户名，标题和时间，而不获取contents（内容）以节省系统资源。
		// 3. 通过$this->results传递到模板上。
		$news = spClass("news");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$condition = array('type_news'=>"succ");
		$this->results = $news->spPager($this->spArgs('page', 1), 10)->findAll($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $news->spPager()->getPager();
		$this->position = "成功案例";
		$this->display("admin/succ.html"); // 显示模板
	}
	
	//显示最新录取
	public function acpt(){
		// 这里的功能：
		// 1. 分页查找留言，通过$this->spArgs("page",1)获取到提交的页码，后面的1是默认值，在没有页面的时候就是1页
		// 2. findAll的参数，首先是NULL（无条件，查找全部）；"ctime DESC"是排序，这里按时间倒序；"gid,uname,title,ctime"是希望返回gid，用户名，标题和时间，而不获取contents（内容）以节省系统资源。
		// 3. 通过$this->results传递到模板上。
		$news = spClass("news");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$condition = array('type_news'=>"acpt");
		$this->results = $news->spPager($this->spArgs('page', 1), 10)->findAll($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $news->spPager()->getPager();
		$this->position = "录取通知";
		$this->display("admin/acpt.html"); // 显示模板
	}
	
	//显示帮助中心
	public function help(){
		// 这里的功能：
		// 1. 分页查找留言，通过$this->spArgs("page",1)获取到提交的页码，后面的1是默认值，在没有页面的时候就是1页
		// 2. findAll的参数，首先是NULL（无条件，查找全部）；"ctime DESC"是排序，这里按时间倒序；"gid,uname,title,ctime"是希望返回gid，用户名，标题和时间，而不获取contents（内容）以节省系统资源。
		// 3. 通过$this->results传递到模板上。
		$news = spClass("news");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$condition = array('type_news'=>"help");
		$this->results = $news->spPager($this->spArgs('page', 1), 10)->findAll($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $news->spPager()->getPager();
		$this->position = "帮助中心";
		$this->display("admin/help.html"); // 显示模板
	}
	
	//显示国家信息
	public function ctry(){
		$country = spClass("country");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$this->results = $country->spPager($this->spArgs('page', 1), 10)->findAll(); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $country->spPager()->getPager();
		$this->position = "国家信息";
		$this->display("admin/ctry.html"); // 显示模板
	}
	
	//显示学院信息
	public function schl(){
		if($this->spArgs("kwds")){
			$keywords = urldecode($this->spArgs("kwds"));
		}else{
			$keywords = "";
		}
		
		$school = spClass("school"); 
        $sql = "select id_schl,cname_schl,
        		ename_schl,univ_schl,cname_univ 
				from tb_school,tb_university 
				where univ_schl = id_univ 
				and cname_univ like '%$keywords%'";
		$this->results = $school->spPager($this->spArgs('page', 1), 15)->findSql($sql);
        $this->pager = $school->spPager()->getPager();
        $this->kwds = $keywords;
 		$this->position = "学院信息";
       	$this->display("admin/schl.html");       
	}
	
	//显示专业列表
	public function majrl(){
		if($this->spArgs("schl")){
			$schl = urldecode($this->spArgs("schl"));
		}else{
			$schl = "";
		}
		
		if($this->spArgs("kwds")){
			$kwds = urldecode($this->spArgs("kwds"));
		}else{
			$kwds = "";
		}
		
		 $condition = "select id_majr,cname_majr,cname_schl,
        		cname_univ,cname_clss,cname_cate 
				from tb_school,tb_university,tb_classes,tb_category,tb_major 
				where id_clss = clss_majr 
				and univ_majr = id_univ 	
				and schl_majr = id_schl 
				and cate_clss = id_cate ";
		
		$major = spClass("major"); 
       
		if($schl!="" && $kwds==""){
			$sql = $condition."and cname_univ like '%$schl%'";
		}else if($schl=="" && $kwds!=""){
			$sql = $condition."and cname_majr like '%$kwds%'";	
		}else{
			$sql = $condition."and cname_univ like '%$schl%' and cname_majr like '%$kwds%'";
		}
		
		
		$this->major = $major->spPager($this->spArgs('page', 1), 15)->findSql($sql);
        $this->pager = $major->spPager()->getPager();
        $this->kwds = $kwds;
        $this->schl = $schl;
 		$this->position = "专业信息";
       	$this->display("admin/majrl.html");       
	}
	
	//显示专业信息
	public function majr(){
		$id = $this->spArgs("id");
		$condition = "select id_majr,cname_majr,ename_majr,
        				cname_schl,cname_univ,cname_clss,
        				cname_cate, rank_majr,
        				desc_majr,url_majr 
					from tb_school,tb_university,
    					tb_classes,tb_category,tb_major 
					where id_clss = clss_majr 
					and univ_majr = id_univ 	
					and schl_majr = id_schl 
					and cate_clss = id_cate
					and id_majr=$id";
		
		$major = spClass("major"); 
       		
		$this->major = $major->findSql($condition);
        $this->id = $id;
 		$this->position = "专业信息";
       	$this->display("admin/viewmajor.html");       
	}
	
	// 添加国家信息
	public function postc(){
		if($this->spArgs('save')){
 		$s = new SaeStorage(); 
   if(!$_FILES['pic']['name']) die('没有选择文件!');
   
				$cname = $this->spArgs("cname"); //接受参数cname
				$ename = $this->spArgs("ename"); //接受参数ename
				$code = $this->spArgs("code"); //接受参数code
                                
    $ext = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
				$uploadfile = $ename.'.'.$ext; //要保存的文件路径+文件名，此处保存在upload/目录下
				
    if($s->upload(DOMAIN, $uploadfile, $_FILES['pic']['tmp_name'])){                    
                        
			$pic = $s->getUrl(DOMAIN,$uploadfile);
			
				$country = spClass("country");
				$condition1 = array('code_ctry'=>$code);
				$condition2 = array('ename_ctry'=>$ename);
				$condition3 = array('cname_ctry'=>$cname);
		
				if($country->findCount($condition1)>0){
					$this->error("国家代码重复",spUrl("admin","addc"));
				}else if($country->findCount($condition2)>0){
					$this->error("国家英文名重复",spUrl("admin","addc"));
				}else if($country->findCount($condition3)>0){
					$this->error("国家中文名重复",spUrl("admin","addc"));
				}else{
					$newrow = array( // PHP的数组
						'cname_ctry' => $cname,
						'ename_ctry' => $ename,
						'code_ctry' => $code,
						'pic_ctry' => $pic
					);
					$country->create($newrow);
					$this->success("国家信息添加成功！", spUrl("admin","ctry"));
				}		
			}else {
				$this->error("error",spUrl("admin","addc"));
			}	
  unset($s);
		}
	}
	
	// 添加学校信息
	public function postu(){
		if($this->spArgs('save')){
                
            $cname      = $this->spArgs("cname"); //接受参数cname
			$ename      = $this->spArgs("ename"); //接受参数ename
			$country    = $this->spArgs("country"); //接受参数country
			$rank       = $this->spArgs("rank"); //接受参数rank
			$desc       = $this->spArgs("desc"); //接受参数desc  
            $py         = $this->spArgs("py"); //接受参数py  
            $keywords   = $this->spArgs("keywords"); //接受参数keywords 

            //新增学术水平。校园环境，消费水平，分析评价等4个字段
            $acad_univ  = $this->spArgs("acad_univ"); 
            $env_univ   = $this->spArgs("env_univ"); 
            $cons_univ  = $this->spArgs("cons_univ"); 
            $eval_univ  = $this->spArgs("eval_univ");
            $job_univ   = $this->spArgs("job_univ"); 
            $easy_univ  = $this->spArgs("easy_univ"); 

			 
   			if(!$_FILES['pic']['name']) die('没有选择文件!');
   
				$ext = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
				$uploadfile = $ename.'.'.$ext; //要保存的文件路径+文件名，此处保存在upload/目录下
			
			//sae上传功能
			//$s = new SaeStorage();	
			//$s->upload(DOMAIN, $uploadfile, $_FILES['pic']['tmp_name'])	
			//$pic = $s->getUrl(DOMAIN,$uploadfile);

   			$up = spClass("upload");
			$up->setOptions(array('userDefName'=>$uploadfile,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_UNIV));
    		$up->uploadFile($_FILES['pic']);

   			if($up->getErrorNo()==0){                    
				$university = spClass("university");
						
				$condition1 = array('ename_univ'=>$ename);
				$condition2 = array('cname_univ'=>$cname);
		
				if($university->findCount($condition1)>0){
					$this->error("学校英文名重复",spUrl("admin","addu"));
				}else if($university->findCount($condition2)>0){
					$this->error("学校中文名重复",spUrl("admin","addu"));
				}else{
					$newrow = array( // PHP的数组
						'cname_univ' => $cname,
						'ename_univ' => $ename,
						'ctry_univ' => $country,
						'desc_univ' => $desc,
						'rank_univ' => $rank,
						'logo_univ' => $pic,
                        'py_univ'   => $py,
                        'kws_univ'  => $keywords,	
						'acad_univ' => $acad_univ,
						'env_univ'  => $env_univ,
						'cons_univ' => $cons_univ,
                        'eval_univ' => $eval_univ,
						'job_univ'  => $job_univ,
                        'easy_univ' => $easy_univ
					);
					$university->create($newrow);
                                       
                    $id = $university->findAll($newrow);
                    // 网站数据同步
                    //$f = new SaeFetchurl();
  					//$f->setMethod("post");
  					//$f->setPostData(
    				//	array(
                    //        "user"=> $_SESSION["userinfo"]["name_user"],
                    //        "pass"=> $_SESSION["userinfo"]["pswd_user"],
                    //        "id" => $id[0]['id_univ'],
                    //        'cname' => $cname,
                    //        'ename' => $ename,
                    //        'ctry' => $country,
    				//	)
  				//);

                    //$f->fetch("http://www.showcute.sinaapp.com/synch/univ_add.php");
                    //$f->fetch("http://www.wayabroad.sinaapp.com/synch/univ_add.php");
                    //$ntc =  "OK";
                    //if($f->errno() != 0){ 
                    //    $ntc = $f->errmsg();
                    //}
                                            
                    $this->success("学校信息添加成功！", spUrl("admin","univ"));
				}
			}else {
				$this->error("error",spUrl("admin","univadd"));
			}
   			//storage对象对象销毁
   			//unset($s);
		}
	}
	
	// 添加专业小类信息
	public function posts(){
		if($this->spArgs('save')){
   			$cname = $this->spArgs("cname"); //接受参数cname
			$ename = $this->spArgs("ename"); //接受参数ename
			$category = $this->spArgs("category"); //接受参数category
                        
			$s = new SaeStorage(); 
   			if(!$_FILES['pic']['name']) die('没有选择文件!');
   
			$ext = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
			$uploadfile = $ename.'.'.$ext; //要保存的文件路径+文件名，此处保存在upload/目录下
				
   if($s->upload(DOMAIN, $uploadfile, $_FILES['pic']['tmp_name'])){                    
                        
				$pic = $s->getUrl(DOMAIN,$uploadfile);
											
				$classes = spClass("classes");
    //查询条件		
				$condition1 = array('ename_clss'=>$ename);
				$condition2 = array('cname_clss'=>$cname);
		
				if($classes->findCount($condition1)>0){
					$this->error("专业小类英文名重复",spUrl("admin","adds"));
				}else if($classes->findCount($condition2)>0){
					$this->error("专业小类中文名重复",spUrl("admin","adds"));
				}else{
					$newrow = array( // PHP的数组
						'cname_clss' => $cname,
						'ename_clss' => $ename,
						'cate_clss' => $category,
						'pic_clss' => $pic
					);
					$classes->create($newrow);
					$this->success("专业小类信息添加成功！", spUrl("admin","clssc"));
				}
			}else {
				$this->error("error",spUrl("admin","adds"));
			}	
  unset($s);
		}
	}
	
	
	// 添加专业大类信息
	public function posty(){
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$category = spClass("category");
						
		$condition1 = array('ename_cate'=>$ename);
		$condition2 = array('cname_cate'=>$cname);
		
		if($category->findCount($condition1)>0){
			$this->error("专业英文名重复",spUrl("admin","addy"));
		}else if($category->findCount($condition2)>0){
			$this->error("专业中文名重复",spUrl("admin","addy"));
		}else{
			$newrow = array( // PHP的数组
				'cname_cate' => $cname,
				'ename_cate' => $ename,
			);
			$category->create($newrow);
			$this->success("专业大类添加成功！", spUrl("admin","cate"));
		}
	}
	
	// 添加专业信息
	public function postr(){
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$university = $this->spArgs("university"); //接受参数university
		$school = $this->spArgs("school"); //接受参数school
		$classes = $this->spArgs("classes"); //接受参数classes
		$rank = $this->spArgs("rank"); //接受参数rank
		$url = $this->spArgs("url"); //接受参数url
		$desc = $this->spArgs("desc"); //接受参数desc		
		
		$major = spClass("major");
						
		$condition1 = " ename_majr='$ename' and schl_majr='$school' ";
		$condition2 = " cname_majr='$cname' and schl_majr='$school' ";
		
		if($major->findCount($condition1)>0){
			$this->error("专业英文名重复",spUrl("admin","addr"));
		}else if($major->findCount($condition2)>0){
			$this->error("专业中文名重复",spUrl("admin","addr"));
		}else{
			$newrow = array( // PHP的数组
				'cname_majr' => $cname,
				'ename_majr' => $ename,
				'univ_majr' => $university,
				'schl_majr' => $school,
				'clss_majr' => $classes,
				'rank_majr' => $rank,
				'url_majr' => $url,
				'desc_majr' => $desc,
			
			);
			$major->create($newrow);
                        
                        
                        $id = $major->findAll($newrow);
                        // 网站数据同步
                        $f = new SaeFetchurl();
  			$f->setMethod("post");
  			$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id[0]['id_majr'],
                                  'cname' => $cname,
				  'ename' => $ename,
				  'univ' => $university,
				  'schl' => $school,
				  'clss' => $classes,
				  'rank' => $rank,
				  'url' => $url,
				  'desc' => $desc,
    				)
  			);
  			$f->fetch("http://www.wayabroad.sinaapp.com/synch/major_add.php");
                        $ntc =  "OK";
                  	if($f->errno() != 0){ 
                  		$ntc = $f->errmsg();
                        }
                        
                        
			$this->success("专业添加成功！", spUrl("admin","majrl"));
		}
	}
	
	// 添加学院信息
	public function postl(){
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$univ = $this->spArgs("university"); //接受参数university
		
		$university = spClass("university");
		$school = spClass("school");

		$condition = array('cname_univ'=>$univ);
		$sql1 = "SELECT id_schl 
				FROM tb_school,tb_university 
				where cname_univ='$univ' 
                                and id_univ = univ_schl 
				and cname_schl='$cname'";
		$sql2 = "SELECT id_schl 
				FROM tb_school,tb_university 
				where cname_univ='$univ' 
                                and id_univ = univ_schl 
				and ename_schl='$ename'";
		
		if($university->findCount($condition)==0){
			$this->error("无此学校，请先添加学校",spUrl("admin","addl"));
		}else if($university->findSql($sql1)){
			$this->error("学院中文名重复",spUrl("admin","addl"));
		}else if($university->findSql($sql2)){
			$this->error("学院英文名重复",spUrl("admin","addl"));
		}else{
			
			$univid = $university->findAll($condition);
			$newrow = array( // PHP的数组
				'cname_schl' => $cname,
				'ename_schl' => $ename,
				'univ_schl'  => $univid[0]['id_univ'],
			);
			$school->create($newrow);
                        
                        $id = $school->findAll($newrow);
                        // 网站数据同步
                        $f = new SaeFetchurl();
  			$f->setMethod("post");
  			$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id[0]['id_schl'],
                                  'cname' => $cname,
				  'ename' => $ename,
				  'univ'  => $univid[0]['id_univ'],
    				)
  			);
  			$f->fetch("http://www.wayabroad.sinaapp.com/synch/schl_add.php");
                        $ntc =  "OK";
                  	if($f->errno() != 0){ 
                  		$ntc = $f->errmsg();
                        }
                        
                        
			$this->success("学院添加成功！", spUrl("admin","schl"));
		}
	}
	
	//查看资讯内容
	public function view(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			$news = spClass("news");  //
        	$condition = array('id_news'=>$id); // 获取ID
        	$this->info = $news->findAll($condition);  //根据ID搜索留学资讯
			$this->display("admin/view.html"); // 显示模板
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","login"));
		}
	}
	
	// 修改资讯信息
	public function insert(){
		$id = $this->spArgs("id"); //接受参数id 
		$content = $this->spArgs("content"); //接受参数content
        $title = $this->spArgs("title"); //接受参数title
		$time=date("Y-m-d H:i:s");
        $news = spClass("news");  //
        $condition = array('id_news'=>$id); // 获取ID
        $row = array('content_news'=>$content,'title_news'=>$title);
        $this->info = $news->update($condition, $row);   //根据ID修改留学资讯
        $this->success("修改成功，请继续使用！", spUrl("admin","news"));
	}
	
	// 退出登录
	public function logout(){
		// 这里是PHP.net关于删除SESSION的方法
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', time()-42000, '/');}
		session_destroy();
		// 跳转回首页
		$this->success("已退出，返回首页！", spUrl("main","index"));
	}
	
	// 用户修改密码
	public function pwsde(){
		$userObj = spClass("user"); // 实例化lib_user类
		if( $uname = $this->spArgs("name") ){ // 已经提交，这里开始进行登录验证
			//$upass = spClass("spAcl")->pwvalue(); // 通过acl的pwvalue获取提交的加密密码
			$opass=md5($this->spArgs("opass"));
			$npass=md5($this->spArgs("npass"));
			// 使用spVerifier进行第一次检查
			$rows = array('name_user' => $uname, 'pswd_user' => $opass);
			$results = $userObj->spVerifier($rows);
			
			if( false == $results ){ // 当spVerifier返回false的时候，则是表示已经通过验证，数据是合格的
			
				// 使用lib_user类中我们新建的userlogin方法来验证用户名和密码
				if( false == $userObj->userlogin($uname, $opass) ){
					// 登录失败，提示后跳转回登录页面
					$this->error("原始密码错误，请重新输入！", spUrl("admin","pswd"));	
				}else{
					$condition = array('name_user'=>$uname); // 获取用户名
					$row = array('pswd_user'=>$npass);
        			$userObj->update($condition, $row);   //根据ID修改留学资讯
        			$this->success("密码修改成功！", spUrl("admin","news"));
				}
			}else{
				// $results不是false，所以没有通过验证，错误信息是$results
				// dump($results);
				foreach($results as $item){ // 开始循环错误信息的规则，这里只有用户名
					// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
					foreach($item as $msg){ 
						// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
						// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
						$this->error($msg,spUrl("admin","login"));
						$this->display("admin/login.html");
					}
				}
			}
		}
		$this->display("admin/pwsde.html");
		// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单
	}
	
	// 显示用户登录框以及验证用户登录情况
	public function login(){
		import("spAcl.php"); // 引入Acl文件，使得可以生成加密的密码输入框
		$userObj = spClass("user"); // 实例化lib_user类
		if( $uname = $this->spArgs("uname") ){ // 已经提交，这里开始进行登录验证
			//$upass = spClass("spAcl")->pwvalue(); // 通过acl的pwvalue获取提交的加密密码
			$upass=md5($this->spArgs("upass"));
			// 使用spVerifier进行第一次检查
			$rows = array('name_user' => $uname, 'pswd_user' => $upass);
			$results = $userObj->spVerifier($rows);
			
			if( false == $results ){ // 当spVerifier返回false的时候，则是表示已经通过验证，数据是合格的
			
				// 使用lib_user类中我们新建的userlogin方法来验证用户名和密码
				if( false == $userObj->userlogin($uname, $upass) ){
					// 登录失败，提示后跳转回登录页面
					$this->error("用户名/密码错误，请重新输入！", spUrl("admin","login"));
					
				}else{
					// 成功登录，跳转。这里要进行判断一下：
					// 如果用户角色是GBADMIN（管理员）则跳转到admin/index的管理中心
					// 如果用户角色是GBUSER（普通会员）则跳转回首页
					$useracl = spClass("spAcl")->get(); // 通过acl的get可以获取到当前用户的角色标识
					if( "GBADMIN" == $useracl ){
						$this->success("登录成功，欢迎您，管理员！", spUrl("admin","index"));
					}else if( "SALES" == $useracl){
						$this->success("登录成功，欢迎您，尊敬的客服人员！", spUrl("admin","index"));
					}else{
						$this->success("登录成功，欢迎您，尊敬的会员！", spUrl("admin","index"));	
					}
				}
			}else{
				// $results不是false，所以没有通过验证，错误信息是$results
				// dump($results);
				foreach($results as $item){ // 开始循环错误信息的规则，这里只有用户名
					// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
					foreach($item as $msg){ 
						// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
						// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
						$this->error($msg,spUrl("admin","login"));
						$this->display("admin/login.html");
					}
				}
			}
		}
		$this->display("admin/login.html");
		// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单
	}
	
	// 发布咨询
	public function post(){
		if($this->spArgs("title")){
			$title = $this->spArgs("title");
			$content    = $this->spArgs("content");
			$type       = $this->spArgs("type");

            //增加添加时间，关键字，学校，专业显示
			$showtime   = $this->spArgs("showtime");
			$keywords   = $this->spArgs("keywords");
			$id_univ    = $this->spArgs("university");
			$id_clss    = $this->spArgs("classes");

			if($type =="info"){

				if( !$_FILES['pic']['name'] or !$_FILES['avatar']['name'] )	die('没有选择文件a!');
				   			
	   			$tmp = date('ymdHis');
				$ext_p = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
				$uploadfile_p = "P".$tmp.'.'.$ext_p; //要保存的文件路径+文件名，此处保存在upload/目录下
				
				$ext_a = end(explode('.',$_FILES['avatar']['name'])); //获得扩展名
				$uploadfile_a = "A".$tmp.'.'.$ext_a; //要保存的文件路径+文件名，此处保存在upload/目录下
				

	   			$up1 = spClass("upload");
				$up1->setOptions(array('userDefName'=>$uploadfile_a,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_NEWS));
	    		$up1->uploadFile($_FILES['avatar']);

	    		$up2 = spClass("upload");
	    		$up2->setOptions(array('userDefName'=>$uploadfile_p,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_NEWS));
	    		$up2->uploadFile($_FILES['pic']);

	    		if($up1->getErrorNo()==0 && $up2->getErrorNo()==0){
	    			$news   = spClass("news"); // 实例化留言对象
					$newrow = array( // PHP的数组
						'title_news'    => $title,
						'content_news'  => $content,
						'type_news'     => $type,
						'keywords_news' => $keywords,
						'id_univ'       => $id_univ,
						'id_clss'       => $id_clss,
						'time_news'     => $showtime,
						'avatar_news'     => $uploadfile_a,
						'pic_news'     => $uploadfile_p,
						'appdate_new'  => date('Y-m-d H:i:s'),
					);
					$news->create($newrow);
					$this->success("留言成功！", spUrl("admin","news"));
	    		}else{
	    			$this->error("图片上传失败！");
	    		}		
			}else{
				$news       = spClass("news"); // 实例化留言对象
				$newrow = array( // PHP的数组
					'title_news'    => $title,
					'content_news'  => $content,
					'type_news'     => $type,
					'keywords_news' => $keywords,
					'id_univ'       => $id_univ,
					'id_clss'       => $id_clss,
					'time_news'     => $showtime,
					'appdate_new'  => date('Y-m-d H:i:s'),
				);
				$news->create($newrow);
				$this->success("留言成功！", spUrl("admin","news"));	
			}	
		}
	}
        
        
        //学校资料下载
	public function down(){
		//输出专业信息
		$major = spClass("major");
		$univ_id = $this->spArgs("id"); //接受参数id
		$condition = array('univ_majr'=>$univ_id);
		$this->major = $major->findAll($condition); 
		//输出学院信息
		$school = spClass("school");
		$condition1 = array('univ_schl'=>$univ_id);
		$this->school = $school->findAll($condition1); 
		//输出学校信息
		$university = spClass("university");
		$condition2 = array('id_univ'=>$univ_id);
		$u_temp = $university->findAll($condition2); 
		$this->university = $u_temp;
		header('application/msword');
		header('Content-Disposition: attachment; filename="'.$u_temp[0]['cname_univ'].'.doc"'); 
		
		$this->display("admin/download.html"); // 显示模板
	}
}	