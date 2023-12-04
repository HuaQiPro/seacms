<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if($action=="set")
{	
	$isopen = $_POST['isopen'];
	$title = htmlspecialchars($_POST['title']);
	$url = $_POST['url'];
	$ckmov_url = $_POST['ckmov_url'];
	$follow = htmlspecialchars($_POST['follow']);
	$noc = htmlspecialchars($_POST['noc']);
	$dpic = $_POST['dpic'];
	$help = htmlspecialchars($_POST['help']);
	$topage = $_POST['topage'];
	$sql_num = intval($_POST['sql_num']);
	$dwz = $_POST['dwz'];
	$dwztoken = $_POST['dwztoken'];
	
	$msg1a = $_POST['msg1a'];
	$msg1b = $_POST['msg1b'];
	$msg2a = $_POST['msg2a'];
	$msg2b = $_POST['msg2b'];
	$msg3a = $_POST['msg3a'];
	$msg3b = $_POST['msg3b'];
	$msg4a = $_POST['msg4a'];
	$msg4b = $_POST['msg4b'];
	$msg5a = $_POST['msg5a'];
	$msg5b = $_POST['msg5b'];
	
	$open=fopen("../data/admin/weixin.php","w" );
	$str='<?php  ';
	
	$str.='define("isopen", "';
	$str.="$isopen";
	$str.='"); ';
	
	$str.='define("title", "';
	$str.="$title";
	$str.='"); ';
	
	$str.='define("url", "';
	$str.="$url";
	$str.='"); ';
	
	$str.='define("ckmov_url", "';
	$str.="$ckmov_url";
	$str.='"); ';
	
	$str.='define("follow", "';
	$str.="$follow";
	$str.='"); ';
	
	$str.='define("noc", "';
	$str.="$noc";
	$str.='"); ';
	
	$str.='define("dpic", "';
	$str.="$dpic";
	$str.='"); ';
	
	$str.='define("help", "';
	$str.="$help";
	$str.='"); ';
	
	$str.='define("topage", "';
	$str.="$topage";
	$str.='"); ';
	
	$str.='define("dwz", "';
	$str.="$dwz";
	$str.='"); ';
	
	$str.='define("dwztoken", "';
	$str.="$dwztoken";
	$str.='"); ';
	
	$str.='define("sql_num", "';
	$str.="$sql_num";
	$str.='"); ';
	
	$str.='define("msg1a", "';
	$str.="$msg1a";
	$str.='"); ';
	$str.='define("msg1b", "';
	$str.="$msg1b";
	$str.='"); ';
	
	$str.='define("msg2a", "';
	$str.="$msg2a";
	$str.='"); ';
	$str.='define("msg2b", "';
	$str.="$msg2b";
	$str.='"); ';
	
	$str.='define("msg3a", "';
	$str.="$msg3a";
	$str.='"); ';
	$str.='define("msg3b", "';
	$str.="$msg3b";
	$str.='"); ';
	
	$str.='define("msg4a", "';
	$str.="$msg4a";
	$str.='"); ';
	$str.='define("msg4b", "';
	$str.="$msg4b";
	$str.='"); ';
	
	$str.='define("msg5a", "';
	$str.="$msg5a";
	$str.='"); ';
	$str.='define("msg5b", "';
	$str.="$msg5b";
	$str.='"); ';

	$str.=" ?>";
	fwrite($open,$str);
	fclose($open);
	ShowMsg("成功保存设置!","admin_weixin.php");
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信公众号设置</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;微信公众号设置 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<form action="admin_weixin.php?action=set" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">微信公众号设置</td>
</tr>
<?php  require_once("../data/admin/weixin.php"); ?>
<tr>
<td width="80%" align="left" height="30" class="td_border">
功能开关：<input type="radio" name="isopen" value="y" <?php  if(isopen=="y") echo 'checked';?>>开启
&nbsp;&nbsp;
<input type="radio" name="isopen" value="n" <?php  if(isopen=="n") echo 'checked';?>>关闭
&nbsp;&nbsp;  选择是否开启微信公共平台功能

</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">

微信域名：<input  name="url" size="40" value="<?php  echo url;?>">
  网址结尾不要加 / 符号，如域名被微信屏蔽，修改此处即可
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
微信名称：<input name="title" size="40"  value="<?php  echo title;?>">
  显示的公众号名称，可以自定义任意内容
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
解析接口：<input name="ckmov_url" size="40"  value="<?php  echo ckmov_url;?>">
  注意此处非海洋cms播放器，而是微信用户发送视频网址时调用的解析接口
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
默认封面：<input name="dpic"  size="40"  value="<?php  echo dpic;?>">
  图文消息默认封面图片地址
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
关注回复：<textarea name="follow" style="width:500px;height:50px;"><?php  echo follow;?></textarea>
  用户关注后自动回复内容
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
无内容时：<textarea name="noc" style="width:500px;height:50px;"><?php  echo noc;?></textarea>
  无对应内容时回复内容
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
帮助信息：<textarea name="help" style="width:500px;height:50px;"><?php  echo help;?></textarea>
  自定义帮助信息
</td>
</tr>


<tr>
<td width="80%" align="left" height="30" class="td_border">
跳转页面：
<input type="radio" name="topage" value="d" <?php  if(topage=="d") echo 'checked';?>>内容
&nbsp;&nbsp;
<input type="radio" name="topage" value="v" <?php  if(topage=="v") echo 'checked';?>>播放
&nbsp;&nbsp;&nbsp; 选择默认链接地址，播放页或者内容页
</td>
</tr>

<tr style="display:none;">
<td width="80%" align="left" height="30" class="td_border">
网址缩短：
<input type="radio" name="dwz" value="y" <?php  if(dwz=="y") echo 'checked';?>>开启
&nbsp;&nbsp;
<input type="radio" name="dwz" value="n" <?php  if(dwz!="y") echo 'checked';?>>关闭
&nbsp;&nbsp;&nbsp;
授权码：<input name="dwztoken"  size="20"  value="<?php  echo dwztoken;?>">
&nbsp;&nbsp; 新浪授权码，访问open.weibo.com获取
</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
展示数目：<input name="sql_num" size="6"   value="<?php  echo sql_num;?>">
  相关内容展示数量，建议不超过30，过多内容会严重影响系统效率
</td>
</tr>

<tr class="thead">
<td colspan="5" class="td_title">自定义关键词回复</td>
</tr>

<tr>
<td width="80%" align="left" height="30" class="td_border">
【1】关键词:<input name="msg1a"  size="10"  value="<?php  echo msg1a;?>">
&nbsp;&nbsp;回复内容：<textarea name="msg1b" style="width:500px;height:50px;"><?php  echo msg1b;?></textarea>
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
【2】关键词:<input name="msg2a"  size="10"  value="<?php  echo msg2a;?>">
&nbsp;&nbsp;回复内容：<textarea name="msg2b" style="width:500px;height:50px;"><?php  echo msg2b;?></textarea>
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
【3】关键词:<input name="msg3a"  size="10"  value="<?php  echo msg3a;?>">
&nbsp;&nbsp;回复内容：<textarea name="msg3b" style="width:500px;height:50px;"><?php  echo msg3b;?></textarea>
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
【4】关键词:<input name="msg4a"  size="10"  value="<?php  echo msg4a;?>">
&nbsp;&nbsp;回复内容：<textarea name="msg4b" style="width:500px;height:50px;"><?php  echo msg4b;?></textarea>
</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
【5】关键词:<input name="msg5a"  size="10"  value="<?php  echo msg5a;?>">
&nbsp;&nbsp;回复内容：<textarea name="msg5b" style="width:500px;height:50px;"><?php  echo msg5b;?></textarea>
</td>
</tr>

<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="确 认" class="btn" >
</td>
</tr>


<tr>
<td><div style="padding: 10px;border: 0;border-radius: 4px;line-height:22px;font-size: 12px;background-color: #eef5f4;">
注意：token：<font color="red"><strong>weixin</strong></font>，服务器地址：<font color="red"><strong>http://你的网址/weixin/</strong></font>，末尾有/。
<br>当用户输入<font color="red"><strong>中文</strong></font>或<font color="red"><strong>英文</strong></font>时搜索影片；<font color="red"><strong>纯数字</strong></font>时获取观看密码。
<br>默认的帮助触发关键词为<font color="red"><strong>帮助</strong></font>，留言板触发关键词为<strong><font color="red">留言</strong></font>。
<br>当用户发送以<font color="red"><strong>http</strong></font>或<font color="red"><strong>https</strong></font>开头的网址时，自动调用解析接口进行解析播放。
<br>自定义回复内容支持链接，格式：<font color="red"><strong>&lta href='xxx'&gt文字&lt/a&gt</strong></font> 注意单引号。
<br>请在微信公众平台：https://mp.weixin.qq.com中正确填写开发者选项。
<br>如果修改无效，请检查/data/admin/weixin.php文件权限是否可写。
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