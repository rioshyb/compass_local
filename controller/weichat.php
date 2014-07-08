<?php
class weichat extends spController
{
	//接收消息
	function main(){
		$wx = spClass('spWeiXin'); 
        $msg = $wx->run();
        $username = $msg['FromUserName'];
        //绑定图文消息
        $articles_bd =  array(
							array(
								'Title' => '绑定指南者账号，享受便利留学服务',
								'Description' => '绑定指南者账号，享受便利留学服务',
                                'PicUrl' => 'http://spptest-upload.stor.sinaapp.com/at.jpg',
								'Url' => 'http://www.compassedu.hk/index.php?c=main&a=weix&id='.$username,
							),
                		);
        //材料进度查询
        if($msg['Event']=="subscribe" && $msg['MsgType']=="event"){
            echo $wx->replyText("您好，欢迎关注指南者教育（www.compassedu.hk)。指南者教育致力于海外名校研究生申请，团队成员毕业于海外名校：从伦敦大学学院，爱丁堡大学到香港中文大学，新加坡南洋理工大学。指南者教育率先在行业内创建了实用性较强的自助选校系统以及先拿offer再付费的商业模式。3年来，指南者教育已为近300位客户拿到了世界排名前100学校的Offer。");
            
        }else if($msg['EventKey']=="cl" && $msg['MsgType']=="event"){
              
            if($user=$this->check($username)){
                if($result =$this->matew($user)){
                    echo $wx->replyNews($result);
                }else{
                	echo $wx->replyText("目前还没收到您的任何申请材料呢 #^_^#");
                }                
        	}else{
        		echo $wx->replyText('请点击“我的申请”——>“账号绑定”，完成微信账号绑定，谢谢！');
        	}
        //申请进度查询
        }else if($msg['EventKey']=="sq" && $msg['MsgType']=="event"){
        	 if($user=$this->check($username)){
                if($result =$this->aplys($user)){
                    echo $wx->replyNews($result);
                }else{
                	echo $wx->replyText("目前还没任何申请结果呢 #^_^#");
                }                
        	}else{
        		echo $wx->replyText('请点击“我的申请”——>“账号绑定”，完成微信账号绑定，谢谢！');
        	}
        //账号绑定
        }else if($msg['EventKey']=="bd" && $msg['MsgType']=="event"){
        	if($user=$this->check($username)){
        		echo $wx->replyText('您已绑定账号：'.$user['user_wcur']."\n".'，请点击其他菜单查询进度等信息'."\n".
                                    '<a href ="http://www.compassedu.hk/index.php?c=main&a=weid&id='.$username.'">点击此处，解除绑定</a>');
        	}else{
        		echo $wx->replyNews($articles_bd);
        	}
        }
	}
	//检测微信号是否绑定
	public function check($wcname){
		$wcuser = spClass("wcuser");
		$conditions = array('wcuser_wcur' => $wcname);
		$result = $wcuser->find($conditions);
		return $result;
		
	}
	
	//材料查询
	public function matew($user){
		$mate = spClass("mate");
        $materials = NULL;
		$condition = array('cust_mate'=>$user['uid_wcur'],'show_mate'=>1);
		if($result = $mate->find($condition)){ 
			$materials =  array(
							array(
								'Title' => '材料准备情况',
								'Description' => $user['name_wcur'].'您好，材料准备情况如下：'.
                                					"\n\n".'推荐信1：'.$result['recd1_mate'].
													"\n\n".'推荐信2：'.$result['recd2_mate'].
													"\n\n".'简历：'.$result['cv_mate'].
													"\n\n".'个人陈述：'.$result['ps_mate'].
													"\n\n".'申请表：'.$result['aply_mate'].
													"\n\n".'成绩单：'.$result['acdc_mate'].
													"\n\n".'其他材料：'.$result['pic_mate'],
                                'PicUrl' => 'http://spptest-upload.stor.sinaapp.com/mt.jpg',
								'Url' => 'http://www.compassedu.hk/index.php?c=main&a=custom',
							),
                		);      
		}
        return $materials;
	}
	//进度查询
	public function aplys($user){
        $apply = NULL;
        $condition = array('cust_aply'=>$user['uid_wcur'],'show_aply'=>1);
		$aply = spClass("aply");
        $text = $user['name_wcur'].'您好，申请进度如下：'."\n\n";
        if($result = $aply->findAll($condition)){
            
        	foreach ($result as $key=>$items){
				$text = $text.'申请专业：'.$items['majr_aply']."\n".'申请状态：'.$items['state_aply']."\n".'申请账号：'.$items['account_aply']."\n".'申请结果：'.$items['result_aply']."\n\n";
			}
            
            $apply =  array(
							array(
								'Title' => '申请进度情况',
								'Description' => $text,
                                'PicUrl' => 'http://spptest-upload.stor.sinaapp.com/ap.jpg',
								'Url' => 'http://www.compassedu.hk/index.php?c=main&a=custom',
							),
                		);    
        }
        return $apply;
	}
	//订单查询
	public function order(){
		
	}
	//实时缴费
	public function pay(){
	}
}	



