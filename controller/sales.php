<?php
import("uploadFile.php");
class sales extends spController
{
	//显示名下客户列表
	public function cust(){
		$name = $this->spArgs("name"); //接受参数用户名
		$custom = spClass("custom");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$user = $_SESSION["userinfo"]['id_user'];
		if($name==""){
			$condition = array('sales_cust'=> $user); // 从session获取当前的登陆用户
		}else{
			$condition = " cname_cust like '%$name%' and sales_cust = '$user' ";
		}
		$this->results = $custom->spPager($this->spArgs('page', 1), 20)->findAll($condition,"id_cust DESC"); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $custom->spPager()->getPager();
		$this->position = "客户";
		$this->name = $name;
		$this->display("admin/sales/custom_query.html"); // 显示模板
	}
	
	// 修改客户信息（部分）
	public function editc(){
		$id = $this->spArgs("id"); //接受参数id 
		$custom = spClass("custom");
       	$condition = array('id_cust'=>$id); // 获取ID
       	$result = $custom->findAll($condition);  //根据ID搜索留学资讯
        $this->custom = $result;
       	$this->position = "客户信息";
        $this->id = $id;
        $this->display("admin/sales/custom_edit.html");
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
        $this->display("admin/sales/custom_pswd.html");
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
        $this->success("修改成功，请继续使用！", spUrl("sales","cust"));
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
        	$this->success("修改成功，请继续使用！", spUrl("sales","cust"));
		}else{
			$this->error("两次密码不一致，请重新输入！", spUrl("sales","editp",array('id'=>$id)));	
		}
	}
	
	// 销售管理界面
	public function main(){
		$name = $this->spArgs("name"); //接受参数用户名
		$custom = spClass("custom");
		// 这里使用了spPager，同时用spArgs接受到传入的page参数
		$user = $_SESSION["userinfo"]['id_user'];
		if($name==""){
			$condition = array('sales_cust'=> $user); // 从session获取当前的登陆用户
		}else{
			$condition = " cname_cust like '%$name%' and sales_cust = '$user' ";
		}
		$this->results = $custom->spPager($this->spArgs('page', 1), 20)->findAll($condition,"id_cust DESC"); 
		// 这里获取分页数据并发送到smarty模板内
		$this->pager = $custom->spPager()->getPager();
		$this->position = "客户管理";
		$this->name = $name;
		$this->display("admin/sales/sales_main.html"); // 显示模板
	}
	
	// 客户订单界面
	public function order(){
		$id = $this->spArgs("id"); //接受参数id 
		$types = $this->spArgs("ts");
		$custom = spClass("custom");
       	$condition = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition);  //根据ID搜索客户
       	$order = spClass("order");
       	
       	if($types ==1 ){
			$order = date('ymdHis').$id;
       		$this->order = $order;       	
       	  	$this->position = "付费管理->新订单生成";
        	$this->id = $id;
        	$this->display("admin/sales/sales_order.html");
       	}else if($types ==2){
       		$condition = "select id_order,id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,refund_order,
						item_order,price_order,ct_order,desc_order,state_order  
						from tb_order,tb_custom    
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order = 'WAIT_BUYER_PAY' and refund_order = 'NO_REFUND' "; 

			$this->orders = $order->findSql($condition); 
			$this->position = "付费管理->未付款订单";
        	$this->id = $id;
        	$this->display("admin/sales/sales_nopay.html");
        }else if($types ==3){
       		$condition = "select id_order,id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,refund_order,
						item_order,price_order,pt_order,desc_order,state_order  
						from tb_order,tb_custom    
						where id_cust = custom_order 
						and id_cust = $id 	
						and state_order in ('WAIT_SELLER_SEND_GOODS','WAIT_BUYER_CONFIRM_GOODS','TRADE_FINISHED','TRADE_CLOSED')
                                                and refund_order = 'NO_REFUND' "; 

			$this->orders = $order->findSql($condition); 
			$this->position = "付费管理->已付款订单";
        	$this->id = $id;
        	$this->display("admin/sales/sales_paid.html");	
       	}else {
       		$condition = "select id_cust,cname_cust,tel_cust,
						address_cust,code_cust,code_order,pt_order,refund_order,
						item_order,price_order,state_order,desc_order,state_order    
						from tb_order,tb_custom   
						where id_cust = custom_order 
						and id_cust = $id 	
						and refund_order != 'NO_REFUND' "; 

			$this->orders = $order->findSql($condition); 
			$this->position = "付费管理->退款订单";
        	$this->id = $id;
        	$this->display("admin/sales/sales_refund.html");
       	} 	
	}
	
	
	// 保存订单信息
	public function orders(){
		$id = $this->spArgs("id"); //接受参数id 
		$order = spClass("order");
       	$condition2 = array('custom_order'=>$id,'state_order'=>"WAIT_BUYER_PAY"); 
       	if($order->findCount($condition2)>0){
       		$this->error("该客户存在未付款订单，新订单生成失败！", spUrl("sales","order",array('id'=>$id,'ts'=>2)));
       	}else{
			$item = $this->spArgs("item"); //接受参数item
			$price = $this->spArgs("price"); //接受参数price
			$od = $this->spArgs("order"); //接受参数order
			$desc = $this->spArgs("desc"); //接受参数desc
			$ct = date('Y-m-d H:i:s');
	        $row = array(
    		    'code_order'=>$od,
        		'item_order'=>$item,
        		'price_order'=>$price,
	        	'desc_order'=>$desc,
		        'custom_order'=>$id,
   		     	'ct_order'=>$ct,
        	);
        	$order->create($row);   //新增订单
        	$this->success("订单新增成功，请继续使用！", spUrl("sales","order",array('id'=>$id,'ts'=>2)));
		}
	}
	
	//未付费订单修改
	public function ordere(){
		$id = $this->spArgs("id"); //接受参数id 
		$oid = $this->spArgs("oid"); //接受参数oid 
		$item = $this->spArgs("item"); //接受参数item
		$desc = $this->spArgs("desc"); //接受参数desc
		$price = $this->spArgs("price"); //接受参数price
		
		$order = spClass("order");
       	$condition = array('id_order'=>$oid); 
	    $row = array(
        	'item_order'=>$item,
        	'price_order'=>$price,
	        'desc_order'=>$desc,
        );
        $this->info = $order->update($condition, $row);  
        	$this->success("订单修改成功，请继续使用！", spUrl("sales","order",array('id'=>$id,'ts'=>2)));
	}
	
	//未付费订单删除
	public function orderl(){
		$id = $this->spArgs("id"); //接受参数id 
		$oid = $this->spArgs("oid"); //接受参数oid 
		
       	$condition = array('id_order'=>$oid,'state_order'=>'WAIT_BUYER_PAY'); 
		$result = spClass("order")->findAll($condition);
		if($result){
			// 执行删除
			spClass("order")->delete(array('id_order'=>$oid));
			$this->success("删除成功！", spUrl("sales","order",array('id'=>$id,'ts'=>2)));
		}else{
				$this->error("订单状态发生变化，不可删！", spUrl("sales","order",array('id'=>$id,'ts'=>2)));
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
        $this->display("admin/sales/sales_mate.html");
	}
	//保存进度查询列表
	public function mates(){
		$id = $this->spArgs("id"); //接受参数id
		$custom = spClass("custom");
       	$condition1 = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition1);  //根据ID搜索客户
		
		
		$recd1 = $this->spArgs("recd1"); //接受参数recd1
		$recd2 = $this->spArgs("recd2"); //接受参数recd2
		$cv = $this->spArgs("cv"); //接受参数cv
		$ps = $this->spArgs("ps"); //接受参数ps
		$aply = $this->spArgs("aply"); //接受参数aply
		$pic = $this->spArgs("pic"); //接受参数pic
		$acdc = $this->spArgs("acdc"); //接受参数acdc
		$show = $this->spArgs("show"); //接受参数show
		
		$condition = array('cust_mate'=>$id); // 获取ID
		$mate = spClass("mate");
		
		 $row = array(
        	'recd1_mate'=>$recd1,
        	'recd2_mate'=>$recd2,
        	'cv_mate'=>$cv,
        	'ps_mate'=>$ps,
        	'aply_mate'=>$aply,
        	'pic_mate'=>$pic,
        	'acdc_mate'=>$acdc,
		 	'show_mate'=>$show,
		 	'time_mate'=>date("Y-m-d H:i:s"),
        );
        $this->info = $mate->update($condition, $row);   
		
		$this->id = $id;
		$this->position = "材料明细";
        $this->success("修改成功，请继续使用！", spUrl("sales","mate",array('id'=>$id)));
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
        $this->display("admin/sales/sales_aply.html");
	}
	
	//新增申请专业
	public function aplys(){
		$id = $this->spArgs("id"); //接受参数id
     	$majr = $this->spArgs("majr"); //接受参数majr
		$notice = $this->spArgs("notice"); //接受参数notice

		$row = array(
    		    'majr_aply'=>$majr,
        		'notice_aply'=>$notice,
				'cust_aply'=>$id,
				'time_aply'=>date('Y-m-d H:i:s'),
        	);
        spClass("aply")->create($row);   //新增订单
		
		$this->id = $id;
		$this->success("申请专业新增成功，请继续使用！", spUrl("sales","aply",array('id'=>$id)));
	}
	
	//申请专业删除
	public function aplyl(){
		$uid = $this->spArgs("uid"); //接受参数uid 		
		$id = $this->spArgs("id"); //接受参数id 
		spClass("aply")->delete(array('id_aply'=>$id));
		$this->success("删除成功！", spUrl("sales","aply",array('id'=>$uid)));	
	}
	
	//申请专业编辑
	public function aplye(){
		$uid = $this->spArgs("uid"); //接受参数uid 
		$custom = spClass("custom");
       	$condition1 = array('id_cust'=>$uid); // 获取ID
       	$this->custom = $custom->findAll($condition1);  //根据ID搜索客户
				
		$id = $this->spArgs("id"); //接受参数id 
		$this->aply = spClass("aply")->findAll(array('id_aply'=>$id));
		$this->position = "申请进度修改";
		$this->id = $uid;
        $this->display("admin/sales/sales_aplye.html");
	}
	
	//申请专业保存
	public function aplyb(){
		$uid = $this->spArgs("uid"); //接受参数uid 

		$majr = $this->spArgs("majr"); //接受参数majr
		$state = $this->spArgs("state"); //接受参数state
		$account = $this->spArgs("account"); //接受参数account
		$result = $this->spArgs("result"); //接受参数result
		$notice = $this->spArgs("notice"); //接受参数notice
		$show = $this->spArgs("show"); //接受参数show

		$id = $this->spArgs("id"); //接受参数id 
       	$condition = array('id_aply'=>$id); 
       	
       	if($state == "完成网申"){
       		 $row = array(
        	'majr_aply'=>$majr,
        	'state_aply'=>$state,
	        'account_aply'=>$account,
	    	'result_aply'=>$result,
	    	'notice_aply'=>$notice,
	    	'show_aply'=>$show,
	    	'time_aply'=>date('Y-m-d H:i:s'),
        	);
       	}else{ 
       		$row = array(
        	'majr_aply'=>$majr,
        	'state_aply'=>$state,
	        'account_aply'=>"",
	    	'result_aply'=>$result,
	    	'notice_aply'=>$notice,
	    	'show_aply'=>$show,
	    	'time_aply'=>date('Y-m-d H:i:s'),
        	);
       	}
       	
	   
        $this->info = spClass("aply")->update($condition, $row); 
        $this->id = $uid; 
        $this->success("修改成功！", spUrl("sales","aply",array('id'=>$uid)));	
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
        $this->display("admin/sales/sales_tour.html");
	}
	
	//保存行程准备列表
	public function tours(){
		$id = $this->spArgs("id"); //接受参数id
		$custom = spClass("custom");
       	$condition1 = array('id_cust'=>$id); // 获取ID
       	$this->custom = $custom->findAll($condition1);  //根据ID搜索客户
		
		$visa = $this->spArgs("visa"); //接受参数visa
		$rent = $this->spArgs("rent"); //接受参数rent
		$ticket = $this->spArgs("ticket"); //接受参数ticket
		$other = $this->spArgs("other"); //接受参数other
		$show = $this->spArgs("show"); //接受参数show
		
		$condition = array('cust_tour'=>$id); // 获取ID
		$tour = spClass("tour");
		
		$row = array(
        	'visa_tour'=>$visa,
        	'rent_tour'=>$rent,
        	'ticket_tour'=>$ticket,
        	'other_tour'=>$other,
		 	'show_tour'=>$show,
		 	'time_tour'=>date("Y-m-d H:i:s"),
        );
        $this->info = $tour->update($condition, $row);   
		
		$this->id = $id;
		$this->position = "行程准备";
        $this->success("修改成功，请继续使用！", spUrl("sales","tour",array('id'=>$id)));
	}
	
	// 批量修改页面
	public function batch(){
		$this->position = "批量修改";
		$this->display("admin/sales/custom_batch.html"); // 显示模板
	}
	
	// 批量修改
	public function editb(){
		$mate = spClass("mate");
		$aply = spClass("aply");
		$tour = spClass("tour");
		$custom = spClass("custom");
		$account = "";
		$uid = "";
		$results = array();
		$counts = 0;
		
		
		
   		if(!$_FILES['file']['name']) die('没有选择文件!');
   
		$uploadfile = $_SESSION["userinfo"]['name_user'].date('ymdHis').'.csv'; //要保存的文件路径+文件名，此处保存在upload/目录下
		
		//sae storage写法
		//		$s = new SaeStorage(); 		
   		//if($s->upload(DOMAIN, $uploadfile, $_FILES['file']['tmp_name'])){                    
                        
		//		$files = $s->getUrl(DOMAIN,$uploadfile);
   		//}
		$up = spClass("upload");
		$up->setOptions(array('userDefName'=>$uploadfile,'allowType'=>array('csv'),'filePath'=>DM_CSV));
    	$up->uploadFile($_FILES['file']);
    	$files = DM_CSV.$uploadfile;
		$handle = fopen($files, 'r'); //打开csv文件
		while ( $data = fgetcsv( $handle, 1000, ',' )) { // 1000为一行数据的最大长度, ','为数据分隔标志
			if($data[0]!=$account){
				$account = $data[0];  	
				$condition = array('name_cust'=>$account); 
       			$result = $custom->findAll($condition);
       			if(!empty($result)){
       				$uid = $result[0]['id_cust'];
       			}else{
       				$this->error("用户".$results[0]['content']['visa_tour'].$account."不存在，请联系管理员创建用户，谢谢！");
       				break;
       			}
       		}
			if($data[1]==1){
				$condition1 = array('cust_mate'=>$uid);
				if($mate->findCount($condition1)>0){
		 			$row1 = array(
        				'recd1_mate'=>mb_convert_encoding($data[3],'UTF-8','GB2312'),
        				'recd2_mate'=>iconv('GB2312','UTF-8',$data[4]),
        				'cv_mate'=>iconv('GB2312','UTF-8',$data[5]),
        				'ps_mate'=>iconv('GB2312','UTF-8',$data[6]),
        				'aply_mate'=>iconv('GB2312','UTF-8',$data[2]),
        				'pic_mate'=>iconv('GB2312','UTF-8',$data[8]),
        				'acdc_mate'=>iconv('GB2312','UTF-8',$data[7]),
		 				'show_mate'=>iconv('GB2312','UTF-8',$data[9]),
        			);
        			$mate->update($condition1, $row1);
        			$results[$counts] = array('user'=>$account.'_材料清单','type'=>1,'content'=>$row1);
        			$counts++;
				}else{
        			$row11 = array(
        				'recd1_mate'=>iconv('GB2312','UTF-8',$data[3]),
        				'recd2_mate'=>iconv('GB2312','UTF-8',$data[4]),
        				'cv_mate'=>iconv('GB2312','UTF-8',$data[5]),
        				'ps_mate'=>iconv('GB2312','UTF-8',$data[6]),
        				'aply_mate'=>iconv('GB2312','UTF-8',$data[2]),
        				'pic_mate'=>iconv('GB2312','UTF-8',$data[8]),
        				'acdc_mate'=>iconv('GB2312','UTF-8',$data[7]),
		 				'show_mate'=>iconv('GB2312','UTF-8',$data[9]),
        				'cust_mate'=>iconv('GB2312','UTF-8',$uid),
        			);
					$mate->create($row11);
					$results[$counts] = array('user'=>$account.'_材料清单','type'=>1,'content'=>$row11);
					$counts++;
				}

			}elseif($data[1]==2){
				$condition2 = array('majr_aply'=>iconv('GB2312','UTF-8',$data[2]),'cust_aply'=>$uid);
				if($aply->findCount($condition2)>0){
					 $row2 = array(
        				'state_aply'=>iconv('GB2312','UTF-8',$data[3]),
	        			'account_aply'=>iconv('GB2312','UTF-8',$data[4]),
	    				'result_aply'=>iconv('GB2312','UTF-8',$data[5]),
	    				'notice_aply'=>iconv('GB2312','UTF-8',$data[6]),
	    				'show_aply'=>iconv('GB2312','UTF-8',$data[7]),
        			);
        			$aply->update($condition2, $row2);
        			$row2['majr_aply'] = $data[2];
        			$results[$counts] = array('user'=>$account.'_申请进度','type'=>2,'content'=>$row2); 
        			$counts++; 
				}else{
					$row22 = array(
						'majr_aply'=>iconv('GB2312','UTF-8',$data[2]),
        				'state_aply'=>iconv('GB2312','UTF-8',$data[3]),
	        			'account_aply'=>iconv('GB2312','UTF-8',$data[4]),
	    				'result_aply'=>iconv('GB2312','UTF-8',$data[5]),
	    				'notice_aply'=>iconv('GB2312','UTF-8',$data[6]),
	    				'show_aply'=>iconv('GB2312','UTF-8',$data[7]),
						'cust_aply'=>iconv('GB2312','UTF-8',$uid),
        			);
					$aply->create($row22);
					$results[$counts] = array('user'=>$account.'_申请进度','type'=>2,'content'=>$row22);
					$counts++;
				};
			}elseif($data[1]==3){
				$condition4 = array('id_cust'=>$uid);
				$row4 = array(	'step_cust'=>$data[2],	);
        		$custom->update($condition4, $row4);
        		$results[$counts] = array('user'=>$account.'_总体申请进度','type'=>3,'content'=>$row4); 
        		$counts++; 
			}else{
				$condition3 = array('cust_tour'=>$uid);
				if($tour->findCount($condition3)>0){
		 			$row3 = array(
        				'visa_tour'=>iconv('GB2312','UTF-8',$data[2]),
        				'rent_tour'=>iconv('GB2312','UTF-8',$data[3]),
        				'ticket_tour'=>iconv('GB2312','UTF-8',$data[4]),
        				'other_tour'=>iconv('GB2312','UTF-8',$data[5]),
        				'show_tour'=>iconv('GB2312','UTF-8',$data[6]),
        			);
        			$tour->update($condition3, $row3);
        			$results[$counts] = array('user'=>$account.'_行程准备','type'=>0,'content'=>$row3);  
        			$counts++;
				}else{
        			$row33 = array(
        				'visa_tour'=>iconv('GB2312','UTF-8',$data[2]),
        				'rent_tour'=>iconv('GB2312','UTF-8',$data[3]),
        				'ticket_tour'=>iconv('GB2312','UTF-8',$data[4]),
        				'other_tour'=>iconv('GB2312','UTF-8',$data[5]),
        				'show_tour'=>iconv('GB2312','UTF-8',$data[6]),
        				'cust_tour'=>iconv('GB2312','UTF-8',$uid),
        			);
					$tour->create($row33);
					$results[$counts] = array('user'=>$account.'_行程准备','type'=>0,'content'=>$row33);
					$counts++;
				}
			}
		}
		//删除上传文件
		//$s->delete(DOMAIN, $uploadfile);
		$this->position = "批量修改";
		$this->results = $results;
		$this->display("admin/sales/custom_batch.html"); // 显示模板
	}
}