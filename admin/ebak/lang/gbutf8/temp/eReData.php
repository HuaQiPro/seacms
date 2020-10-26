<?php 
if(!defined('InEmpireBak'))
{
	exit();
}
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>恢复数据</title>
<link href="../img/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;工具&nbsp;&raquo;&nbsp;数据库恢复 ';</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="tb">
  <form name="ebakredata" method="post" action="phomebak.php" onsubmit="return confirm('确认要恢复？');">
    <tr class="thead">
	<td class="td_title" width="100%"  style="height:30px">恢复数据 </td>
      <input name="phome" type="hidden" id="phome" value="ReData">  
    </tr>
</table>
 <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="tb">
    <tr bgcolor="#FFFFFF"> 
      <td width="34%" height="25">恢复数据源文件：</td>
      <td width="66%" height="25"> 
        <?php  echo $bakpath ?>
        / 
        <input name="mypath" type="text" id="mypath" value="<?php  echo $mypath ?>"> <input type="button" name="Submit2" value="选择备份" onclick="javascript:window.open('ChangePath.php?change=1','','width=750,height=500,scrollbars=yes');"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top">要导入的数据库：</td>
      <td height="25"> <select name="add[mydbname]" size="1" id="add[mydbname]" style="width:300">
          <?php  echo $db ?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">恢复选项：</td>
      <td height="25">每组恢复间隔： 
        <input name="add[waitbaktime]" type="text" id="add[waitbaktime]" value="0" size="2">
        秒</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"> <div align="left"> 
          <input type="submit" name="Submit" value="开始恢复">
        </div></td>
    </tr>
	</form>
  </table>
<?php 
echo "<div align=center>";
	$starttime = explode(' ', $starttime);
	$endtime = explode(' ', microtime()); 
	echo "</div><div class=\"bottom\"><table width=\"100%\" cellspacing=\"5\"><tr><td align=\"center\"><font style=\"color:#666;\">本页面用时0.0123秒,共执行3次数据查询</font></td></tr><tr><td align=\"center\"><a target=\"_blank\" href=\"//www.seacms.net/\"><font style=\"font-size:10px;\">POWER BY SEACMS</font></a></td></tr></table></div>\n</body>\n</html>";
 ?>
</body>
</html>