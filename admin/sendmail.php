<?php 
header("Content-Type:text/html;charset=utf-8");
require_once(dirname(__FILE__)."/config.php");
CheckPurview();

	$username= $_GET['username'];
	$smtprmail= $_GET['smtprmail'];


if($ac=="post"){
	
	$smtprmail= $_POST['smtprmail'];
	$smtprtitle = $_POST['smtprtitle'];
	$smtprbody = $_POST['smtprbody'];
	require_once("../include/class.phpmailer.php"); 
	require_once("../data/admin/smtp.php"); 
	$mail = new PHPMailer();
	$mail->SMTPDebug = 0;//是否启用smtp的debug进行调试
	$mail->isSMTP();
	$mail->SMTPAuth=true;//smtp需要鉴权 这个必须是true
	$mail->Host = $smtpserver;//服务器地址
	$mail->SMTPSecure = 'ssl';//设置使用ssl加密方式登录鉴权
	$mail->Port = intval($smtpserverport);//设置ssl连接smtp服务器的远程服务器端口号 可选465或587
	$mail->CharSet = 'UTF-8';
	$mail->FromName = $smtpname;//设置发件人名称
	$mail->Username =$smtpuser;//smtp登录的账号
	$mail->Password = $smtppass;//smtp登录的密码 
	$mail->From = $smtpusermail;//发件人邮箱地址
	$mail->isHTML(true); //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
	$mail->addAddress($smtprmail);//设置收件人邮箱地址 
	$mail->Subject ="=?utf-8?B?" . base64_encode($smtprtitle) . "?=";//添加该邮件的主题
	//$mail->Subject = $smtprtitle;//添加该邮件的主题
	$mail->Body = $smtprbody;//添加邮件正文

	 
	//发送命令 返回布尔值 
	//PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
	$status = $mail->send();
	 
	//简单的判断与提示信息
	if($status) {
	 ShowMsg("邮件发送成功！","-1");
	}else{
	  ShowMsg("邮件发送失败!<br>$mail->ErrorInfo","-1");
	}
}

if($ac !="post"){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>发送邮件</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;百度主动推送设置 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<form action="?ac=post" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">发送邮件</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
用户账户：<input  name="username" value="<?php  echo $username;?>" style="width:300px;">
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
接收邮箱：<input name="smtprmail" value="<?php  echo $smtprmail;?>" style="width:300px;">
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
邮件标题：<input name="smtprtitle" style="width:600px;">
</td>
</tr>

<tr>
<td width="80%" align="left" class="td_border" style="width:500px;">
邮件内容：<textarea name="smtprbody" style="width:600px; height:120px;"> </textarea>
</td>
</tr>



<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="发 送" class="btn" >
</td>
</tr>

<tr>
<td width="90%" align="left" height="30" class="td_border">
* 支持HTML内容。
</td>
</tr>
<tr>
<td width="90%" align="left" height="30" class="td_border">
* 您可以进入“系统-邮件服务器设置”修改发送参数。
</td>
</tr>
</tbody></table>	
	

</form>
</div>
	</div>
</div>
<?php 
viewFoot();
?>
</body>
</html>

<?php  
}
?>
