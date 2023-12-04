<?php 	
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if($action=="set")
{
	$weburl= $_POST['smtpserver'];
	$token = $_POST['smtpserverport'];
	$token = $_POST['smtpusermail'];
	$token = $_POST['smtpuser'];
	$token = $_POST['smtppass'];
	$open=fopen("../data/admin/smtp.php","w" );
	$str='<?php  ';	
	$str.='$smtpserver = "';
	$str.="$smtpserver";
	$str.='"; ';
	$str.='$smtpserverport = "';
	$str.="$smtpserverport";
	$str.='"; ';
	$str.='$smtpusermail = "';
	$str.="$smtpusermail";
	$str.='"; ';
	$str.='$smtpname = "';
	$str.="$smtpname";
	$str.='"; ';
	$str.='$smtpuser = "';
	$str.="$smtpuser";
	$str.='"; ';
	$str.='$smtppass = "';
	$str.="$smtppass";
	$str.='"; ';
	$str.='$smtpreg = "';
	$str.="$smtpreg";
	$str.='"; ';
	$str.='$smtppsw = "';
	$str.="$smtppsw";
	$str.='"; ';	
	$str.=" ?>";
	fwrite($open,$str);
	fclose($open);
	ShowMsg("成功保存设置!","admin_smtp.php");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>邮件服务器设置</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;邮件服务器设置';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<form action="admin_smtp.php?action=set" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">邮件服务器设置</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
<?php  require_once("../data/admin/smtp.php"); ?>
SMTP地址：<input  name="smtpserver" value="<?php  echo $smtpserver;?>">
 * SMTP发信服务器地址，必须支持SSL链接，如：smtp.qq.com 
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
SMTP端口：<input name="smtpserverport" value="<?php  echo $smtpserverport;?>">
 * SMTP服务器使用的SSL端口，如：465
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
SMTP邮箱：<input name="smtpusermail" value="<?php  echo $smtpusermail;?>">
 * 用于发信的地址，如：1234@qq.com
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
SMTP昵称：<input name="smtpname" value="<?php  echo $smtpname;?>">
 * 用于显示的发信名称，如：海洋影视网
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
SMTP账户：<input name="smtpuser" value="<?php  echo $smtpuser;?>">
 * 用于发信的邮件账户，如：1234@qq.com
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
SMTP密码：<input name="smtppass" value="<?php  echo $smtppass;?>">
 * SMTP服务器的用户密码或者授权码
</td>
</tr>
<tr class="thead">
<td colspan="5" class="td_title">邮箱认证选项</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
注册需要激活：<input type="radio" name="smtpreg" value="off" <?php  if($smtpreg=='off') echo 'checked';?>>关闭
&nbsp;&nbsp;
<input type="radio" name="smtpreg" value="on" <?php  if($smtpreg=='on') echo 'checked';?>>开启
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
找回密码功能：<input type="radio" name="smtppsw" value="off" <?php  if($smtppsw=='off') echo 'checked';?>>关闭
&nbsp;&nbsp;
<input type="radio" name="smtppsw" value="on" <?php  if($smtppsw=='on') echo 'checked';?>>开启
</td>
</tr>
<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="确 认" class="btn" >
</td>
</tr>


<tr>
<td width="90%" align="left" height="30" class="td_border"><div style="padding: 10px;border: 0;border-radius: 4px;font-size: 12px;background-color: #eef5f4;">
* 更改全部用户为已激活状态SQL语句：UPDATE sea_member SET acode='y';<br>
* 邮件发送服务器必须支持使用SSL加密协议。<br>
* 网站服务器环境需要开启SSL扩展。<br>
* 如果修改无效，请检查/data/admin/smtp.php文件权限是否可写。
</div></td>
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