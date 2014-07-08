<?php
class super extends spController
{		
	// 删除留言，这里是权限是只有GBADMIN才能执行
	public function del(){
		// 这里先判断是否传入了gid
		$page = $this->spArgs("page");
		if( $id = $this->spArgs("id") ){
			// 执行删除
			spClass("news")->delete(array('id_news'=>$id));
			$this->success("删除成功！", spUrl("admin","news",array('page'=>$page)));
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","news",array('page'=>$page)));
		}
	}
	
	// 删除评估表
	public function delm(){
		// 这里先判断是否传入了gid
		$page = $this->spArgs("page");
		if( $id = $this->spArgs("id") ){
			// 执行删除
			spClass("message")->delete(array('id_msge'=>$id));
			$this->success("删除成功！", spUrl("admin","msge",array('page'=>$page)));
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","msge",array('page'=>$page)));
		}
	}
	
	// 删除国家，若国家下由学校，不予删除
	public function delc(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			$condition = array('ctry_univ'=>$id); // 获取ID
			$result = spClass("university")->findAll($condition);
			if($result){
				$this->error("该国家下有学校，不可删！", spUrl("admin","ctry"));
			}else{
				//获取url图片名称                            
    $countrypic = spClass("country")->findAll(array('id_ctry'=>$id), null, 'pic_ctry', null);
    $url_array = explode("/", $countrypic[0]['pic_ctry']);
    // storage删除pic
    $s = new SaeStorage(); 
    $s->delete (DOMAIN,$url_array[3]);
    unset($s);
    // 执行删除
				spClass("country")->delete(array('id_ctry'=>$id));
				$this->success("删除成功！", spUrl("admin","ctry"));
			}
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","ctry"));
		}
	}
	
 	// 删除专业小类，若专业小类下有专业，不予删除
	public function dels(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
   		   //补规则    ！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！         
   			$condition = array('clss_majr'=>$id); // 获取ID
   			$result = spClass("major")->findAll($condition);
   			if($result){
   				$this->error("该专业小类下有专业，不可删！", spUrl("admin","clssc"));
   			}else{
   			//获取url图片名称                            
   			 	$opic = spClass("classes")->findAll(array('id_clss'=>$id), null, 'pic_clss', null);
    			$url_array = explode("/", $opic[0]['pic_clss']);
    			// storage删除pic
    			$s = new SaeStorage(); 
    			$s->delete (DOMAIN,$url_array[3]);
    			unset($s);
    		// 执行删除
				spClass("classes")->delete(array('id_clss'=>$id));
				$this->success("删除成功！", spUrl("admin","clssc"));
            }
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","clssc"));
		}
	}       
        
	// 删除学院，若学院下有专业，不予删除
	public function dell(){
	// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			//记得补规则！！！！！！！！！！！！！！！！！
			$condition = array('schl_majr'=>$id); // 获取ID
			$result = spClass("major")->findAll($condition);
			if($result){
				$this->error("该学院下有专业，不可删！", spUrl("admin","schl"));
			}else{
				// 执行删除
				spClass("school")->delete(array('id_schl'=>$id));
                                
                                
                                // 网站数据同步
                        	$f = new SaeFetchurl();
  				$f->setMethod("post");
  				$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id,
    				)
  				);
  				$f->fetch("http://www.wayabroad.sinaapp.com/synch/schl_del.php");
                        	$ntc =  "OK";
                  		if($f->errno() != 0){ 
                  			$ntc = $f->errmsg();
                        	}                           
                                
                                
				$this->success("删除成功！", spUrl("admin","schl"));
                                
                                 
			}
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","schl"));
		}
	}  
	
	// 删除学校，若学校下有学院，不予删除
	public function delu(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			$condition = array('univ_schl'=>$id); // 获取ID
   			$result = spClass("school")->findAll($condition);
   			if($result){
   				$this->error("该学校下有学院，不可删！", spUrl("admin","univc"));
   			}else{
				// 执行删除
				spClass("university")->delete(array('id_univ'=>$id));
                       
                                
				
                                 // 网站数据同步
                        	$f = new SaeFetchurl();
  				$f->setMethod("post");
  				$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id,
    				)
  			);
  			$f->fetch("http://www.wayabroad.sinaapp.com/synch/univ_del.php");
                        $ntc =  "OK";
                  	if($f->errno() != 0){ 
                  		$ntc = $f->errmsg();
                        }
                          
                        
                        $this->success("删除成功！", spUrl("admin","univc"));                                     
                                
			}
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","univc"));
		}
	}
	
	// 删除专业大类，若专业大类下有专业，不予删除
	public function dely(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			//记得补规则！！！！！！！！！！！！！！！！！
			$condition = array('cate_clss'=>$id); // 获取ID
			$result = spClass("classes")->findAll($condition);
			if($result){
				$this->error("该大类下有专业，不可删！", spUrl("admin","cate"));
			}else{
				// 执行删除
				spClass("category")->delete(array('id_cate'=>$id));
				$this->success("删除成功！", spUrl("admin","cate"));
			}
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","cate"));
		}
	}
	
	// 删除专业
	public function delr(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
			spClass("major")->delete(array('id_majr'=>$id));

                        // 网站数据同步
                        $f = new SaeFetchurl();
  			$f->setMethod("post");
  			$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id,
    				)
  			);
  			$f->fetch("http://www.wayabroad.sinaapp.com/synch/major_del.php");
                        $ntc =  "OK";
                  	if($f->errno() != 0){ 
                  		$ntc = $f->errmsg();
                        }
                        
                        
			$this->success("删除成功！", spUrl("admin","majrl"));
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("admin","majrl"));
		}
	}
	
	// 修改案例，录取，这里是权限是只有GBADMIN才能执行
	public function edit(){
		$id = $this->spArgs("id"); //接受参数id 
		$page = $this->spArgs("page"); //接受参数page
        $news = spClass("news");  //
        $condition = array('id_news'=>$id); // 获取ID
        $this->info = $news->findAll($condition);  //根据ID搜索留学资讯
        $this->page = $page;
        $this->id = $id;
        $this->selected = " selected";

        $this->id_univ = $this->info[0]["id_univ"];
        $this->id_clss = $this->info[0]["id_clss"];

        //返回国家信息
		$country = spClass("country");
		$this->country = $country->findAll(); 
		
		//返回学校信息

		$university = spClass("university");

        //获取选中学校对应的国家
        $conditions = array('id_univ' => $this->id_univ);
		$this->ctry_univ = $university->find($conditions); 

        if($this->ctry_univ){

            $this->ctry_univ = $this->ctry_univ["ctry_univ"]; 

            $sqluniv = "SELECT id_univ,cname_univ 
                        FROM tb_university 
                        where ctry_univ=".$this->ctry_univ;

            $this->university = $university->findSql($sqluniv); 
       }else{
            $this->university =null;
       }

		//返回专业大类列表
		$category = spClass("category");
		$this->category = $category->findAll(); 
        
		//返回专业小类信息

		$classes = spClass("classes");

        //获取选中专业对应的专业大类
        $conditions = array('id_clss' => $this->id_clss);
		$this->cate_clss  = $classes->find($conditions); 
    
        if($this->cate_clss){

            $this->cate_clss = $this->cate_clss["cate_clss"]; 
            $sqlclss = "SELECT id_clss,cname_clss 
                        FROM tb_classes  
                        where cate_clss=".$this->cate_clss;

            $this->classes = $classes->findSql($sqlclss); 
        }else{
            $this->classes = null;
        }
        $this->display("admin/edit.html");
	}

	// 修改资讯，这里是权限是只有GBADMIN才能执行
	public function editn(){
		$id = $this->spArgs("id"); //接受参数id 
		$page = $this->spArgs("page"); //接受参数page
        $news = spClass("news");  //
        $condition = array('id_news'=>$id); // 获取ID
        $this->info = $news->findAll($condition);  //根据ID搜索留学资讯
        $this->page = $page;
        $this->id = $id;
        $this->selected = " selected";

        $this->id_univ = $this->info[0]["id_univ"];
        $this->id_clss = $this->info[0]["id_clss"];

        //返回国家信息
		$country = spClass("country");
		$this->country = $country->findAll(); 
		
		//返回学校信息

		$university = spClass("university");

        //获取选中学校对应的国家
        $conditions = array('id_univ' => $this->id_univ);
		$this->ctry_univ = $university->find($conditions); 

        if($this->ctry_univ){

            $this->ctry_univ = $this->ctry_univ["ctry_univ"]; 

            $sqluniv = "SELECT id_univ,cname_univ 
                        FROM tb_university 
                        where ctry_univ=".$this->ctry_univ;

            $this->university = $university->findSql($sqluniv); 
       }else{
            $this->university =null;
       }

		//返回专业大类列表
		$category = spClass("category");
		$this->category = $category->findAll(); 
        
		//返回专业小类信息

		$classes = spClass("classes");

        //获取选中专业对应的专业大类
        $conditions = array('id_clss' => $this->id_clss);
		$this->cate_clss  = $classes->find($conditions); 
    
        if($this->cate_clss){

            $this->cate_clss = $this->cate_clss["cate_clss"]; 
            $sqlclss = "SELECT id_clss,cname_clss 
                        FROM tb_classes  
                        where cate_clss=".$this->cate_clss;

            $this->classes = $classes->findSql($sqlclss); 
        }else{
            $this->classes = null;
        }
        $this->display("admin/edit_news.html");
	}
	
	// 修改国家信息，这里是权限是只有GBADMIN才能执行
	public function editc(){
		$id = $this->spArgs("id"); //接受参数id 
		$country = spClass("country");
        $condition = array('id_ctry'=>$id); // 获取ID
        $this->ctry = $country->findAll($condition);  //根据ID搜索留学资讯
        $this->id = $id;
        $this->display("admin/ctryedit.html");
	}
	
	// 修改学校信息，这里是权限是只有GBADMIN才能执行
	public function editu(){
		$id = $this->spArgs("id"); //接受参数id 
		$university = spClass("university");
		$country = spClass("country");
        
		$condition = array('id_univ'=>$id); // 获取ID
        $this->university = $university->findAll($condition);  //根据ID搜索留学资讯
        $this->id = $id;
        $this->country = $country->findAll(); 
        $this->arrcount = array(1,2,3,4,5);
        $this->display("admin/univedit.html");
	}
	
	// 修改专业大类信息，这里是权限是只有GBADMIN才能执行
	public function edity(){
		$id = $this->spArgs("id"); //接受参数id 
		$category = spClass("category");
        
		$condition = array('id_cate'=>$id); // 获取ID
        $this->category = $category->findAll($condition);  //根据ID搜索留学资讯
        $this->id = $id;
        $this->display("admin/catedit.html");
	}
	
	// 修改学院信息，这里是权限是只有GBADMIN才能执行
	public function editl(){
        	$kwds = $this->spArgs("kwds"); //接受参数kwds
		$id = $this->spArgs("id"); //接受参数id 
		$school = spClass("school");
        
		$sql = "select id_schl,cname_schl,ename_schl,cname_univ 
				from tb_school,tb_university 
				where univ_schl = id_univ and id_schl='$id'"; // 获取ID
        $this->school = $school->findSql($sql);  //根据ID搜索留学资讯
        $this->id = $id;
        $this->kwds = $kwds;
        $this->display("admin/schledit.html");
	}
	
	// 修改专业信息，这里是权限是只有GBADMIN才能执行
	public function editr(){
        	$schl = $this->spArgs("schl");
		$kwds = $this->spArgs("kwds"); //接受参数kwds
		$id = $this->spArgs("id"); //接受参数id 
		$major = spClass("major");
        
		$sql = "select id_majr,cname_majr,
					id_schl,cname_schl,
					id_univ,cname_univ,
					id_clss,cname_clss,
					id_cate,cname_cate,
					id_ctry,cname_ctry,
					ename_majr,rank_majr,
					url_majr,desc_majr  
				from tb_school,
					tb_university,
					tb_classes,
					tb_category,
					tb_major,
					tb_country
				where id_clss = clss_majr 
				and univ_majr = id_univ 
				and schl_majr = id_schl 
				and cate_clss = id_cate 
				and ctry_univ = id_ctry 
				and id_majr ='$id'"; // 获取ID
        $this->major = $major->findSql($sql);  //根据ID搜索留学资讯
        $this->id = $id;
        
        //返回国家信息
		$country = spClass("country");
		$this->country = $country->findAll(); 
		
		//返回学校信息
		$sqluniv = "SELECT id_univ,cname_univ 
					FROM tb_university 
					where ctry_univ=
						(select ctry_univ  
    						from tb_major,tb_university 
    						where univ_majr = id_univ 
    						and id_majr = $id)";
		$university = spClass("university");
		$this->university = $university->findSql($sqluniv); 
		
		//返回学院信息
		$sqlschl = "SELECT id_schl,cname_schl 
					FROM tb_school 
					where univ_schl=
						(select univ_majr  
    					from tb_major 
    					where id_majr = $id)";
		$school = spClass("school");
		$this->school = $school->findSql($sqlschl); 
		
		//返回专业大类列表
		$category = spClass("category");
		$this->category = $category->findAll(); 
        
		//返回专业小类信息
		$sqlclss = "SELECT id_clss,cname_clss 
					FROM tb_classes  
					where cate_clss=
					(select id_cate   
    					from tb_major,tb_category,tb_classes 
    					where clss_majr = id_clss
    					and cate_clss = id_cate 
    					and id_majr = $id)";
		$classes = spClass("classes");
		$this->classes = $classes->findSql($sqlclss); 
		$this->kwds = $kwds;
		$this->schl = $schl;
        	$this->display("admin/majredit.html");
	}
	
	// 修改专业小类信息，这里是权限是只有GBADMIN才能执行
	public function edits(){
		$id = $this->spArgs("id"); //接受参数id 
		$classes = spClass("classes");
        
		$category = spClass("category");
		$this->category = $category->findAll();  //根据ID搜索留学资讯
        		
		$condition = array('id_clss'=>$id); // 获取ID
        $this->classes = $classes->findAll($condition);  //根据ID搜索留学资讯
        $this->id = $id;
        $this->display("admin/clssedit.html");
	}
	
	// 保存案例，通知
	public function save(){
		$id         = $this->spArgs("id"); //接受参数id 
		$page       = $this->spArgs("page"); //接受参数page
		$content    = $this->spArgs("content"); //接受参数content
		$type       = $this->spArgs("type"); //接受参数type
        $title      = $this->spArgs("title"); //接受参数title

        //增加添加时间，关键字，学校，专业显示
        $showtime   = $this->spArgs("showtime");
        $keywords   = $this->spArgs("keywords");
        $id_univ    = $this->spArgs("university");
        $id_clss    = $this->spArgs("classes");

		$news = spClass("news");
		$condition = array('id_news'=>$id); // 获取ID
        $row = array(
            'content_news'  => $content,
            'title_news'    => $title,
            'type_news'     => $type,
            'keywords_news' => $keywords,
            'id_univ'       => $id_univ,
            'id_clss'       => $id_clss,
            'time_news'     => $showtime
        );
        $this->info = $news->update($condition, $row);   //根据ID修改留学资讯
        $this->success("修改成功，请继续使用！", spUrl("admin","news",array('page'=>$page)));
	}

	// 保存资讯
	public function saven(){
		$id         = $this->spArgs("id"); //接受参数id 
		$page       = $this->spArgs("page"); //接受参数page
		$content    = $this->spArgs("content"); //接受参数content
		$type       = $this->spArgs("type"); //接受参数type
        $title      = $this->spArgs("title"); //接受参数title

        //增加添加时间，关键字，学校，专业显示
        $showtime   = $this->spArgs("showtime");
        $keywords   = $this->spArgs("keywords");
        $id_univ    = $this->spArgs("university");
        $id_clss    = $this->spArgs("classes");
        $ar = array();

        // 大图，小图都不修改 
        if(!$_FILES['pic']['name'] and !$_FILES['avatar']['name']){
        	$row = array(
	            'content_news'  => $content,
	            'title_news'    => $title,
	            'type_news'     => $type,
	            'keywords_news' => $keywords,
	            'id_univ'       => $id_univ,
	            'id_clss'       => $id_clss,
	            'time_news'     => $showtime
	        );
	    // 不修改大图，修改小图
        }else if(!$_FILES['pic']['name'] and $_FILES['avatar']['name']){
        	$ext_a = end(explode('.',$_FILES['avatar']['name'])); //获得扩展名
			$uploadfile_a = "A_E".$id.'.'.$ext_a; //要保存的文件路径+文件名，此处保存在upload/目录下
			
			$up1 = spClass("upload");
			$up1->setOptions(array('userDefName'=>$uploadfile_a,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_NEWS));
	    	$up1->uploadFile($_FILES['avatar']);

	    	if($up1->getErrorNo()!=0) die('上传失败!');

	    	$row = array(
	            'content_news'  => $content,
	            'title_news'    => $title,
	            'type_news'     => $type,
	            'keywords_news' => $keywords,
	            'id_univ'       => $id_univ,
	            'id_clss'       => $id_clss,
	            'avatar_news'   => $uploadfile_a,
	            'time_news'     => $showtime
	        );

		// 不修改小图，修改大图
        }elseif($_FILES['pic']['name'] and !$_FILES['avatar']['name']) {
        	$ext_p = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
			$uploadfile_p = "P_E".$id.'.'.$ext_p; //要保存的文件路径+文件名，此处保存在upload/目录下
				
			$up2 = spClass("upload");
	    	$up2->setOptions(array('userDefName'=>$uploadfile_p,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_NEWS));
	    	$up2->uploadFile($_FILES['pic']);

	    	if($up2->getErrorNo()!=0) die('上传失败!');

	    	$row = array(
	            'content_news'  => $content,
	            'title_news'    => $title,
	            'type_news'     => $type,
	            'keywords_news' => $keywords,
	            'id_univ'       => $id_univ,
	            'id_clss'       => $id_clss,
	            'pic_news'      => $uploadfile_p,
	            'time_news'     => $showtime
	        );
        // 大图，小图都修改 
        }else{
        	$ext_p = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
			$uploadfile_p = "P_E".$id.'.'.$ext_p; //要保存的文件路径+文件名，此处保存在upload/目录下
				
			$ext_a = end(explode('.',$_FILES['avatar']['name'])); //获得扩展名
			$uploadfile_a = "A_E".$id.'.'.$ext_a; //要保存的文件路径+文件名，此处保存在upload/目录下

	   		$up1 = spClass("upload");
			$up1->setOptions(array('userDefName'=>$uploadfile_a,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_NEWS));
	    	$up1->uploadFile($_FILES['avatar']);

	    	$up2 = spClass("upload");
	    	$up2->setOptions(array('userDefName'=>$uploadfile_p,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_NEWS));
	    	$up2->uploadFile($_FILES['pic']);

	    	if($up2->getErrorNo()!=0 or $up2->getErrorNo()!=0 ) die('上传失败!');

	    	$row = array(
	            'content_news'  => $content,
	            'title_news'    => $title,
	            'type_news'     => $type,
	            'keywords_news' => $keywords,
	            'id_univ'       => $id_univ,
	            'id_clss'       => $id_clss,
	            'pic_news'      => $uploadfile_p,
	            'avatar_news'   => $uploadfile_a,
	            'time_news'     => $showtime
	        );
        }

		$news = spClass("news");
		$condition = array('id_news'=>$id); // 获取ID
        
        $this->info = $news->update($condition, $row);   //根据ID修改留学资讯
        $this->success("修改成功，请继续使用！", spUrl("admin","news",array('page'=>$page)));
	}
	
	// 保存学院信息（校验）
	public function savel(){
        $kwds = $this->spArgs("kwds"); //接受参数kwds
		$id = $this->spArgs("id"); //接受参数id 
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
				and cname_schl='$cname' and id_schl<>'$id'";
		$sql2 = "SELECT id_schl 
				FROM tb_school,tb_university 
				where cname_univ='$univ' 
                                and id_univ = univ_schl 
				and ename_schl='$ename' and id_schl<>'$id'";
		
		if($university->findCount($condition)==0){
			$this->error("无此学校，请先添加学校",spUrl("super","editl",array('id'=>$id)));
		}else if($university->findSql($sql1)){
			$this->error("学院中文名重复",spUrl("super","editl",array('id'=>$id)));
		}else if($university->findSql($sql2)){
			$this->error("学院英文名重复",spUrl("super","editl",array('id'=>$id)));
		}else{
			
			$univid = $university->findAll($condition);
			$newrow = array( // PHP的数组
				'cname_schl' => $cname,
				'ename_schl' => $ename,
				'univ_schl'  => $univid[0]['id_univ'],
			);
			$this->school = $school->update(array('id_schl'=>$id), $newrow);
                        
                        
                        
                        // 网站数据同步
                        $f = new SaeFetchurl();
  			$f->setMethod("post");
  			$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id,
                                  "cname" => $cname,
				  "ename" => $ename,
				  "univ" => $univid[0]['id_univ'],
    				)
  			);
  			$f->fetch("http://www.wayabroad.sinaapp.com/synch/school_edit.php");
                        $ntc =  "OK";
                  	if($f->errno() != 0){ 
                  		$ntc = $f->errmsg();
                        }
                        
                        
			$this->success("修改成功，请继续使用！".$ntc, spUrl("admin","schl",array('kwds'=>$kwds)));
		}
	}
	
	// 保存专业大类
	public function savey(){
		$id = $this->spArgs("id"); //接受参数id 
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$category = spClass("category");
                
		$condition = array('id_cate'=>$id); // 获取ID
                $condition1 = " ename_cate='$ename' and id_cate<>'$id' ";
		$condition2 = " cname_cate='$cname' and id_cate<>'$id' ";
		
		if($category->findCount($condition1)>0){
			$this->error("专业英文名重复",spUrl("super","edity",array('id'=>$id)));
		}else if($category->findCount($condition2)>0){
			$this->error("专业中文名重复",spUrl("super","edity",array('id'=>$id)));                
                }else{
                
        		$row = array('cname_cate'=>$cname,'ename_cate'=>$ename);
        		$this->category = $category->update($condition, $row);   //根据ID修改留学资讯
        		$this->success("修改成功，请继续使用！", spUrl("admin","cate"));
                }
	}
	
	// 保存学校信息
	public function saveu(){
		$id         = $this->spArgs("id");
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

		//创建对象
		$university = spClass("university");
                
        $condition1 = " ename_univ='$ename' and id_univ<>'$id' ";
		$condition2 = " cname_univ='$cname' and id_univ<>'$id' ";
		
		if($university->findCount($condition1)>0){
			$this->error("学校英文名重复",spUrl("super","editu",array('id'=>$id)));
		}else if($university->findCount($condition2)>0){
			$this->error("学校中文名重复",spUrl("super","editu",array('id'=>$id)));                
                }else{
                	$condition = array('id_univ'=>$id); // 获取ID
	
			if($this->spArgs('save')){
				if($_FILES['pic']['name']){
    					//如果图片不为空，删除旧图
    					//获取url图片名称                            
    					//$opic = $university->findAll($condition, null, 'logo_univ', null);
    					//$url_array = explode("/", $opic[0]['logo_univ']);
    					// storage删除pic
    					//$s = new SaeStorage(); 
    					//$s->delete (DOMAIN,$url_array[3]);
    					//新图片上传保存
					$ext = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
					$uploadfile = $ename.'.'.$ext; //要保存的文件路径+文件名，此处保存在upload/目录下
					//$s->upload(DOMAIN, $uploadfile, $_FILES['pic']['tmp_name']);                  
    				//	$pic = $s->getUrl(DOMAIN,$uploadfile);                 
                    $up = spClass("upload");
					$up->setOptions(array('userDefName'=>$uploadfile,'allowType'=>array('jpg','gif','png','jpeg'),'filePath'=>DM_UNIV));
    				$up->uploadFile($_FILES['pic']);				

					$row = array('cname_univ' => $cname,
						'ename_univ' => $ename,
						'ctry_univ' => $country,
						'desc_univ' => $desc,
						'rank_univ' => $rank,
						'logo_univ' => $uploadfile,
                        'py_univ'   => $py,
                        'kws_univ'  => $keywords,	
						'acad_univ' => $acad_univ,
						'env_univ'  => $env_univ,
						'cons_univ' => $cons_univ,
                        'eval_univ' => $eval_univ,
						'job_univ'  => $job_univ,
                        'easy_univ' => $easy_univ
					);
				}else{
					$row = array('cname_univ' => $cname,
						'ename_univ' => $ename,
						'ctry_univ' => $country,
						'desc_univ' => $desc,
						'rank_univ' => $rank,
                        'py_univ'   => $py,
                        'kws_univ'  => $keywords,
						'acad_univ' => $acad_univ,
						'env_univ'  => $env_univ,
						'cons_univ' => $cons_univ,
                        'eval_univ' => $eval_univ,
						'job_univ'  => $job_univ,
                        'easy_univ' => $easy_univ
					);
				}	
				//storage对象销毁
				//unset($s);
                    $this->ctry = $university->update($condition, $row);   //根据ID修改留学资讯

                                
                        // 网站数据同步
                        //$f = new SaeFetchurl();
                        //$f->setMethod("post");

                        //$f->setPostData(
                        //    array(
                        //        "user"=> $_SESSION["userinfo"]["name_user"],
                        //        "pass"=> $_SESSION["userinfo"]["pswd_user"],
                        //        "id" => $id,
                        //        "ename" => $ename,
                        //        "cname" => $cname,
                        //        'ctry' => $country
                        //    )
  			            //);


  			            //$f->fetch("http://www.showcute.sinaapp.com/synch/univ_edit.php");  

  			            //$f->fetch("http://www.wayabroad.sinaapp.com/synch/univ_edit.php");     
				
                        $this->success("修改成功，请继续使用！", spUrl("admin","univc"));
				}
        	}
	}	
	
	// 保存专业小类信息
	public function saves(){
		$id = $this->spArgs("id");
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$category = $this->spArgs("category"); //接受参数category
		//创建对象
		$classes = spClass("classes");
		
		$condition = array('id_clss'=>$id); // 获取ID
	
        $condition1 = " ename_clss='$ename' and id_clss<>'$id' ";
		$condition2 = " cname_clss='$cname' and id_clss<>'$id' ";
		
		if($classes->findCount($condition1)>0){
			$this->error("专业小类英文名重复",spUrl("super","edits",array('id'=>$id)));
		}else if($classes->findCount($condition2)>0){
			$this->error("专业小类中文名重复",spUrl("super","edits",array('id'=>$id)));                
        }else{
        
			if($this->spArgs('save')){
				$row = array('cname_clss' => $cname,
					'ename_clss' => $ename,
					'cate_clss' => $category,
				);
				$this->clss = $classes->update($condition, $row);   //根据ID修改留学资讯
  				$this->success("修改成功，请继续使用！", spUrl("admin","clssc"));
			}
        }
	}	
	
	// 保存国家信息
	public function savec(){
		$id = $this->spArgs("id");
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$code = $this->spArgs("code"); //接受参数code
                
		$country = spClass("country");
		$condition = array('id_ctry'=>$id); // 获取ID
                
                $condition1 = " ename_ctry='$ename' and id_ctry<>'$id' ";
		$condition2 = " cname_ctry='$cname' and id_ctry<>'$id' ";
		
		if($country->findCount($condition1)>0){
			$this->error("国家英文名重复",spUrl("super","editc",array('id'=>$id)));
		}else if($country->findCount($condition2)>0){
			$this->error("国家中文名重复",spUrl("super","editc",array('id'=>$id)));                
                }else{
	
		if($this->spArgs('save')){
			if($_FILES['pic']['name']){
    		//如果图片不为空，删除旧图
    		//获取url图片名称                            
    		$opic = $country->findAll($condition, null, 'pic_ctry', null);
    		$url_array = explode("/", $opic[0]['pic_ctry']);
    		// storage删除pic
    		$s = new SaeStorage(); 
    		$s->delete (DOMAIN,$url_array[3]);
    		//新图片上传保存
    		$ext = end(explode('.',$_FILES['pic']['name'])); //获得扩展名
				$uploadfile = $ename.'.'.$ext; //要保存的文件路径+文件名，此处保存在upload/目录下
				$s->upload(DOMAIN, $uploadfile, $_FILES['pic']['tmp_name']);                  
    		$pic = $s->getUrl(DOMAIN,$uploadfile);  
    
				$row = array('cname_ctry' => $cname,
					'ename_ctry' => $ename,
					'code_ctry' => $code,
					'pic_ctry' => $pic,
				);
			}else{
				$row = array('cname_ctry' => $cname,
					'ename_ctry' => $ename,
					'code_ctry' => $code,
				);
			}
   			unset($s);
			$this->ctry = $country->update($condition, $row);   //根据ID修改留学资讯
  			$this->success("修改成功，请继续使用！", spUrl("admin","ctry"));
		}	
        	}
	}

	//保存专业信息
	public function saver(){
		$id = $this->spArgs("id"); //接受参数id
		$cname = $this->spArgs("cname"); //接受参数cname
		$ename = $this->spArgs("ename"); //接受参数ename
		$university = $this->spArgs("university"); //接受参数university
		$school = $this->spArgs("school"); //接受参数school
		$classes = $this->spArgs("classes"); //接受参数classes
		$rank = $this->spArgs("rank"); //接受参数rank
		$url = $this->spArgs("url"); //接受参数url
		$desc = $this->spArgs("desc"); //接受参数desc		
		
                $kwds = $this->spArgs("kwds"); //接受参数kwds
		$schl = $this->spArgs("schl");
                
		$major = spClass("major");
						
		$condition1 = " ename_majr='$ename' and schl_majr='$school' and id_majr <> $id ";
		$condition2 = " cname_majr='$cname' and schl_majr='$school' and id_majr <> $id ";
		
		if($major->findCount($condition1)>0){
			$this->error("专业英文名重复",spUrl("super","editr",array('id'=>$id)));
		}else if($major->findCount($condition2)>0){
			$this->error("专业中文名重复",spUrl("super","editr",array('id'=>$id)));
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
    
                        
			$this->major = $major->update(array('id_majr'=>$id), $newrow);
                        
                        
                        // 网站数据同步
                        $f = new SaeFetchurl();
  			$f->setMethod("post");
  			$f->setPostData(
    				array(
                                  "user"=> $_SESSION["userinfo"]["name_user"],
                                  "pass"=> $_SESSION["userinfo"]["pswd_user"],
                                  "id" => $id,
                                  "cname" => $cname,
				  "ename" => $ename,
				  "univ" => $university,
				  "schl" => $school,
				  "clss" => $classes,
				  "rank" => $rank,
				  "url" => $url,
				  "desc" => $desc,
    				)
  			);
  			$f->fetch("http://www.wayabroad.sinaapp.com/synch/major_edit.php");
                        $ntc =  "OK";
                  	if($f->errno() != 0){ 
                  		$ntc = $f->errmsg();
                        }
			$this->success("修改成功，请继续使用！".$ntc, spUrl("admin","majrl",array('kwds' => $kwds,'schl' => $schl)));
		}
	}
}	