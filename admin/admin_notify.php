<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if($action=="set")
{
	$notify1= $_POST['notify1'];
	$notify2= $_POST['notify2'];
	$notify3= $_POST['notify3'];
	$open=fopen("../data/admin/notify.php","w" );
	$str='<?php  ';
	$str.='$notify1 = "';
	$str.="$notify1";
	$str.='"; ';
	$str.='$notify2 = "';
	$str.="$notify2";
	$str.='"; ';
	$str.='$notify3 = "';
	$str.="$notify3";
	$str.='"; ';
	$str.=" ?>";
	fwrite($open,$str);
	fclose($open);
	ShowMsg("成功保存设置!","admin_notify.php");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员消息通知</title>
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
<form action="?action=set" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">会员消息通知</td>
</tr>
<tr>
<?php  require_once("../data/admin/notify.php"); ?>


<tr>
<td width="80%" align="left" height="30" class="td_border">
<br>通知1：<br><textarea name="notify1" style="width:600px; height:60px;"><?php  echo $notify1;?></textarea>
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
<br>通知2：<br><textarea name="notify2" style="width:600px; height:60px;"><?php  echo $notify2;?></textarea>
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
<br>通知3：<br><textarea name="notify3" style="width:600px; height:60px;"><?php  echo $notify3;?></textarea>
</td>
</tr>
<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="确 认" class="btn" >
</td>
</tr>
<tr>
<td>
<div style="padding: 10px;border: 0;border-radius: 4px;font-size: 12px;background-color: #eef5f4;">
* 最多支持发布3条会员通知，删除通知内容即可取消发布该条。<br>
* 如果修改无效，请检查/data/admin/notify.php文件权限是否可写。
</div>
</td>
</tr>

</tbody></table>	
<br>

</form>
</div>
	</div>
</div>
<?php 
viewFoot();
?>
</body>
</html>