<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if($action=="set")
{
	$v2=$_POST['v'];
	$open=fopen("../data/admin/adminvcode.txt","w" );
	fwrite($open,$v2);
	fclose($open);
	ShowMsg("成功保存设置!","admin_vcode.php");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理登陆验证码</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;管理登陆验证码 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<form action="admin_vcode.php?action=set" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">后台登陆验证码</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
<?php  $v1=file_get_contents("../data/admin/adminvcode.txt"); ?>
<input type="radio" name="v" value="0" <?php  if($v1==0) echo 'checked';?>>关闭
&nbsp;&nbsp;
<input type="radio" name="v" value="1" <?php  if($v1==1) echo 'checked';?>>开启
</td>
</tr>

<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="确 认" class="btn" >
</td>
</tr>
<tr>
<td><div style="padding: 10px;border: 0;border-radius: 4px;font-size: 12px;background-color: #eef5f4;">
* 如果修改无效，请检查/data/admin/adminvcode.txt文件权限是否可写。</div>
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