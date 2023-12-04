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
	$query = "Insert Into `sea_zyk`(zname,zapi,zinfo,ztype) Values('$zname','$zapi','$zinfo','$ztype');";
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
elseif($action=="edit")
{
	if(empty($e_id))
	{
		ShowMsg("请选择需要更改的资源库","admin_zyk.php");
		exit();
	}
	foreach($e_id as $id)
	{
		$zname=$_POST["zname$id"];
		$zapi=$_POST["zapi$id"];
		$zinfo=$_POST["zinfo$id"];
		$ztype=$_POST["ztype$id"];
	if(empty($zname))
	{
		ShowMsg("资源库名称没有填写，请返回检查","-1");
		exit();
	}
	if(empty($zname))
	{
		ShowMsg("资源库api没有填写，请返回检查","-1");
		exit();
	}
	
	$dsql->ExecuteNoneQuery("update sea_zyk set zname='$zname',zapi='$zapi',zinfo='$zinfo',ztype='$ztype' where zid=".$id);
	}
	header("Location:admin_zyk.php");
	exit;
}
elseif($action=="delall")
{
	if(empty($e_id))
	{
		ShowMsg("没有选择资源库，请返回选择！","admin_zyk.php");
		exit();
	}
	foreach($e_id as $id)
	{
		$dsql->ExecuteNoneQuery("delete from `sea_zyk` where zid='$id'");
	}
	header("Location:admin_zyk.php");
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
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript">
$(document).ready(function(){

$("#btn_editall").click(function(){
				if(confirm('您确定要修改选中的资源库吗?'))
				{
					document.formclass.action='?action=edit';
					document.formclass.submit();
				}
	 })
	
$("#btn_delall").click(function(){
				if(confirm('您确定要删除选中的资源库吗?'))
				{
					document.formclass.action='?action=delall';
					document.formclass.submit();
				}
	 })
	 
 

	
})
	
</script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;资源库管理 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style" >
<tbody><tr class="thead">
<td colspan="6" class="td_title">资源库列表</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style" style="display:block">
<form method="post" name="formclass" action=""> 
<tr>
<td style="width:20px;" align="left"  height="30" class="td_border"> </td>
<td style="width:30px;" align="left"  height="30" class="td_border">ID</td>
<td style="width:120px;" align="left"  height="30" class="td_border">名称</td>
<td style="width:250px;" align="left"  height="30" class="td_border">接口地址</td>
<td style="width:220px;" align="left"  height="30" class="td_border">介绍</td>
<td style="width:250px; " align="left"  height="30" class="td_border">类型</td>
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
<td style="width:20px;" align="left"  height="30" class="td_border">
<input type="checkbox" name="e_id[]" value="<?php  echo $row->zid; ?>" class="checkbox" style=" margin-right:10px;"></td>
<td style="width:30px;" align="left"  height="30" class="td_border">
<input readonly="readonly" name="zid<?php  echo $row->zid; ?>" style=" margin-right:10px;width:30px;" value="<?php  echo $row->zid; ?>"></td>
<td style="width:120px;" align="left"  height="30" class="td_border">
<input  name="zname<?php  echo $row->zid; ?>" style=" margin-right:10px;width:120px;" value="<?php  echo $row->zname; ?>"></td>
<td style="width:250px;" align="left"  height="30" class="td_border">
<input  name="zapi<?php  echo $row->zid; ?>" style=" margin-right:10px;width:250px;" value="<?php  echo $row->zapi; ?>"></td>
<td style="width:220px;" align="left"  height="30" class="td_border">
<input  name="zinfo<?php  echo $row->zid; ?>" style=" margin-right:10px;width:220px;" value="<?php  echo $row->zinfo; ?>"></td>
<td style="width:320px;" align="left"  height="30" class="td_border">
<input type="radio" name="ztype<?php  echo $row->zid; ?>" value="1" <?php if($row->ztype == 1) echo "checked";?> class="radio" />新增+更新&nbsp
<input type="radio" name="ztype<?php  echo $row->zid; ?>" value="0" <?php if($row->ztype == 0) echo "checked";?> class="radio" />仅更新&nbsp
<input type="radio" name="ztype<?php  echo $row->zid; ?>" value="2" <?php if($row->ztype == 2) echo "checked";?> class="radio" />仅新增&nbsp
<input type="radio" name="ztype<?php  echo $row->zid; ?>" value="3" <?php if($row->ztype == 3) echo "checked";?> class="radio" />隐藏
</td>
</tr>

<?php 
}
?>

</tbody></table>	

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">					  
					<tr>
<td height="30" colspan="6" align="left" bgcolor="#FFFFFF" class="td_border" width="100%">
<input type="checkbox" name="ChkAll" id="ChkAll" ids="S_ID"  class="checkbox" onclick="checkAll(this.checked,'input','e_id[]')"/>全选
&nbsp;&nbsp; <input type="button" name="editall" value="修改所选" class="rb1"  id="btn_editall"/>
&nbsp;&nbsp;<input type="button" name="delall"  value="删除所选" class="rb1"  id="btn_delall"/></td>
					  </tr>
                  </form>
</table>   
	
	
	
	
	
	
	
	
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
 * 资源库api地址，仅支持 XML 格式，如：http://www.123.com/api.php
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
资源库描述：<input name="zinfo" value="暂无描述" style="width:400px;">
 * 简单描述资源库的基本资料
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
资源库类型：
<input type="radio" name="ztype" value="1" checked class="radio" />新增+更新&nbsp;&nbsp;
<input type="radio" name="ztype" value="0" class="radio" />仅更新&nbsp;&nbsp;
<input type="radio" name="ztype" value="2" class="radio" />仅新增&nbsp;&nbsp;
<input type="radio" name="ztype" value="3" class="radio" />隐藏
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