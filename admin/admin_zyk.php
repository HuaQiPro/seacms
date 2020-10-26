<?php 
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if(empty($action))
{
	$action = '';
}
$id = empty($id) ? 0 : intval($id);

if($action=="add")
{
	if(empty($zname))
	{
		ShowMsg("资源库名称没有填写，请返回检查","-1");
		exit();
	}
	if(empty($zapi))
	{
		ShowMsg("资源库api地址没有填写，请返回检查","-1");
		exit();
	}
	$query = "Insert Into `sea_zyk`(zname,zapi,zinfo) Values('$zname','$zapi','$zinfo');";
	$rs = $dsql->ExecuteNoneQuery($query);
	if($rs)
	{
		ShowMsg("成功增加一个资源库!","admin_zyk.php");
		exit();
	}
	else
	{
		ShowMsg("增加资源库时出错，请向官方反馈，原因：".$dsql->GetError(),"javascript:;");
		exit();
	}
}
elseif($action=="del")
{
	$dsql->ExecuteNoneQuery("delete from `sea_zyk` where zid='$id'");
	ShowMsg("成功删除一个资源库!","admin_zyk.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>资源库管理</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;资源库管理 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">资源库列表</td>
</tr>
<?php 
$sqlStr="select * from `sea_zyk` order by zid ASC";
$dsql->SetQuery($sqlStr);
$dsql->Execute('flink_list');
while($row=$dsql->GetObject('flink_list'))
{
$aid=$row->id;
?>

<tr>
<td width="60" align="left" height="30" class="td_border">&nbsp;<a href="?action=del&id=<?php  echo $row->zid; ?>">删除该库</a>&nbsp;&nbsp;</td>
<td align="left"  height="30" class="td_border">
【ID<?php  echo $row->zid; ?>】【<?php  echo $row->zname ?>】 - [<?php  echo $row->zapi ?>] - [<?php  echo $row->zinfo ?>]
</td>
</tr>

<?php 
}
?>

</tbody></table>	
	
	
	
	
	
	
	
	
<form action="?action=add" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">资源库新增</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
资源库名称：<input  name="zname" style="width:200px;">
 * 填写一个容易识别的资源库名称
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
资源库地址：<input name="zapi" style="width:400px;">
 * 请填写正确的资源库api地址，如：http://www.123.com/api.php
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
资源库描述：<input name="zinfo" value="暂无描述" style="width:400px;">
 * 简单描述资源库的基本资料
</td>
</tr>

<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="增 加" class="btn" >
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