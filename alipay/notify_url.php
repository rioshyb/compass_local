<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];
	
  	//退款状态
        
  	if(isset($_POST['refund_status'])){
        	$refund_status = $_POST['refund_status'];
    }else{
            $refund_status = "NO_REFUND";
    }
    
	//交易支付时间
	$gmt_payment = $_POST['gmt_payment'];
	
	$link = mysql_connect("localhost","compassedu1","compassedu8500") or die("Could not connect");   
		mysql_select_db("compassedu1") or die("Could not select database");
		
	if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET state_order = 'WAIT_SELLER_SEND_GOODS' , refund_order = '".$refund_status."', pt_order = '".$gmt_payment."' WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
	//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET state_order = 'WAIT_BUYER_CONFIRM_GOODS' , pt_order = '".$gmt_payment."', refund_order = '".$refund_status."' WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}	
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['trade_status'] == 'TRADE_FINISHED') {
	//该判断表示买家已经确认收货，这笔交易完成
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET state_order = 'TRADE_FINISHED' , pt_order = '".$gmt_payment."', refund_order = '".$refund_status."' WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['trade_status'] == 'TRADE_CLOSED') {
	//该判断交易中途关闭
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET state_order = 'TRADE_CLOSED' , pt_order = '".$gmt_payment."', refund_order = '".$refund_status."' WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['refund_status'] == 'WAIT_SELLER_AGREE') {
	//该判断退款协议等待卖家确认中
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET refund_order = 'WAIT_SELLER_AGREE', trade_status = '".$trade_status."' WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['refund_status'] == 'SELLER_REFUSE_BUYER') {
	//该判断卖家不同意协议，等待买家修改
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET refund_order = 'SELLER_REFUSE_BUYER', trade_status = '".$trade_status."'  WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['refund_status'] == 'WAIT_BUYER_RETURN_GOODS') {
	//该判断退款协议达成，等待买家退货
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET refund_order = 'WAIT_BUYER_RETURN_GOODS', trade_status = '".$trade_status."'  WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['refund_status'] == 'WAIT_SELLER_CONFIRM_GOODS') {
	//该判断等待卖家收货
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET refund_order = 'WAIT_SELLER_CONFIRM_GOODS', trade_status = '".$trade_status."'  WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['refund_status'] == 'REFUND_SUCCESS') {
	//该判断退款成功
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET refund_order = 'REFUND_SUCCESS', trade_status = '".$trade_status."'  WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
	else if($_POST['refund_status'] == 'REFUND_CLOSED') {
	//该判断退款关闭
	
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
		$sql = "UPDATE tb_order SET refund_order = 'REFUND_CLOSED', trade_status = '".$trade_status."'  WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
        echo "success";		//请不要修改或删除

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else {
		//其他状态判断
        echo "success";

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>