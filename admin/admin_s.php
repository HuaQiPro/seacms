<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if($action=="set")
{
	$v2=$_POST['v'];
	$open=fopen("../data/admin/s.txt","w" );
	fwrite($open,$v2);
	fclose($open);
	ShowMsg("成功保存设置!","admin_s.php");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户签到设置</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;用户管理&nbsp;&raquo;&nbsp;用户签到设置';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<form action="admin_s.php?action=set" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">每次签到增加的积分数</td>
</tr>
<tr bgcolor="#FFF" style="background-color:#FFF;">
<td width="80%" align="left" height="30" class="td_border">
<?php  $v1=file_get_contents("../data/admin/s.txt"); ?>
&nbsp;&nbsp;<input name="v" type="text" id="v" value=<?php  echo $v1 ?> style="width:50px;text-transform:uppercase;"> *为0时关闭该功能
</td>
</tr>

<tr bgcolor="#FFF" style="background-color:#FFF;">
<td width="10%" align="left" height="30" class="td_border">
&nbsp;&nbsp;<input type="submit" value="确 认" class="btn" >
</td>
</tr>
<tr>
<td width="90%" align="left" height="30" class="td_border">
&nbsp;&nbsp;* 积分数设置为0时，表示关闭该功能。如果修改无效，请检查/data/admin/s.txt文件权限是否可写。
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