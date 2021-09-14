<?php 
if(!defined('InEmpireBak'))
{
	exit();
}
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>下载压缩包</title>
<link href="../img/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0"  cellpadding="3" cellspacing="1" class="tb">
<tr class="thead">
	<td class="td_title" width="100%"  style="height:30px"> <div>下载压缩包【目录： 
        <?php  echo $p ?>
        】</div></td>
  </tr>
  <tr> 
    <td height="30" bgcolor="#FFFFFF"> 
      <div align="center">[<a href="<?php  echo $file ?>">下载压缩包</a>]</div></td>
  </tr>
  <tr> 
    <td height="30" bgcolor="#FFFFFF"> 
      <div align="center">[<a href="phome.php?f=<?php  echo $f ?>&phome=DelZip" onclick="return confirm('确认要删除？');">删除压缩包</a>]</div></td>
  </tr>
  <tr>
    <td height="30" bgcolor="#FFFFFF">
<div align="center">（<font color="#FF0000">说明：安全起见，下载完毕请马上删除压缩包．</font>）</div></td>
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