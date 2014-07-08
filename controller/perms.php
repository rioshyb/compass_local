<?php
class perms extends spController
{
	public function addu(){
		$this->display("admin/useradd.html"); // 显示模板
	}
	//显示所有用户
	function users(){ // 这里是首页
		$user = spClass("user");
		$conditions = " name_user <> 'admin' and name_user <> 'send' ";
        $this->userlist = $user->findAll($conditions); // 用$this->results可以将$guestbook->findAll()的值发送到模板上面，模板上可以用$results来使用该值。
        $this->display("admin/userlist.html"); // 显示模板，这里使用的模板是根目录/tpl/green/index.html。
	}
	
	//用户权限编辑
	function edit(){
        $name = $this->spArgs("name"); //接受参数name
		$group =  $this->spArgs("group"); //接受参数group
		$this->name = $name;
		$this->group = $group;
		$this->display("admin/useredit.html");
	}
	
	//用户权限编辑
	function delu(){
		if( $id = $this->spArgs("id") ){
			// 执行删除
			spClass("user")->delete(array('id_user'=>$id));
			$this->success("删除成功！", spUrl("perms","users"));
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("perms","users"));
		}
	}
	
	//用户修改保存
	function save(){
		$name = $this->spArgs("name"); //接受参数name
		$group = $this->spArgs("groupn"); //接受参数group
        	$user = spClass("user");
        	
        	$sql = "SELECT a.id_user FROM  `tb_user` a INNER JOIN `tb_custom` b 
        		ON b.sales_cust = a.id_user WHERE a.`name_user` =  '".$name."' and a.acl_user ='SALES'";

        	if($user->findSql($sql)!=""){
				$this->error("销售下存在客户，不可修改权限",spUrl("perms","users"));
        	}else{
        		$condition = array('name_user'=>$name); // 获取ID             
                
       			$row = array('acl_user'=>$group);
        		$this->info = $user->update($condition, $row);   //根据name修改用户权限组
        		$this->success("用户组修改成功！", spUrl("perms","users"));
        	}
	}
	
	//显示权限表
	function show(){ 
		$acl = spClass("acl");
        $this->acl = $acl->spPager($this->spArgs('page', 1), 10)->findAll(); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $acl->spPager()->getPager();
		$this->position = "权限设置";
        $this->display("admin/permslist.html"); // 显示模板，这里使用的模板是根目录/tpl/green/index.html。
	}
	
	//权限编辑
	function editp(){
		$acl = spClass("acl");
		$id = $this->spArgs("id"); //接受参数id
		$condition = array('aclid'=>$id); // 获取ID
        $this->acl = $acl->findAll($condition);  //根据ID搜索权限
		$this->display("admin/permsedit.html");
	}
	
	//权限修改保存
	function savep(){
		$name = $this->spArgs("name"); //接受参数name
		$action = $this->spArgs("action"); //接受参数action
		$controller = $this->spArgs("controller"); //接受参数controller
		$acl_name = $this->spArgs("acl_name"); //接受参数acl_name
        $id = $this->spArgs("id"); //接受参数name
		
		$acl = spClass("acl");
        $condition = array('aclid'=>$id); // 获取ID
        $row = array('name'=>$name,'action'=>$action,'controller'=>$controller,'acl_name'=>$acl_name);
        $this->acl = $acl->update($condition, $row);   //根据id修改权限
        $this->success("修改成功，Asshole！", spUrl("perms","show"));
	}
	
	//权限新增保存
	function insertp(){
		$name = $this->spArgs("name"); //接受参数name
		$action = $this->spArgs("action"); //接受参数action
		$controller = $this->spArgs("controller"); //接受参数controller
		$acl_name = $this->spArgs("acl_name"); //接受参数acl_name
		$acl = spClass("acl");
		
		$conditions = " controller = '$controller' and action = '$action' ";
		$sum = $acl->findCount($conditions); // 使用了findCount
		if($sum>0){
			$this->error("控制器和操作重复",spUrl("perms","addp"));
		}else{
			$newrow = array( // PHP的数组
					'name' => $name,
					'action' => $action,
					'controller' => $controller,
					'acl_name' => $acl_name,
				);
			$acl->create($newrow);
			$this->success("权限添加成功！", spUrl("perms","show"));
		}
	}
	
	//权限编辑
	function delp(){
	if( $id = $this->spArgs("id") ){
			// 执行删除
			spClass("acl")->delete(array('aclid'=>$id));
			$this->success("删除成功！", spUrl("perms","show"));
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("perms","show"));
		}
	}
	
	//权限新增
	function addp(){
		$this->display("admin/permsadd.html");
	}
	//保存用户信息
	public function postu(){
		$name = $this->spArgs("name"); //接受参数name
		$password = md5($this->spArgs("npass")); //接受参数npass
		$groupn = $this->spArgs("groupn"); //接受参数groupn
		$user = spClass("user");	
		$condition = array('name_user'=>$name);
		
		if($user->findCount($condition)>0){
			$this->error("用户名存在",spUrl("perms","addu"));
		}else{
			$newrow = array( // PHP的数组
				'name_user' => $name,
				'pswd_user' => $password,
				'acl_user' => $groupn,
			);
			$user->create($newrow);
			$this->success("用户添加成功！", spUrl("perms","users"));
		}
	}
}	