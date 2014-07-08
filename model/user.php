<?php
class user extends spModel
{
	var $pk = "id_user"; // 每个留言唯一的标志，可以称为主键
	var $table = "user"; // 数据表的名称
	var $verifier = array( // 验证登录信息，由于密码是加密的输入框生成的，所以不需要进行“格式验证”
		"rules" => array( // 规则
			'name_user' => array(  // 这里是对uname的验证规则
				'notnull' => TRUE, // uname不能为空
				'minlength' => 3,  // uname长度不能小于3
				'maxlength' => 12  // uname长度不能大于12
			),
		),
		"messages" => array( // 提示信息
			'name_user' => array(
				'notnull' => "用户名不能为空",
				'minlength' => "用户名不能少于3个字符",
				'maxlength' => "用户名不能大于20个字符"
			),
		)
	);
	/**
	 * 这里我们建立一个成员函数来进行用户登录验证
	 *
	 * @param uname    用户名
	 * @param upass    密码，请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
	 */
	public function userlogin($uname, $upass){ 
		$conditions = array(
			'name_user' => $uname,
			'pswd_user' => $upass, // 请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
		);
		// dump($conditions);
		// 检查用户名/密码，由于$conditions是数组，所以SP会自动过滤SQL攻击字符以保证数据库安全。
		if( $result = $this->find($conditions) ){ 
			// 成功通过验证，下面开始对用户的权限进行会话设置，最后返回用户ID
			// 用户的角色存储在用户表的acl字段中
			spClass('spAcl')->set($result['acl_user']); // 通过spAcl类的set方法，将当前会话角色设置成该用户的角色
			$_SESSION["userinfo"] = $result; // 在SESSION中记录当前用户的信息
			return true;
		}else{
			// 找不到匹配记录，用户名或密码错误，返回false
			return false;
		}
	}
	/**
	 * 无权限提示及跳转
	 */
	public function acljump(){ 
		// 这里直接“借用”了spController.php的代码来进行无权限提示
		if(isset($_SESSION["userinfo"])==true)
		{
			$url = spUrl("main","login");
			echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"您没有权限进行此操作！\");location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
		}else if(isset($_SESSION["custominfo"])==true){
			$url = spUrl("main","login");
			echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"您没有权限进行后台操作，谢谢！\");location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
		}else{
			$url = spUrl("main","login");
			echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"您没有权限进行此操作，请登录谢谢！\");location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
			
		}
		exit;
	}
}