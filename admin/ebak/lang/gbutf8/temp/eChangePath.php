<?php 
if(!defined('InEmpireBak'))
{
	exit();
}
$onclickword='(点击转向恢复数据)';
$change=(int)$_GET['change'];
if($change==1)
{
	$onclickword='(点击选择)';
}
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理备份目录</title>
<link href="../img/style.css" rel="stylesheet" type="text/css">
<script>
function ChangePath(pathname)
{
	opener.document.<?php  echo $form ?>.mypath.value=pathname;
	window.close();
}
</script>
</head>

<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;工具&nbsp;&raquo;&nbsp;备份文件管理 ';</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="tb">
  <form name="ebakredata" method="post" action="phomebak.php" onsubmit="return confirm('确认要恢复？');">
    <tr class="thead">
	<td class="td_title" width="100%"  style="height:30px">备份文件管理</td>
      <input name="phome" type="hidden" id="phome" value="ReData">  
    </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="tb">
  <tr class="header"> 
    <td width="42%" height="25"> <div align="center">备份目录名
        <?php  echo $onclickword ?>
    </div></td>
    <td width="16%" height="25"> <div align="center">查看说明文件</div></td>
    <td width="42%"><div align="center">操作</div></td>
  </tr>
  <?php 
  while($file=@readdir($hand))
  {
	if($file!="."&&$file!=".."&&is_dir($bakpath."/".$file))
	{
		if($change==1)
		{
			$showfile="<a href='#ebak' onclick=\"javascript:ChangePath('$file');\" title='$file'>$file</a>";
		}
		else
		{
			$showfile="<a href='phome.php?phome=PathGotoRedata&mypath=$file' title='$file'>$file</a>";
		}
   ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"> <div align="left"><img src="images/dir.gif" width="19" height="15">&nbsp; 
        <?php  echo $showfile ?> </div></td>
    <td height="25"> <div align="center"> [<a href="<?php  echo $bakpath."/".$file."/readme.txt" ?>" target=_blank>查看备份说明</a>]</div></td>
    <td><div align="center">[<a href="phome.php?phome=DoZip&p=<?php  echo $file ?>&change=<?php  echo $change ?>")>打包并下载</a>]&nbsp;&nbsp;[<a href="phome.php?phome=DelBakpath&path=<?php  echo $file ?>&change=<?php  echo $change ?>" onclick="return confirm('确认要删除？');">删除备份</a>]</div></td>
  </tr>
  <?php 
     }
  }
   ?>
  <tr> 
    <td height="25" colspan="3" bgcolor="#FFFFFF"><font color="#666666">(说明：如果备份目录文件较多建议直接从FTP下载备份,备份文件存储位置：后台目录/ebak/bdata)</font></td>
  </tr>
</table>
<?php 
echo "<div align=center>";
	$starttime = explode(' ', $starttime);
	$endtime = explode(' ', microtime()); 
	echo "</div><div class=\"bottom\"><table width=\"100%\" cellspacing=\"5\"><tr><td align=\"center\"><font style=\"color:#666;\">本页面用时0.0123秒,共执行3次数据查询</font></td></tr><tr><td align=\"center\"><a target=\"_blank\" href=\"//www.seacms.net/\"><font style=\"font-size:10px;\">POWER BY SEACMS</font></a></td></tr></table></div>\n</body>\n</html>";
 ?>
</body>
</html>