<?php
class serv extends spController
{
	//显示名下客户列表
	public function cust(){
		$name = $this->spArgs("name"); //接受参数用户名
		$custom = spClass("custom");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		if($name==""){
			$this->results = $custom->spPager($this->spArgs('page', 1), 20)->findAll(null,"id_cust DESC"); 
		}else{
			$condition = " cname_cust like '%$name%' ";
			$this->results = $custom->spPager($this->spArgs('page', 1), 20)->findAll($condition,"id_cust DESC"); 
		}
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $custom->spPager()->getPager();
		$this->position = "客户";
		$this->name = $name;
		$this->display("admin/serv/custom_query.html"); // 显示模板
	}
	
	// 修改客户信息（部分）
	public function editc(){
		$id = $this->spArgs("id"); //接受参数id 
		$custom = spClass("custom");
       	$condition = array('id_cust'=>$id); // 获取ID
       	$result = $custom->findAll($condition);  //根据ID搜索客户信息
       	$this->custom = $result;
       	
       	$user = spClass("user");
       	$conditions = array('acl_user'=>"SALES"); // 获取销售人员列表
       	
       	
       	$this->sales = $user->findAll($conditions);
       	$this->position = "客户信息";
        $this->id = $id;
        $this->display("admin/serv/custom_edit.html");
	}
	
	// 重置客户密码
	public function editp(){
		$id = $this->spArgs("id"); //接受参数id 
		$custom = spClass("custom");
       	$condition = array('id_cust'=>$id); // 获取ID
       	$result = $custom->findAll($condition);  //根据ID搜索留学资讯
       	$this->custom = $result;
       	$this->position = "修改客户密码";
        $this->id = $id;
        $this->display("admin/serv/custom_pswd.html");
	}
	
