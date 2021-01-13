<?php 
header("Content-Type:text/html;charset=utf-8");
require_once(dirname(__FILE__)."/config.php");
CheckPurview();

	$username= $_GET['username'];

$cc=$dsql->GetOne("select msgbody from sea_member where username='$username'");

if($ac=="post"){
	$username= $_POST['username'];
	$msgbody= $_POST['msgbody'];
	$dsql->ExecuteNoneQuery("update `sea_member` set msgbody = '$msgbody',msgstate = 'n'  where username= '$username'");
	showMsg("站内信息发送成功！","-1");exit;
}

if($ac !="post"){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>发送站内信息</title>
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
<td colspan="5" class="td_title">发送站内信息</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
用户账户：<input  name="username" value="<?php  echo $username;?>" style="width:300px;">
</td>
</tr>
<tr>
<td width="80%" align="left" class="td_border" style="width:500px;">
<textarea name="msgbody" style="width:600px; height:120px;"><?php echo $cc['msgbody'] ?></textarea>
</td>
</tr>
<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="发 送" class="btn" >
</td>
</tr>

<tr>
<td width="90%" align="left" height="30" class="td_border">
* 只能发送一条信息给用户，以最后一条为准，之前的信息会被覆盖。支持HTML内容。
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
