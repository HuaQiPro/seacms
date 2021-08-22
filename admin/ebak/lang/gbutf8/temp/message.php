<?php 
if(!defined('InEmpireBak'))
{
	exit();
}
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息提示</title>
<style>body{background:#f9fafd;color:#818181}a {text-decoration: none}.mac_msg_jump{width:90%;max-width:420px;margin:5% auto 0;border: 1px solid #293846;border-radius: 4px;box-shadow: 0px 1px 2px rgba(0,0,0,0.1);border: 1px solid #0099CC;background-color: #F2F9FD;}.mac_msg_jump .title{margin-bottom:11px}.mac_msg_jump .text{margin-top: 20px;font-size:14px;color:#555;font-weight: normal;}.msg_jump_tit{height: 32px;padding: 0px;line-height: 32px;font-size: 14px;color: #DFE4ED;text-align: left;background: #0099CC;}</style>
</head>


<body leftmargin="0" topmargin="0">
<center>
<script>
      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='<?php  echo $gotourl ?>'; pgo=1; }
      }
document.write("<br /><div class='mac_msg_jump'><div class='text'>");
document.write("<img style='height: 28px;margin-bottom: 8px;'; src='../../pic/i2.png'><br><?php  echo $error ?>");
document.write("<br /><br /><a href='<?php  echo $gotourl ?>'><font style='color:#0099CC;font-size:12px;'>[点击这里手动跳转]</font></a><br/><br/></div></div>");
setTimeout('JumpUrl()',3000);</script>
</center>
</body>