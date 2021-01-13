<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP Web木马扫描器</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;工具&nbsp;&raquo;&nbsp;PHP Web木马扫描器 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">PHP Web木马扫描器</td>
</tr>
</tbody></table>	
</div>
<?php

ob_start();
set_time_limit(0);
$username = "t00ls"; //设置用户名
$password = "t00ls"; //设置密码
$md5 = md5(md5($username).md5($password));
$version = "PHP Web木马扫描器 v1.0";
  
$realpath = realpath('./');
$selfpath = $_SERVER['PHP_SELF'];
$selfpath = substr($selfpath, 0, strrpos($selfpath,'/'));
define('REALPATH', str_replace('//','/',str_replace('\\','/',substr($realpath, 0, strlen($realpath) - strlen($selfpath)))));
define('MYFILE', basename(__FILE__));
define('MYPATH', str_replace('\\', '/', dirname(__FILE__)).'/');
define('MYFULLPATH', str_replace('\\', '/', (__FILE__)));
define('HOST', "http://".$_SERVER['HTTP_HOST']);
?>
<html>
<head>
<title><?php echo $version?></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style>
body{margin:0px;}
body,td{font: 12px Arial,Tahoma;line-height: 16px;}
a {color: #00f;text-decoration:underline;}
a:hover{color: #f00;text-decoration:none;}
.alt1 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 10px 5px 5px;}
.alt2 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f9f9f9;padding:5px 10px 5px 5px;}
.focus td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 10px 5px 5px;}
.head td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;font-weight:bold;}
.head td span{font-weight:normal;}
</style>
</head>
<body>
<?php
if(!(isset($_COOKIE['t00ls']) && $_COOKIE['t00ls'] == $md5) && !(isset($_POST['username']) && isset($_POST['password']) && (md5(md5($_POST['username']).md5($_POST['password']))==$md5)))
{
 setcookie("t00ls", $md5, time()+60*60*24*365,"/");
 header( 'refresh: 1; url='.MYFILE.'?action=scan' );
 exit();
}
elseif(isset($_POST['username']) && isset($_POST['password']) && (md5(md5($_POST['username']).md5($_POST['password']))==$md5))
{
 setcookie("t00ls", $md5, time()+60*60*24*365,"/");
 header( 'refresh: 1; url='.MYFILE.'?action=scan' );
 exit();
}
else
{
 setcookie("t00ls", $md5, time()+60*60*24*365,"/");
 $setting = getSetting();
 $action = isset($_GET['action'])?$_GET['action']:"";
  
 if($action=="logout")
 {
  setcookie ("t00ls", "", time() - 3600);
  Header("Location: ".MYFILE);
  exit();
 }
 if($action=="download" && isset($_GET['file']) && trim($_GET['file'])!="")
 {
  $file = $_GET['file'];
  ob_clean();
  if (@file_exists($file)) {
   header("Content-type: application/octet-stream");
      header("Content-Disposition: filename=\"".basename($file)."\"");
   echo file_get_contents($file);
  }
  exit();
 }
?>
<table border="0" cellpadding="0" cellspacing="0" width="99%" style="margin-left:7px;">
 <tbody>
 <tr class="alt1">
  <td><span style="float: right;"><?=date("Y-m-d H:i:s",time())?></span>
   <a href="?action=scan">扫描</a> |
            <a href="?action=setting">设定</a> |
  </td>
 </tr>
</tbody></table>
<br>
<?php
 if($action=="setting")
 {
  if(isset($_POST['btnsetting']))
  {
   $Ssetting = array();
   $Ssetting['user']=isset($_POST['checkuser'])?$_POST['checkuser']:"php | php? | phtml";
   $Ssetting['all']=isset($_POST['checkall'])&&$_POST['checkall']=="on"?1:0;
   $Ssetting['hta']=isset($_POST['checkhta'])&&$_POST['checkhta']=="on"?1:0;
   setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*365,"/");
   echo "&nbsp;&nbsp;&nbsp;&nbsp;设置完成！";
   header( 'refresh: 1; url='.MYFILE.'?action=setting' );
   exit();
  }
?>
<form name="frmSetting" method="post" action="?action=setting">
<fieldset style="width:400px;margin-left:10px;">
<LEGEND>扫描设定</LEGEND>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60">文件后缀:</td>
    <td width="300"><input type="text" name="checkuser" id="checkuser" style="width:300px;" value="<?php echo $setting['user']?>"></td>
  </tr>
  <tr>
    <td><label for="checkall">所有文件</label></td>
    <td><input type="checkbox" name="checkall" id="checkall" <?php if($setting['all']==1) echo "checked"?>></td>
  </tr>
  <tr>
    <td><label for="checkhta">设置文件</label></td>
    <td><input type="checkbox" name="checkhta" id="checkhta" <?php if($setting['hta']==1) echo "checked"?>></td>
  </tr>
  <tr>
    <td> </td>
    <td>
      <input type="submit" name="btnsetting" id="btnsetting" value="提交">
    </td>
  </tr>
</table>
</fieldset>
</form>
<?php
 }
 else
 {
  $dir = isset($_POST['path'])?$_POST['path']:MYPATH;
  $dir = substr($dir,-1)!="/"?$dir."/":$dir;
?>
<form name="frmScan" method="post" action="">
<table width="99%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="690">
        &nbsp;&nbsp;&nbsp;&nbsp;扫描路径：<input type="text" name="path" id="path" style="width:400px" value="<?php echo $dir?>">
          <input type="submit" name="btnScan" id="btnScan" value="开始扫描"></td>
  </tr>
</table>
</form>
<?php
  if(isset($_POST['btnScan']))
  {
   $start=time();
   $is_user = array();
   $is_ext = "";
   $list = "";
    
   if(trim($setting['user'])!="")
   {
    $is_user = explode("|",$setting['user']);
    if(count($is_user)>0)
    {
     foreach($is_user as $key=>$value)
      $is_user[$key]=trim(str_replace("?","(.)",$value));
     $is_ext = "(\.".implode("($|\.))|(\.",$is_user)."($|\.))";
    }
   }
   if($setting['hta']==1)
   {
    $is_hta=1;
    $is_ext = strlen($is_ext)>0?$is_ext."|":$is_ext;
    $is_ext.="(^\.htaccess$)";
   }
   if($setting['all']==1 || (strlen($is_ext)==0 && $setting['hta']==0))
   {
    $is_ext="(.+)";
   }
    
   $php_code = getCode();
   if(!is_readable($dir))
    $dir = MYPATH;
   $count=$scanned=0;
   scan($dir,$is_ext);
   $end=time();
   $spent = ($end - $start);
?>
<div style="padding:10px; background-color:#ccc;margin:10px;">扫描: <?php echo $scanned?> 文件 | 发现: <?php echo $count?> 可疑文件 | 耗时: <?php echo $spent?> 秒 | 提示: 可疑不一定是木马文件，部分文件可能误报！</div>
<table width="99%" border="0" cellspacing="0" cellpadding="0" style="margin-left:10px;">
  <tr>
    <td width="15" align="center">No.</td>
    <td width="48%">&nbsp;文件</td>
    <td width="12%">&nbsp;更新时间</td>
    <td width="10%">&nbsp;原因</td>
    <td width="20%">&nbsp;特征</td>
    <td>&nbsp;动作</td>
  </tr>
<?php echo $list?>
</table>
<?php
  }
 }
}
ob_flush();
?>
</body>
</html>
<?php
function scan($path = '.',$is_ext){
 global $php_code,$count,$scanned,$list;
    $ignore = array('.', '..' );
 $replace=array(" ","\n","\r","\t");
    $dh = @opendir( $path );
  
    while(false!==($file=readdir($dh))){
        if( !in_array( $file, $ignore ) ){                
            if( is_dir( "$path$file" ) ){
                scan("$path$file/",$is_ext);           
            } else {
    $current = $path.$file;
    if(MYFULLPATH==$current) continue;
    if(!preg_match("/$is_ext/i",$file)) continue;
    if(is_readable($current))
    {
     $scanned++;
     $content=file_get_contents($current);
     $content= str_replace($replace,"",$content);
     foreach($php_code as $key => $value)
     {
      if(preg_match("/$value/i",$content))
      {
       $count++;
       $j = $count % 2 + 1;
       $filetime = date('Y-m-d H:i:s',filemtime($current));
       $reason = explode("->",$key);
       $url =  str_replace(REALPATH,HOST,$current);
       preg_match("/$value/i",$content,$arr);
       $list.="
   <tr class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
  <td>$count</td>
  <td><a href='$url' target='_blank'>$current</a></td>
  <td>$filetime</td>
  <td><font color=red>$reason[0]</font></td>
  <td><font color=#090>$reason[1]</font></td>
  <td><a href='?action=download&file=$current' target='_blank'>下载</a></td>
   </tr>";
       //echo $key . "-" . $path . $file ."(" . $arr[0] . ")" ."<br />";
       //echo $path . $file ."<br />";
       break;
      }
     }
    }
            }
        }
    }
    closedir( $dh );
}
function getSetting()
{
 $Ssetting = array();
 if(isset($_COOKIE['t00ls_s']))
 {
  $Ssetting = unserialize(base64_decode($_COOKIE['t00ls_s']));
  $Ssetting['user']=isset($Ssetting['user'])?$Ssetting['user']:"php | php? | phtml | shtml";
  $Ssetting['all']=isset($Ssetting['all'])?intval($Ssetting['all']):0;
  $Ssetting['hta']=isset($Ssetting['hta'])?intval($Ssetting['hta']):1;
 }
 else
 {
  $Ssetting['user']="php | php? | phtml | shtml";
  $Ssetting['all']=0;
  $Ssetting['hta']=1;
  setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*365,"/");
 }
 return $Ssetting;
}
function getCode()
{
 return array(
 '后门特征->cha88.cn'=>'cha88\.cn',
 '后门特征->c99shell'=>'c99shell',
 '后门特征->phpspy'=>'phpspy',
 '后门特征->Scanners'=>'Scanners',
 '后门特征->cmd.php'=>'cmd\.php',
 '后门特征->str_rot13'=>'str_rot13',
 '后门特征->webshell'=>'webshell',
 '后门特征->EgY_SpIdEr'=>'EgY_SpIdEr',
 '后门特征->tools88.com'=>'tools88\.com',
 '后门特征->SECFORCE'=>'SECFORCE',
 '后门特征->eval("?>'=>'eval\((\'|")\?>',
 '可疑需人工判断->system('=>'system\(',
 '可疑需人工判断->passthru('=>'passthru\(',
 '可疑需人工判断->shell_exec('=>'shell_exec\(',
 '可疑需人工判断->exec('=>'exec\(',
 '可疑需人工判断->popen('=>'popen\(',
 '可疑需人工判断->proc_open'=>'proc_open',
 '可疑需人工判断->eval($'=>'eval\((\'|"|\s*)\\$',
 '可疑需人工判断->assert($'=>'assert\((\'|"|\s*)\\$',
 '危险MYSQL代码->returns string soname'=>'returnsstringsoname',
 '危险MYSQL代码->into outfile'=>'intooutfile',
 '危险MYSQL代码->load_file'=>'select(\s+)(.*)load_file',
 '加密后门特征->eval(gzinflate('=>'eval\(gzinflate\(',
 '加密后门特征->eval(base64_decode('=>'eval\(base64_decode\(',
 '加密后门特征->eval(gzuncompress('=>'eval\(gzuncompress\(',
 '加密后门特征->eval(gzdecode('=>'eval\(gzdecode\(',
 '加密后门特征->eval(str_rot13('=>'eval\(str_rot13\(',
 '加密后门特征->gzuncompress(base64_decode('=>'gzuncompress\(base64_decode\(',
 '加密后门特征->base64_decode(gzuncompress('=>'base64_decode\(gzuncompress\(',
 '一句话后门特征->eval($_'=>'eval\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->assert($_'=>'assert\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->require($_'=>'require\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->require_once($_'=>'require_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->include($_'=>'include\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->include_once($_'=>'include_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->call_user_func("assert"'=>'call_user_func\(("|\')assert("|\')', 
 '一句话后门特征->call_user_func($_'=>'call_user_func\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '一句话后门特征->$_POST/GET/REQUEST/COOKIE[?]($_POST/GET/REQUEST/COOKIE[?]'=>'\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\]\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[',
 '一句话后门特征->echo(file_get_contents($_POST/GET/REQUEST/COOKIE'=>'echo\(file_get_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',                                    
 '上传后门特征->file_put_contents($_POST/GET/REQUEST/COOKIE,$_POST/GET/REQUEST/COOKIE'=>'file_put_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\],(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
 '上传后门特征->fputs(fopen("?","w"),$_POST/GET/REQUEST/COOKIE['=>'fputs\(fopen\((.+),(\'|")w(\'|")\),(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[',
 '.htaccess插马特征->SetHandler application/x-httpd-php'=>'SetHandlerapplication\/x-httpd-php',
 '.htaccess插马特征->php_value auto_prepend_file'=>'php_valueauto_prepend_file',
 '.htaccess插马特征->php_value auto_append_file'=>'php_valueauto_append_file'
 );
}
?>
<br><br>
</div>
</div>
<?php 
viewFoot();
?>
</body>
</html>