	// 保存客户信息
	public function savec(){
		$id = $this->spArgs("id"); //接受参数id 
		
		$name = $this->spArgs("name"); //接受参数name
		$cname = $this->spArgs("cname"); //接受参数cname
		$sales = $this->spArgs("sales"); //接受参数sales
		
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
        'name_cust'=>$name,
        'address_cust'=>$address,
        'code_cust'=>$code,
        'tel_cust'=>$tel,
        'cname_cust'=>$cname,
        'sales_cust'=>$sales);
        $this->info = $custom->update($condition, $row);   //根据ID修改留学资讯
        $this->success("修改成功，请继续使用！", spUrl("serv","cust"));
	}
	
	
	// 保存客户信息
	public function addc(){
		$name = $this->spArgs("name"); //接受参数name
		$cname = $this->spArgs("cname"); //接受参数cname
		$sales = $this->spArgs("sales"); //接受参数sales
		
		$univ = $this->spArgs("univ"); //接受参数univ
		$major = $this->spArgs("major"); //接受参数major
		$english = $this->spArgs("english"); //接受参数english
		$email = $this->spArgs("email"); //接受参数email

		$address = $this->spArgs("address"); //接受参数address
		$code = $this->spArgs("code"); //接受参数code
		$tel = $this->spArgs("tel"); //接受参数tel
		
		$npass = $this->spArgs("npass"); //接受参数npass
		$rpass = $this->spArgs("rpass"); //接受参数rpass
		
		$custom = spClass("custom");
		
		$condition1 = " name_cust='$name' ";
		$condition2 = " cname_cust='$cname' ";
		
		if($custom->findCount($condition1)>0){
			$this->error("用户名重复",spUrl("serv","newcs"));
		}else if($custom->findCount($condition2)>0){
			$this->error("姓名重复",spUrl("serv","newcs"));
		}else if($npass != $rpass){
			$this->error("两次密码不同",spUrl("serv","newcs"));
		}else{

        $row = array(
        	'univ_cust'=>$univ,
        	'major_cust'=>$major,
        	'english_cust'=>$english,
        	'email_cust'=>$email,
        	'name_cust'=>$name,
        	'cname_cust'=>$cname,
        	'sales_cust'=>$sales,
         	'address_cust'=>$address,
        	'code_cust'=>$code,
        	'tel_cust'=>$tel,
        	'pswd_cust'=>md5($npass),
        );
        $custom->create($row);
		$this->success("客户信息添加成功！", spUrl("serv","cust"));
		}
	}
	
	
	// 保存客户密码
	public function savep(){
		$id = $this->spArgs("id"); //接受参数id 
		$npass = $this->spArgs("npass"); //接受参数npass
		$rpass = $this->spArgs("rpass"); //接受参数rpass

		if($npass == $rpass){
			$custom = spClass("custom");
			$condition = array('id_cust'=>$id); // 获取ID
        	$row = array('pswd_cust'=>md5($npass));
        	$this->info = $custom->update($condition, $row);   //根据ID修改留学资讯
        	$this->success("修改成功，请继续使用！", spUrl("serv","cust"));
		}else{
			$this->error("两次密码不一致，请重新输入！", spUrl("serv","editp",array('id'=>$id)));	
		}
	}
	
	// 新增客户页面      
	public function newcs(){
		$user = spClass("user");
       	$conditions = array('acl_user'=>"SALES"); // 获取销售人员列表
       	$this->sales = $user->findAll($conditions);
		$this->position = "客户新增";
		$this->display("admin/serv/custom_add.html"); // 显示模板
	}
	
	//显示销售员列表
	public function sales(){
		$name = $this->spArgs("name"); //接受参数用户名
		$user = spClass("user");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		if($name==""){
			$condition = " acl_user = 'SALES' ";
			$this->results = $user->spPager($this->spArgs('page', 1), 20)->findAll($condition); 
		}else{
			$condition = " cname_user like '%$name%' and acl_user = 'SALES' ";
			$this->results = $user->spPager($this->spArgs('page', 1), 20)->findAll($condition); 
		}
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $user->spPager()->getPager();
		$this->position = "销售人员";
		$this->name = $name;
		$this->display("admin/serv/sales_query.html"); // 显示模板
	}
	
	
	// 修改销售员信息
	public function edits(){
		$id = $this->spArgs("id"); //接受参数id 
		$user = spClass("user");
       	$condition = array('id_user'=>$id); // 获取ID
       	$this->sales = $user->findAll($condition);  //根据ID搜索销售员信息
       
       	$this->position = "销售人员";
        $this->id = $id;
        $this->display("admin/serv/sales_edit.html");
	}
	
	// 保存销售人员信息
	public function saves(){
		$id = $this->spArgs("id"); //接受参数id 
		
		$name = $this->spArgs("name"); //接受参数name
		$cname = $this->spArgs("cname"); //接受参数cname
		$email = $this->spArgs("email"); //接受参数email

		$user = spClass("user");
		$condition = array('id_user'=>$id); // 获取ID
        $row = array( 'email_user'=>$email, 'name_user'=>$name,'cname_user'=>$cname);
        $this->info = $user->update($condition, $row);   //根据ID修改留学资讯
        $this->success("修改成功，请继续使用！", spUrl("serv","sales"));
	}
	
	// 删除销售人员信息，若专销售人员信息下有客户，不予删除
	public function dels(){
		// 这里先判断是否传入了gid
		if( $id = $this->spArgs("id") ){
   		   	$condition = array('sales_cust'=>$id); // 获取ID
   			$result = spClass("custom")->findAll($condition);
   			if($result){
   				$this->error("该销售人员下有客户，不可删！", spUrl("serv","sales"));
   			}else{
    		// 执行删除
				spClass("user")->delete(array('id_user'=>$id));
				$this->success("删除成功！", spUrl("serv","sales"));
            }
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("serv","sales"));
		}
	} 
	
	// 删除客户信息，若客户下有订单，不予删除
	public function delc(){
		if( $id = $this->spArgs("id") ){
   		   	$condition = array('custom_order'=>$id); // 获取ID
   			$result = spClass("order")->findAll($condition);
   			if($result){
   				$this->error("该客户下有订单，不可删！", spUrl("serv","cust"));
   			}else{
    		// 执行删除
				spClass("custom")->delete(array('id_cust'=>$id));
				$this->success("删除成功！", spUrl("serv","cust"));
            }
		}else{
			// 无gid则直接跳转回首页
			$this->jump(spUrl("serv","cust"));
		}
	} 
	// 新增销售人员页面      
	public function newss(){
		$this->position = "销售人员";
		$this->display("admin/serv/sales_add.html"); // 显示模板
	}
	
	// 添加销售人员信息
	public function adds(){
		$cname = $this->spArgs("cname"); //接受参数cname
		$name = $this->spArgs("name"); //接受参数name
		$email = $this->spArgs("email"); //接受参数email
		$npass = $this->spArgs("npass"); //接受参数npass
		$rpass = $this->spArgs("rpass"); //接受参数rpass	
		
		$user = spClass("user");
						
		$condition1 = " name_user='$name' ";
		$condition2 = " cname_user='$cname' ";
		
		if($user->findCount($condition1)>0){
			$this->error("用户名重复",spUrl("serv","newss"));
		}else if($user->findCount($condition2)>0){
			$this->error("姓名重复",spUrl("serv","newss"));
		}else if($npass != $rpass){
			$this->error("两次密码不同",spUrl("serv","newss"));
		}else{
			$newrow = array( // PHP的数组
				'cname_user' => $cname,
				'name_user' => $name,
				'email_user' => $email,
				'acl_user' => "SALES",	
				'pswd_user' => md5($npass),					
			);
			$user->create($newrow);
			$this->success("销售人员添加成功！", spUrl("serv","sales"));
		}
	}	
	
	// 重置销售人员密码
	public function editsp(){
		$id = $this->spArgs("id"); //接受参数id 
		$user = spClass("user");
       	$condition = array('id_user'=>$id); // 获取ID
       	$result = $user->findAll($condition);  //根据ID搜索留学资讯
       	$this->user = $result;
       	$this->position = "修改客户密码";
        $this->id = $id;
        $this->display("admin/serv/sales_pswd.html");
	}
	
	// 保存销售员密码
	public function savesp(){
		$id = $this->spArgs("id"); //接受参数id 
		$npass = $this->spArgs("npass"); //接受参数npass
		$rpass = $this->spArgs("rpass"); //接受参数rpass

		if($npass == $rpass){
			$user = spClass("user");
			$condition = array('id_user'=>$id); // 获取ID
        	$row = array('pswd_user'=>md5($npass));
        	$this->info = $user->update($condition, $row);   //根据ID修改留学资讯
        	$this->success("修改成功，请继续使用！", spUrl("serv","sales"));
		}else{
			$this->error("两次密码不一致，请重新输入！", spUrl("serv","editsp",array('id'=>$id)));	
		}
	}
	
	
	// 业务管理界面
	public function main(){
		$name = $this->spArgs("name"); //接受参数用户名
		$custom = spClass("custom");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		if($name==""){
			$this->results = $custom->spPager($this->spArgs('page', 1), 20)->findAll(null,"id_cust DESC"); 
		}else{
			$condition = " cname_cust like '%$name%' ";
			$this->results = $custom->spPager($this->spArgs('page', 1), 20)->findAll($condition,"id_cust DESC"); 
		}
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $custom->spPager()->getPager();
		$this->position = "业务查询";
		$this->name = $name;
		$this->display("admin/serv/sales_main.html"); // 显示模板
	}
	
	// 业务管理查询客户订单
	public function order(){
		$id = $this->spArgs("id"); //接受参数id 
		$types = $this->spArgs("ts");
		$custom = spClass("custom");
       	$condition = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition);  //根据ID搜索客户
       	$order = spClass("order");
       	
       if($types ==2){
       		$condition = "select id_order,id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,refund_order,
						item_order,price_order,ct_order,desc_order,state_order  
						from tb_order,tb_custom    
						where id_cust = custom_order 
						and id_cust = $id 	 	
						and state_order = 'WAIT_BUYER_PAY' 
						and refund_order = 'NO_REFUND' "; 
			$this->orders = $order->findSql($condition); 
			$this->position = "订单查询->未付款订单";
        	$this->id = $id;
        	$this->display("admin/serv/sales_nopay.html");
       	}else {
       		$condition = "select id_order,id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,refund_order,
						item_order,price_order,pt_order,desc_order,state_order  
						from tb_order,tb_custom    
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order != 'WAIT_BUYER_PAY' "; 

			$this->orders = $order->findSql($condition); 
			$this->position = "订单查询->已付款订单";
        	$this->id = $id;
        	$this->display("admin/serv/sales_paid.html");
       	} 	
	}
	
	//进度查询列表
	public function mate(){
		$id = $this->spArgs("id"); //接受参数id
		$custom = spClass("custom");
		
       	$condition1 = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition1);  //根据ID搜索客户
		$condition = array('cust_mate'=>$id); 
		
		if(spClass("mate")->findAll($condition)){
			$mate = spClass("mate")->findAll($condition);
		}else{
			 $row = array(
    		    'cust_mate'=>$id,
        	);
        	spClass("mate")->create($row);
        	$mate = spClass("mate")->findAll($condition);
		}		
		$this->mate = $mate;
		$this->id = $id;
		$this->position = "材料明细";
        $this->display("admin/serv/sales_mate.html");
	}
	
	//申请查询列表
	public function aply(){
		$id = $this->spArgs("id"); //接受参数id
		$custom = spClass("custom");
		
       	$condition1 = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition1);  //根据ID搜索客户

       	$condition = array('cust_aply'=>$id); 
		$aply = spClass("aply")->findAll($condition);

		$this->aply = $aply;
		$this->id = $id;
		$this->position = "申请进度";
        $this->display("admin/serv/sales_aply.html");
	}
	
	//行程准备列表
	public function tour(){
		$id = $this->spArgs("id"); //接受参数id
		$custom = spClass("custom");
		
       	$condition1 = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition1);  //根据ID搜索客户
		
       	$condition = array('cust_tour'=>$id); 
		if(spClass("tour")->findAll($condition)){
			$tour = spClass("tour")->findAll($condition);
		}else{
			 $row = array(
    		    'cust_tour'=>$id,
        	);
        	spClass("tour")->create($row);
        	$tour = spClass("tour")->findAll($condition);
		}		
		$this->tour = $tour;
		$this->id = $id;
		$this->position = "行程准备";
        $this->display("admin/serv/sales_tour.html");
	}
}