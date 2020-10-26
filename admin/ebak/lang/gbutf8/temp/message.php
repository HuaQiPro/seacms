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
<style>body{background:#f9fafd;color:#818181}.mac_msg_jump{width:90%;max-width:624px;min-height:60px;padding:20px 50px 50px;margin:5% auto 0;font-size:14px;line-height:24px;border:1px solid #cdd5e0;border-radius:10px;background:#fff;box-sizing:border-box;text-align:center}.mac_msg_jump .title{margin-bottom:11px}.mac_msg_jump .text{margin-bottom:11px}.msg_jump_tit{width:100%;height:35px;margin:25px 0 10px;text-align:center;font-size:25px;color:#0099CC;letter-spacing:5px}</style>
</head>


<body leftmargin="0" topmargin="0">
<center>
<script>
      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='<?php  echo $gotourl ?>'; pgo=1; }
      }
document.write("<br /><div class='mac_msg_jump'><div class='msg_jump_tit'>系统提示</div><div class='text'>");
document.write("<?php  echo $error ?>");
document.write("<br /><br /><a href='<?php  echo $gotourl ?>'><font style='color:#777777;'>点击这里手动跳转</font></a><br/></div></div>");
setTimeout('JumpUrl()',3000);</script>
</center>
</body>