<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];
	//卖家支付宝账号
	$seller_email = $_GET['seller_email'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];
	//价格
	$price = $_GET['price'];
	//买家支付宝账号
	$buyer_email = $_GET['buyer_email'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];
	//交易支付时间
	$gmt_payment = $_GET['gmt_payment'];
	//商品名称
	$subject = $_GET['subject'];

	//交易状态
	$trade_status = $_GET['trade_status'];


    if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			
    	$link = mysql_connect("localhost","compassedu1","compassedu8500") or die("Could not connect");   
		mysql_select_db("compassedu1") or die("Could not select database");
		$sql = "UPDATE tb_order SET state_order = 'WAIT_SELLER_SEND_GOODS' , refund_order = 'NO_REFUND' ,pt_order = '".$gmt_payment."' WHERE code_order = ".$out_trade_no;
    	if(mysql_query($sql,$link)){
    		echo "更新数据成功！";
		} else {
 			echo "更新数据失败：".mysql_error();
		}
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
		
	echo"<table width=\"80%\" border=\"1\" align=\"center\" cellPadding=0 cellSpacing=0 bordercolor=\"#EBEBEB\" >
  <tr>
    <td width=\"22%\" align=\"center\">订单名称：</td>
    <td width=\"78%\">$subject</td>
  </tr>
  <tr>
    <td align=\"center\">订单编号：</td>
    <td>$out_trade_no</td>
  </tr>
  <tr>
    <td align=\"center\">支付宝交易号：</td>
    <td>$trade_no</td>
  </tr>
  <tr>
    <td align=\"center\">卖家名称：</td>
    <td>常州指南者教育咨询有限公司</td>
  </tr>
  <tr>
    <td align=\"center\">卖家支付宝账号：</td>
    <td>$seller_email</td>
  </tr>
   <tr>
    <td align=\"center\">客户支付宝账号：</td>
    <td>$buyer_email</td>
  </tr>
  <tr>
    <td align=\"center\">付款金额：</td>
    <td>$price</td>
  </tr>
  <tr>
    <td align=\"center\">交易时间</td>
    <td>$gmt_payment</td>
  </tr>
</table><p align=\"center\">
<a href=\"javascript:window.close()\">关闭窗口</a></p>";

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
}
?>
        <title>支付宝纯担保交易接口</title>
	</head>
    <body>
    

<!-- compassedu.hk Baidu tongji analytics -->
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fd998a0e3652a82db1c29206a0a3b2e3e' type='text/javascript'%3E%3C/script%3E"));
</script>


</body>
</html>