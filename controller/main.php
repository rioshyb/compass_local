<?php
class main extends spController
{
	// 这里是首页
	function index(){ 
		$news = spClass("news");
		$condition1 = "select id_news,title_news,pic_news,avatar_news,content_news  
					from tb_news 
					where type_news = 'info'  
					ORDER BY time_news DESC limit 3";
        
        $condition2 = "select id_news,title_news,content_news,logo_univ,ctry_univ   
					from tb_news,tb_university 
					where type_news in ('acpt','succ')   
					and tb_news.id_univ = tb_university.id_univ  
					order by id_news desc LIMIT 4";

		$this->news = $news->findSql($condition1); // 返回最新资讯
        $this->succ = $news->findSql($condition2); // 返回最新案例

        $this->display("bigshow/index.html"); // 显示模板，这里使用的模板是根目录/tpl/green/index.html。
	}
	
	//显示留学资讯
	function newsp(){
		if($this->spArgs("cy")){
			$ctry = $this->spArgs("cy"); //接受参数cy
		}else{
			$ctry = 11;
		}
		

		$country = spClass("country");
		$this->list = $country->findAll();
		

		$news = spClass("news");
		$condition = "select id_news,title_news,avatar_news,time_news,content_news,keywords_news 
					from tb_news 
					where type_news = 'info'  
					and tb_news.id_univ in 
					(select tb_university.id_univ 
					from tb_university 
					where ctry_univ = ".$ctry.") 
					ORDER BY time_news DESC ";
		

		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$this->results = $news->spPager($this->spArgs('page', 1), 15)->findSql($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$pager = $news->spPager()->getPager();
		$this->pager = $pager;
		$this->curpage = floor($pager['current_page']/10)*10;

		$position="留学资讯";
		$this->position = $position;
		$this->cy = $ctry;
		$this->display("bigshow/info.html");
	}
	//显示成功案例
	function succp(){
		if($this->spArgs("cy")){
			$ctry = $this->spArgs("cy"); //接受参数cy
		}else{
			$ctry = 11;
		}
		
		$country = spClass("country");
		$this->list = $country->findAll();
		// 这里使用了spPager，同时用spArgs接受到传入的page参数		
		
		$news = spClass("news");
		$condition = "select id_news,title_news,time_news,content_news  
					from tb_news 
					where type_news in ('succ','acpt')   
					and tb_news.id_univ in 
					(select tb_university.id_univ 
					from tb_university 
					where ctry_univ = $ctry) 
					ORDER BY id_news DESC";

		$succ = $news->spPager($this->spArgs('page', 1), 12)->findSql($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$pager = $news->spPager()->getPager();
		$this->pager = $pager;
		$this->curpage = floor($pager['current_page']/10)*10;
		
		for($i=0;$i<count($succ);$i++){
			if(strstr($succ[$i]['content_news'], 'spptest-upload')){
				$succ[$i]['img'] = substr(strchr($succ[$i]['content_news'],"http://spptest-upload.stor.sinaapp.com"),0,68);
			}else{
				$succ[$i]['img'] = substr(strchr($succ[$i]['content_news'],"/tpl/include/kindeditor/php/upload/image/"),0,74);
			}	
			
		}
		$this->succ = $succ;
		$this->cy = $ctry;
		
		$position="成功案例";
		$this->position = $position;
		
		$this->display("bigshow/succ.html");
	}
	
	function newst(){
		$id_news = $this->spArgs("id"); //接受参数id
		$ctry = $this->spArgs("cy"); //接受参数cy
        $news = spClass("news");
        
        $condition = array('id_news'=>$id_news); // 获取ID
        $result = $news->findAll($condition);//根据ID搜索留学资讯
        $this->results = $result; 
        
        if($result[0]['type_news']=="info"){
        	$position ="留学资讯";
        	$condition0 = "select * from tb_news where type_news = 'info' ORDER BY RAND() limit 3";
        	//$this->related = $news->findAll(array('type_news'=>"info"),'id_news desc',null,$count);
        	$this->related = $news->findSql($condition0);
        	$this->position = $position;
	        $this->id = $id_news;
			$this->display("bigshow/news.html");

        }else if($result[0]['type_news']=="succ" or $result[0]['type_news']=="acpt"){
        	$university = spClass("university");
        	$this->university = $university->findAll(array('id_univ'=>$result[0]['id_univ']));
        	
        	$condition1 = "select id_majr,ename_majr,cname_majr,cname_schl,
					id_univ,ename_univ,cname_ctry,cname_univ,cate_clss     
					from tb_major,tb_school,tb_university,tb_country,tb_classes      
					where univ_majr = id_univ
					and schl_majr = id_schl
					and ctry_univ = id_ctry 
					and ctry_univ = ".$ctry."
					and id_clss  = ".$result[0]['id_clss']."
					and clss_majr = ".$result[0]['id_clss']." LIMIT 6 ";
        	$major = spClass("major");
        	$this->major = $major->findSql($condition1); 

        	$condition0 = "select * from tb_news where type_news = 'succ' or type_news = 'acpt' and id_univ ='".$result[0]['id_univ']."' ORDER BY RAND() limit 5";
        	//$this->related = $news->findAll(array('type_news'=>"info"),'id_news desc',null,$count);
        	$this->related = $news->findSql($condition0);
        	
        	$position="成功案例";
        	$this->position = $position;
	        $this->id = $id_news;
	        $this->cy = $ctry;
			$this->display("bigshow/offer.html");
        }else{
        	$university = spClass("university");
        	$this->university = $university->findAll(array('id_univ'=>$result[0]['id_univ']));

        	$condition1 = "select id_majr,ename_majr,cname_majr,cname_schl,
					id_univ,ename_univ,cname_ctry,cname_univ,cate_clss     
					from tb_major,tb_school,tb_university,tb_country,tb_classes      
					where univ_majr = id_univ
					and schl_majr = id_schl
					and ctry_univ = id_ctry 
					and ctry_univ = ".$ctry."
					and id_clss  = ".$result[0]['id_clss']."
					and clss_majr = ".$result[0]['id_clss']." LIMIT 6 ";
        	$major = spClass("major");
        	$this->major = $major->findSql($condition1); 
        	
        	$position="最新录取";
        }	
	}
	
	//显示专业信息
	public function status(){
		//输出微博信息
		$status = spClass("status");
		$condition1 = "SELECT  sid_stat,text_stat,source_stat,thumbnail_stat,bmiddle_stat,
			pics_stat,created_stat,uid_stat,name_wusr,large_wusr 
			FROM  tb_status ,  tb_wbuser WHERE uid_stat =  uid_wusr and uid_wusr<>0 ORDER BY sid_stat DESC";

		$result = $status->spPager($this->spArgs('page', 1), 10)->findSql($condition1); 
		
		// 这里获取分页数据并发送到smarty模板内
		$pager = $status->spPager()->getPager();
		$this->pager = $pager;
		$this->curpage = floor($pager['current_page']/10)*10;

		$pics = spClass("pics");
		$condition = " sid_pics >= ".$result[count($result)-1]['sid_stat']." and sid_pics <= ".$result[0]['sid_stat'];
		$this->pics = $pics->findAll($condition); 

		$this->result = $result;
		$this->display("bigshow/status.html"); // 显示模板
	}

	//显示学校信息
	public function univ(){
		//输出国家列表
		$country = spClass("country");
		$this->list = $country->findAll(); 
		$ctry = $this->spArgs("cy"); //接受参数cy
		
		$conditionc = array('id_ctry'=>$ctry);
		//返回所选国家信息
		$this->country = $country->findAll($conditionc); 
		
		$university = spClass("university");
		$condition = array('ctry_univ'=>$ctry);
		//输出学校列表
		$this->university = $university->spPager($this->spArgs('page', 1), 10)->findAll($condition, " rank_univ ASC "); 
		$pager = $university->spPager()->getPager();
		$this->pager = $pager;
		$this->curpage = floor($pager['current_page']/10)*10;

		$this->ctry = $ctry;
		$this->position = "学校目录";
		
		$this->display("bigshow/univ.html"); // 显示模板
	}
	
	//显示专业大类信息
	public function clss(){
		//输出专业大类选项
		$category = spClass("category");
		$this->list = $category->findAll(); 
		$cate = $this->spArgs("cs"); //接受参数cs
		
		//输出专业小类选项
		$classes = spClass("classes");
		$condition = array('cate_clss'=>$cate);
		$class = $classes->findAll($condition);
		$this->classes = $class;
		if($this->spArgs("cl")){
			$cl = $this->spArgs("cl"); 
		}else{
			$cl = $class[0]['id_clss'];
		}

		//输出国家选项
		$country = spClass("country");
		$ctry = $country->findAll(); 
		$this->country = $ctry;
		if($this->spArgs("cy")){
			$cy = $this->spArgs("cy"); 
		}else{
			$cy = $ctry[0]['id_ctry'];
		}
				
		//输出专业小类列表
		$condition = "select id_majr,ename_majr,cname_majr,cname_schl,desc_majr,logo_univ,
					id_univ,ename_univ,cname_ctry,cname_univ   
					from tb_major,tb_school,tb_university,tb_country     
					where univ_majr = id_univ
					and schl_majr = id_schl
					and ctry_univ = id_ctry 
					and clss_majr = $cl 
					and ctry_univ = $cy ";
		$major = spClass("major");
		$this->major = $major->spPager($this->spArgs('page', 1), 10)->findSql($condition); 
		$pager = $major->spPager()->getPager();
		$this->pager = $pager;
		$this->curpage = floor($pager['current_page']/10)*10;
						
		//返回参数
		$this->cate = $cate;
		$this->cl = $cl;
		$this->cy = $cy;
		$this->position = "专业筛选";
		
		$this->display("bigshow/majr.html"); // 显示模板
	}
	
	//显示专业小类下专业列表
	public function major(){
		//输出专业信息
		$major = spClass("major");
		$univ_id = $this->spArgs("id"); //接受参数id
		$ctry = $this->spArgs("cy"); //接受参数cy
				
		$condition = array('univ_majr'=>$univ_id);
		$this->major = $major->findAll($condition); 
		//输出学院信息
		$school = spClass("school");
		$condition1 = array('univ_schl'=>$univ_id);
		$this->school = $school->findAll($condition1); 
		//输出学校信息
		$university = spClass("university");
		$condition2 = array('id_univ'=>$univ_id);
		$this->univ = $university->findAll($condition2); 
		//输出成功案例
		$news = spClass("news");
		//$condition3 = array('id_univ'=>$univ_id,'type_news'=>"succ");
		
		$condition3 = "  ( type_news = 'succ' OR type_news = 'acpt' ) AND id_univ = '".$univ_id."' ";
		
		$succ = $news->spPager($this->spArgs('page', 1), 12)->findAll($condition3," time_news DESC "); 
		$pager = $news->spPager()->getPager();
		$this->pager = $pager;
		$this->curpage = floor($pager['current_page']/10)*10;
		
		$imgs = array();
		for($i=0;$i<count($succ);$i++){
			$imgs[$i]['title'] = substr($succ[$i]['title_news'],0,100);				
			$imgs[$i]['content'] = strip_tags($succ[$i]['content_news']);
			$imgs[$i]['time'] = substr($succ[$i]['time_news'],0,10);
			$imgs[$i]['id'] = $succ[$i]['id_news'];	


			if(strstr($succ[$i]['content_news'], 'spptest-upload')){
				$imgs[$i]['img'] = substr(strchr($succ[$i]['content_news'],"http://spptest-upload.stor.sinaapp.com"),0,68);
			}else{
				$imgs[$i]['img'] = substr(strchr($succ[$i]['content_news'],"/tpl/include/kindeditor/php/upload/image/"),0,74);
			}		
		}
		
        if($this->spArgs("sc")){
        	$this->sc = $this->spArgs("sc");
        }
        else if( $ctry == 10 || $ctry == 11){
            $this->sc = 1;
    	}else{
			$this->sc = 0;	
		}; 
		
		$this->imgs = $imgs;
		$this->cy = $ctry;
		
		$this->position = "学校介绍";
        
        if( $ctry == 12 ){
        	$this->display("bigshow/gate.html"); // 显示模板
        }else{
        	$this->display("bigshow/gate.html"); // 显示模板
        }
	}
	
	//显示专业信息
	public function majrv(){
		$id = $this->spArgs("id");
		$condition = "select id_univ,id_majr,cname_majr,ename_majr,
       					ename_schl,ename_univ,desc_univ,rank_univ,				
						cname_schl,cname_univ,cname_clss,logo_univ,
        				cname_cate, rank_majr,py_univ,ctry_univ,
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
 		$this->position = "专业详细信息";
       	$this->display("bigshow/detail.html"); 
	}
	
	//显示最新录取信息
	public function acpt(){
		if($this->spArgs("cy")){
			$ctry = $this->spArgs("cy"); //接受参数cy
		}else{
			$ctry = 11;
		}
		
		$country = spClass("country");
		$this->list = $country->findAll();
		
		$news = spClass("news");
		$condition = "select id_news,title_news,content_news  
					from tb_news 
					where type_news = 'acpt'  
					and tb_news.id_univ in 
					(select tb_university.id_univ 
					from tb_university 
					where ctry_univ = $ctry) 
					ORDER BY appdate_new DESC";
		

		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$acpt = $news->spPager($this->spArgs('page', 1), 20)->findSql($condition); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $news->spPager()->getPager();

		for($i=0;$i<count($acpt);$i++){
			$acpt[$i]['img'] = substr(strchr($acpt[$i]['content_news'],"http://spptest-upload.stor.sinaapp.com"),0,68);		
		}
		$this->acpt = $acpt;
		$this->cy = $ctry;
		
		$this->position = "最新录取";
		$this->display("bigshow/acpt.html"); // 显示模板，这里使用的模板是根目录/tpl/green/index.html。
	}
	
	//进入评估表
	public function msgn(){
		$this->position = "在线评估";
		$this->display("bigshow/evaluate.html"); 
	}
	
	//提交留言信息
	public function msgd(){
		$name = $this->spArgs("name"); //接受参数name
        $tel = $this->spArgs("tel"); //接受参数tel
		$mail = $this->spArgs("mail"); //接受参数mail
		$content = $this->spArgs("content"); //接受参数content
                
        if($name=="" ||$tel=="" || $mail=="" ||strstr($content,"http://")||strstr($mail,"hacker")||strstr($name,"8888")){
        	$this->success("评估表提交成功！", spUrl("main","index"));
        }else{  
          	
        	$adm = $this->spArgs("admdate"); //接受参数admdate
			$gender= $this->spArgs("gender"); //接受参数gender		
			$university = $this->spArgs("university"); //接受参数university
			$school= $this->spArgs("school"); //接受参数school
			$major = $this->spArgs("major"); //接受参数major
			$gpa = $this->spArgs("gpa"); //接受参数gpa
			$qq = $this->spArgs("qq"); //接受参数qq
			$cet4 = $this->spArgs("cet4"); //接受参数cet4
			$cet6 = $this->spArgs("cet6"); //接受参数cet6
			$it = $this->spArgs("it"); //接受参数it
			$et = $this->spArgs("et"); //接受参数et
		
			$object = $this->spArgs("object"); //接受参数object
			$country = $this->spArgs("country"); //接受参数country
			$purpose = $this->spArgs("purpose"); //接受参数purpose
			$money = $this->spArgs("money"); //接受参数money
		
		
			$appuniv = $this->spArgs("appuniv"); //接受参数appuniv
			$appmajor = $this->spArgs("appmajor"); //接受参数appmajor
				
			$message = spClass("message");
			$newrow = array( // PHP的数组
				'gender_msge' => $gender,
				'name_msge' => $name,
				'mail_msge' => $mail,
				'tel_msge'  => $tel,
				'adm_msge'  => $adm,
				'univ_msge' => $university,
				'schl_msge' => $school,
				'majr_msge' => $major,
				'gpa_msge'  => $gpa,
				'qq_msge'  => $qq,
				'it_msge' => $it,
				'et_msge' => $et,
				'cet4_msge' => $cet4,
				'cet6_msge' => $cet6,
				'obj_msge' => $object,
				'ctry_msge' => $country,
				'purp_msge' => $purpose,
				'money_msge' => $money,
				'content_msge' => $content,
				'apyuniv_msge' => $appuniv,
				'apymajor_msge' => $appmajor,
				'time_msge' => date('Y-m-d H:i:s'),
			);
			$message->create($newrow);
            
            $this->success("评估表提交成功！");
            $this->position = "在线评估";
            $this->display("bigshow/submit.html");
		
			$mails = spClass("user");
			$condition = array('name_user'=> 'send');       
			$auto = $mails->findAll($condition);
			if($auto[0]['pswd_user'] == 1){
			
				$genderc = "女";
				if ($gender == 1){ $genderc="男";}
		
				$moneyc="30万RMB以上";
				if ($money==1){
					$moneyc="10万RMB以下";
				}else if($money==2){
					$moneyc="10-20万RMB";
				}else if($money==3){
					$moneyc="20-30万RMB";
				}
		
				$enter = "\n";
				$content = "个人信息".$enter
				."姓名:".$name.$enter
				."性别:".$genderc.$enter
				."邮箱:".$mail.$enter
				."手机:".$tel.$enter
				."QQ:".$qq.$enter
				."入学年份:".$adm.$enter.$enter
				."教育背景".$enter
				."毕业学校:".$university.$enter
				."所在院系:".$school.$enter
				."所学专业:".$major.$enter
				."平均成绩:".$gpa.$enter.$enter
				."英语成绩:".$enter
				."英语4级:".$cet4.$enter
				."英语6级:".$cet6.$enter
				."雅思/托福:".$it.$enter
				."GRE/GMAT:".$et.$enter.$enter
				."申请目标:".$enter
				."申请学位:".$object.$enter
				."留学区域:".$country.$enter
				."留学动机:".$purpose.$enter
				."存款证明:".$moneyc.$enter
				."补充信息:".$enter
				."申请学位:".$$content.$enter
                ."留言时间:".date('Y-m-d H:i:s');	
                
				$mailsend = new SaeMail();
        		$mailsend->setOpt( array(
        		'from' => 'rioshyb@163.com',
        		'to' => $auto[0]['email_user'],
        		'smtp_host' => 'smtp.163.com', 
        		'smtp_username' => 'rioshyb@163.com',  
        		'smtp_password' => '87895841',  
        		'subject' => '指南者评估表：'.$name,  
        		'content' => $content, 
       			) );     
                		
				//$ret = $mailsend->send(); 
                //if ($ret === false){
                //	var_dump($mailsend->errno(), $mailsend->errmsg());
                //}else{
                	$this->success("评估表提交成功！");
            		$this->position = "在线评估";
            		$this->display("bigshow/submit.html");
                //}
			}
            
        }
	}
	
	//邮件发送函数
	public function send($content,$name,$receive){
		$mail = new SaeMail();
        $mail->setOpt( array(
        	'from' => 'hyb19852004@163.com',
        	'to' => $receive,
        	'smtp_host' => 'smtp.163.com', 
        	'smtp_username' => 'hyb19852004@163.com',  
        	'smtp_password' => '87895841',  
        	'subject' => '指南者评估表：'.$name,  
        	'content' => $content, 
            
            
            'from' => 'admin@compassedu.hk',
        		'to' => $auto[0]['email_user'],
        		'smtp_host' => 'smtp.exmail.qq.com', 
        		'smtp_username' => 'admin@compassedu.hk',  
        		'smtp_password' => 'zhinanzhe123',  
        		'subject' => '指南者评估表：'.$name,  
        		'content' => $content,
       	) );     
                		
		$ret = $mail->send(); 
		if ($ret === false){
			var_dump($mail->errno(), $mail->errmsg());
		}
		//else{
		//	$row = array('send_msge'=>date('Y-m-d H:i:s'),'recer_msge'=>$receive);
		//	$this->refresh = $message->update($condition, $row); 
		//}
	}
	
	
// 显示用户登录框以及验证用户登录情况
	public function login(){
		import("spAcl.php"); // 引入Acl文件，使得可以生成加密的密码输入框
		$userObj = spClass("custom"); // 实例化lib_user类
		if( $uname = $this->spArgs("uname") ){ // 已经提交，这里开始进行登录验证
			//$upass = spClass("spAcl")->pwvalue(); // 通过acl的pwvalue获取提交的加密密码
			$upass=md5($this->spArgs("upass"));
			// 使用spVerifier进行第一次检查
			$rows = array('name_cust' => $uname, 'pswd_cust' => $upass);
			$results = $userObj->spVerifier($rows);
			
			if( false == $results ){ // 当spVerifier返回false的时候，则是表示已经通过验证，数据是合格的
			
				// 使用lib_user类中我们新建的userlogin方法来验证用户名和密码
				if( false == $userObj->userlogin($uname, $upass) ){
					// 登录失败，提示后跳转回登录页面
					$this->error("用户名/密码错误，请重新输入！", spUrl("main","index"));
					
				}else{
					// 成功登录，跳转。这里要进行判断一下：
					// 如果用户角色是GBADMIN（管理员）则跳转到admin/index的管理中心
					// 如果用户角色是GBUSER（普通会员）则跳转回首页
					$useracl = spClass("spAcl")->get(); // 通过acl的get可以获取到当前用户的角色标识
					if( "CUSTOM" == $useracl ){
						$this->success("登录成功，欢迎您，尊敬的客户！", spUrl("main","order",array("ts"=>1)));
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
						$this->error($msg,spUrl("main","index"));
						$this->display("bigshow/index.html");
					}
				}
			}
		}
		$this->display("bigshow/index.html");
		// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单
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
	
	//显示客户中心
	function custom(){
		if( $_SESSION["custominfo"]=="" ){
			// 登录失败，提示后跳转回登录页面
			$this->error("请先登录，谢谢！！", spUrl("main","index"));
					
		}else{
			$id = $_SESSION["custominfo"]["id_cust"];
			$custom = spClass("custom");
       		$condition = array('id_cust'=>$id); // 获取ID
       		       		
       		$this->custom = $custom->findAll($condition);  //根据ID搜索客户信息
			$position="个人信息";
			$this->position = $position;
			$this->display("bigshow/pers.html");
		}
	}
	//显示帮助内容
	function newsif(){
		$id_news = $this->spArgs("id"); //接受参数id 
        $news = spClass("news");  //
        
        $condition = array('id_news'=>$id_news); // 获取ID
        $result = $news->findAll($condition);//根据ID搜索留学资讯
        $this->results = $result; 
               
		$this->position = "帮助中心";
        $this->id = $id_news;
		$this->display("bigshow/infoph.html");
	}
	//显示帮助中心
	function help(){
		//$news = spClass("news");
		//$condition = array('type_news'=>"help");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		//$this->results = $news->spPager($this->spArgs('page', 1), 20)->findAll($condition," id_news DESC "); 
		// 这里获取分页数据并发送到smarty模板内
		//$this->pager = $news->spPager()->getPager();
		
		$position="帮助中心";
		$this->position = $position;
		$this->display("bigshow/help.html");
	}
	// 客户修改信息
	public function editc(){
		$id = $this->spArgs("id"); //接受参数id 
		$custom = spClass("custom");
       	$condition = array('id_cust'=>$id); // 获取ID
       	$result = $custom->findAll($condition);  //根据ID搜索客户信息
        $this->custom = $result;
       	$this->position = "客户中心";
        $this->id = $id;
        $this->display("bigshow/customif.html");
	}
	
	// 保存客户信息
	public function savec(){
		$id = $this->spArgs("id"); //接受参数id 
		$univ = $this->spArgs("univ"); //接受参数univ
		$major = $this->spArgs("major"); //接受参数major
		$english = $this->spArgs("english"); //接受参数english
		$email = $this->spArgs("email"); //接受参数email
		$address = $this->spArgs("address"); //接受参数address
		$code = $this->spArgs("code"); //接受参数code
		$tel = $this->spArgs("tel"); //接受参数tel

		$custom = spClass("custom");
		$condition = array('id_cust'=>$id); // 获取ID
        $row = array(
        'univ_cust'=>$univ,
        'major_cust'=>$major,
        'english_cust'=>$english,
        'email_cust'=>$email,
        'address_cust'=>$address,
        'code_cust'=>$code,
        'tel_cust'=>$tel,
        );
        $this->info = $custom->update($condition, $row);   //根据ID修改留学资讯
        $this->success("修改成功，请继续使用！", spUrl("main","order",array("ts"=>1)));
	}
	
	// 修改密码界面
	public function pswd(){
		$position="修改密码";
		$this->position = $position;
		$this->display("bigshow/pass.html"); // 显示模板
	}
	
	// 客户修改密码
	public function pwsde(){
		$userObj = spClass("custom"); // 实例化lib_user类
		if( $uname = $this->spArgs("name") ){ // 已经提交，这里开始进行登录验证
			//$upass = spClass("spAcl")->pwvalue(); // 通过acl的pwvalue获取提交的加密密码
			$opass=md5($this->spArgs("opass"));
			$npass=md5($this->spArgs("npass"));
			// 使用spVerifier进行第一次检查
			$rows = array('name_cust' => $uname, 'pswd_cust' => $opass);
			$results = $userObj->spVerifier($rows);
			
			if( false == $results ){ // 当spVerifier返回false的时候，则是表示已经通过验证，数据是合格的
			
				// 使用lib_user类中我们新建的userlogin方法来验证用户名和密码
				if( false == $userObj->userlogin($uname, $opass) ){
					// 登录失败，提示后跳转回登录页面
					$this->error("原始密码错误，请重新输入！", spUrl("main","pswd"));	
				}else{
					$condition = array('name_cust'=>$uname); // 获取用户名
					$row = array('pswd_cust'=>$npass);
        			$userObj->update($condition, $row);   //根据ID修改留学资讯
        			$this->success("密码修改成功！", spUrl("main","order",array("ts"=>1)));
				}
			}else{
				// $results不是false，所以没有通过验证，错误信息是$results
				// dump($results);
				foreach($results as $item){ // 开始循环错误信息的规则，这里只有用户名
					// 每一个规则，都有可能返回多个错误信息，所以这里我们也循环$item来获取多个信息
					foreach($item as $msg){ 
						// 虽然我们使用了循环，但是这里我们只需要第一条出错信息就行。
						// 所以取到了第一条错误信息的时候，我们使用$this->error来提示并跳转
						$this->error($msg,spUrl("main","index"));
						$this->display("bigshow/index.html");
					}
				}
			}
		}
		$this->display("bigshow/index.html");
		// 这里是还没有填入用户名，所以将自动显示main_login.html的登录表单
	}
	
	//显示客户订单
	function order(){
		if( $_SESSION["custominfo"]=="" ){
			// 登录失败，提示后跳转回登录页面
			$this->error("请先登录，谢谢！！", spUrl("main","index"));		
		}else{
			$id = $_SESSION["custominfo"]['id_cust'];
			$types = $this->spArgs("ts");
			if($types ==1){
				$condition = "select id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,
						item_order,price_order,desc_order,state_order  
						from tb_order,tb_custom  
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order = 'WAIT_BUYER_PAY' 
                                                and refund_order = 'NO_REFUND' ";
			}else if($types ==2){
				$condition = "select id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,
						item_order,price_order,pt_order,state_order  
						from tb_order,tb_custom  
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order = 'WAIT_SELLER_SEND_GOODS' 
                                                and refund_order = 'NO_REFUND' ";
			}else if($types ==3){
				$condition = "select id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,
						item_order,price_order,pt_order,state_order  
						from tb_order,tb_custom  
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order = 'WAIT_BUYER_CONFIRM_GOODS' 
                                                and refund_order = 'NO_REFUND' ";
			}else if($types ==4){
				$condition = "select id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,
						item_order,price_order,pt_order,state_order  
						from tb_order,tb_custom  
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order in('TRADE_FINISHED','TRADE_CLOSED') 
                                                and refund_order = 'NO_REFUND' ";
			}else {
				$condition = "select id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,
						item_order,price_order,pt_order,state_order,refund_order  
						from tb_order,tb_custom  
						where id_cust = custom_order 
						and id_cust = $id 	
						and refund_order != 'NO_REFUND' ";
			}	
			
			$order = spClass("order");
			$this->orders = $order->findSql($condition); 
			
			
			$position="我的订单";
			$this->position = $position;
			$this->ts = $types;
			
			
			if($types ==1){
				$this->display("bigshow/ordr.html");
			}else if($types ==2){
				$this->display("bigshow/paid.html");
			}else if($types ==3){
				$this->display("bigshow/wait.html");
			}else if($types ==4){
				$this->display("bigshow/cplt.html");
			}else{
				$this->display("bigshow/refd.html");
			}	
		}
	}
	//显示材料清单
	function mate(){
		$doc = new DOMDocument();  
  		$doc->load('tpl/xml/mate.xml');         //读取xml文件
		if( $_SESSION["custominfo"]=="" ){
			// 登录失败，提示后跳转回登录页面
			$this->error("请先登录，谢谢！！", spUrl("main","index"));		
		}else{
			$id = $_SESSION["custominfo"]['id_cust'];
			$condition = array('cust_mate'=>$id,'show_mate'=>1);
			$mate = spClass("mate");
			$mates = $mate->find($condition); 
			$material = array( 
					'recd1_mate' => 0,
					'recd2_mate' => 0,
					'cv_mate' => 0,
					'ps_mate' => 0,
					'aply_mate' => 0,
					'pic_mate' => 0,
					'acdc_mate' => 0,
				);
			if($mates){
  				foreach(array_keys($mates) as $key){
  					if($key=="acdc_mate" ){
						$others = $doc->getElementsByTagName( "em" );
  						foreach( $others as $other ) {  
  							$vlaues = $other->getElementsByTagName( "value" );  
						  	$vlaue = $vlaues->item(0)->nodeValue;
  							if($mates[$key]==$vlaue){
  								$idm = $other->getElementsByTagName( "key" );         
						  		$idms = $idm->item(0)->nodeValue;  
						  		$material[$key]=$idms;
  							}
						} 
  					}elseif ($key=="aply_mate") {
  						$others = $doc->getElementsByTagName( "apply" );
  						foreach( $others as $other ) {  
  							$vlaues = $other->getElementsByTagName( "value" );  
						  	$vlaue = $vlaues->item(0)->nodeValue;
  							if($mates[$key]==$vlaue){
  								$idm = $other->getElementsByTagName( "key" );         
						  		$idms = $idm->item(0)->nodeValue;  
						  		$material[$key]=$idms;
  							}
						} 
  					}elseif ($key=="pic_mate") {
  						$others = $doc->getElementsByTagName( "pm" );
  						foreach( $others as $other ) {  
  							$vlaues = $other->getElementsByTagName( "value" );  
						  	$vlaue = $vlaues->item(0)->nodeValue;
  							if($mates[$key]==$vlaue){
  								$idm = $other->getElementsByTagName( "key" );         
						  		$idms = $idm->item(0)->nodeValue;  
						  		$material[$key]=$idms;
  							}
						} 
  					}else{
  						$others = $doc->getElementsByTagName( "mt" );
  						foreach( $others as $other ) {  
  							$vlaues = $other->getElementsByTagName( "value" );  
						  	$vlaue = $vlaues->item(0)->nodeValue;
  							if($mates[$key]==$vlaue){
  								$idm = $other->getElementsByTagName( "key" );         
						  		$idms = $idm->item(0)->nodeValue;  
						  		$material[$key]=$idms;
  							}
						} 
  					}
  				}
			}				
		}
		$position="材料清单";
		$this->position = $position;
		$this->counts = $material;
		$this->display("bigshow/mate.html");
	}
	
	//显示行程准备
	function tour(){
		if( $_SESSION["custominfo"]=="" ){
			// 登录失败，提示后跳转回登录页面
			$this->error("请先登录，谢谢！！", spUrl("main","index"));		
		}else{
			$id = $_SESSION["custominfo"]['id_cust'];
			$condition = array('cust_tour'=>$id,'show_tour'=>1);
			$tour = spClass("tour");
			$this->tour = $tour->findAll($condition); 
		}
		$position="行程准备";
		$this->position = $position;
		$this->display("bigshow/tour.html");
	}
	
	//显示申请进度
	function aply(){
		if( $_SESSION["custominfo"]=="" ){
			// 登录失败，提示后跳转回登录页面
			$this->error("请先登录，谢谢！！", spUrl("main","index"));		
		}else{
			$id = $_SESSION["custominfo"]['id_cust'];
			$condition = array('cust_aply'=>$id,'show_aply'=>1);
			$aply = spClass("aply");
			$apply = $aply->findAll($condition); 

		}
		$position="学校申请";
		$this->position = $position;
		$this->aply = $apply;
		$this->display("bigshow/aply.html");
	}
        
        //关于我们
	function about(){
        //$news = spClass("news"); 
        //$condition = array('type_news'=>"aboutus"); // 获取ID
        //$result = $news->findAll($condition);//根据ID搜索留学资讯
        ///$this->results = $result; 
        //$position="关于我们";
		//$this->position = $position;
        //$this->id = $id_news;
		$this->display("bigshow/about.html");
	}
    //支付宝付款
	function alip(){
		$this->display("bigshow/alipay.html");
	}    
	//联系方式
	function service(){
        //$position="联系方式";
		//$this->position = $position;
		$this->display("bigshow/service.html");
	}
    //重置
	function reset(){
		$this->display("bigshow/reset.html");
	}
	//帮助中心介绍
	function helptl(){
		$this->display("bigshow/help_iframe.html");
	}
	//帮助中心加载iframe
	function helpic(){
		$this->display("bigshow/help/help.html");
	}
    //检测微信号是否绑定
	public function checkw($wcname){
		$wcuser = spClass("wcuser");
		$conditions = array('wcuser_wcur' => $wcname);
		$result = $wcuser->find($conditions);
		return $result;
	}
    //微信绑定页面
	function weix(){
        $id =  $this->spArgs("id");
        if($this->checkw($id)){
        	$this->success("您的微信账号已绑定！", spUrl("main","weis"));
        }else{
        	$this->id = $id;
            $this->display("bigshow/weixin.html");
        }
	}
     //解除微信绑定页面
	function weid(){
        $id =  $this->spArgs("id");
        if($this->checkw($id)){
            $this->id = $id;
			$this->display("bigshow/weixin_cancel.html");	
        }else{
        	$this->success("您的微信账号已解除绑定！", spUrl("main","weir"));
        }
	}
    //微信绑定成功页面
	function weis(){
		$this->display("bigshow/weixin_succ.html");
	}
     //微信绑定解除成功页面
	function weir(){
		$this->display("bigshow/weixin_remove.html");
	}
	//绑定账号
	function bind(){
		$id = $this->spArgs("id");
		$name = $this->spArgs("uname");
		$pass=md5($this->spArgs("upass"));
		$wcuser = spClass("wcuser");
		$custom = spClass("custom");
		
		if($result = $custom->find(array('name_cust' => $name,'pswd_cust' => $pass))){
			$newrow = array( // PHP的数组
					'uid_wcur' => $result['id_cust'],
					'name_wcur' => $result['cname_cust'],
					'user_wcur' => $name,
					'wcuser_wcur' => $id,
				);
				$wcuser->create($newrow);
			$this->success("账号绑定成功！", spUrl("main","weis"));	
		}else{
			$this->error("用户名/密码错误，请重新输入！", spUrl("main","weix",array("id"=>$id)));
		}
	}
    //解除绑定账号
	function delw(){
		$id = $this->spArgs("id");
		$name = $this->spArgs("uname");
		$pass=md5($this->spArgs("upass"));
		$wcuser = spClass("wcuser");
		$custom = spClass("custom");
        
        if( $wcuser->find(array('user_wcur' => $name,'wcuser_wcur' => $id)) ){
		
			if($result = $custom->find(array('name_cust' => $name,'pswd_cust' => $pass))){

            	$wcuser->delete(array('wcuser_wcur'=>$id));
			
				$this->success("解除绑定成功！", spUrl("main","weir"));	
			}else{
				$this->error("用户名/密码错误，请重新输入！", spUrl("main","weid",array("id"=>$id)));
			}
        }else{
            $this->error("用户名和绑定微信号不符，请重新输入！", spUrl("main","weid",array("id"=>$id)));
        }
	}



	//显示国家排名信息
	function ctry(){
		if($this->spArgs("rn")){
			$rn = $this->spArgs("rn"); //接受参数cg
			$rn=(int)$rn;
		}else{
			$rn = 0;  //第一次1取前10名；
		}
		
		
		
		if($this->spArgs("cg")){
			$cg = $this->spArgs("cg"); //接受参数cg
		}else{
			$cg = 1;  //默认工科
		}
		if($this->spArgs("cs")){
			$cs = $this->spArgs("cs"); //接受参数cls
		}else{
			$cs = 25;  //默认机械工程
		}
		
		//专业类别
		$cate=spClass("category");
		$this->cate = $cate->findAll(); // 返回所有专业大类
		
		//该专业类别下的所有专业
		$clss=spClass("classes");
		$condition1=array('cate_clss'=>$cg);
		$this->clss = $clss->findAll($condition1); // 返回所属专业
		
		
		
		//专业排名的大学信息
		$rank=spClass("rank");
		$sql="select id_rank,num_rank,univ_rank,univ from tb_rank where clss_rank=$cs limit $rn,10";
		
		$this->results = $rank->findSql($sql);
		$this->cg =$cg;
		$this->cs =$cs;
		$this->rn =$rn;
		
		$this->display("bigshow/ctry.html");

	}



}	