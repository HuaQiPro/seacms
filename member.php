<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title>会员中心</title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex,nofollow" />
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="pic/member/bootstrap.min.css" rel="stylesheet" type="text/css" />				
<link href="pic/member/swiper.min.css" rel="stylesheet" type="text/css" >		
<link href="pic/member/iconfont.css" rel="stylesheet" type="text/css" />
<link href="pic/member/color.css" rel="stylesheet" type="text/css" />
<link href="pic/member/style.min.css" rel="stylesheet" type="text/css" />
<script src="pic/member/jquery.min.js"></script>
<script type="text/javascript" src="pic/member/bootstrap.min.js"></script>
<script type="text/javascript" src="pic/member/layer.js"></script>
<style>
.sc{padding:6px 6px 6px 6px;text-align: center;}
.sc input{margin-top:5px;margin-button:5px;width:120px;}
.pspan span{color:#ab1010;}
.sea_page{text-align:center; display:block;margin-top:20px;}
.sea_page_box{display:inline-block;zoom:1;*display:inline;margin:auto;}
.sea_page a{float:left;font-family:Tahoma;height:22px;line-height:22px;padding:0 8px;margin-left:3px;background-color:#ddd;border-radius: 4px;font-size: 12px;color: #405884;list-style:none;}
.sea_page a:link {color:#405884;text-decoration:none;}
.sea_page a:visited {color:#405884;text-decoration:none;}
.sea_page a:hover {color:#405884;text-decoration:none;background:#6394c8;}
.sea_page a:active {color:#405884;text-decoration:none;}
.sea_page .sea_num{float:left;font-family:Tahoma;height:22px;line-height:22px;padding:0 8px;margin-left:3px;background-color:#6394c8;border-radius: 4px;font-size: 12px;color: #fff;list-style:none;}
</style>
</head>
<?php 
session_start();
require_once("include/common.php");
require_once(sea_INC.'/main.class.php');
if($cfg_user==0)
{
	ShowMsg('系统已关闭会员功能!','index.php');
	exit();
}

if($cfg_vipoff_1==0 OR $cfg_vipoff_1=="" OR empty($cfg_vipoff_1)){$cfg_vipoff_1=1;}
if($cfg_vipoff_3==0 OR $cfg_vipoff_3=="" OR empty($cfg_vipoff_3)){$cfg_vipoff_3=1;}
if($cfg_vipoff_6==0 OR $cfg_vipoff_6=="" OR empty($cfg_vipoff_6)){$cfg_vipoff_6=1;}
if($cfg_vipoff_12==0 OR $cfg_vipoff_12=="" OR empty($cfg_vipoff_12)){$cfg_vipoff_12=1;}
?>
<script>
//全屏弹出层
    var show=function (id,points,pname) {
        var index = layer.open({
            type: 1,
			anim: 'up',
			style: 'position:fixed; bottom:0px; left:0; width: 100%; padding:10px 0; border:none;',
            title:"选择时长",
			content: '<div class="sc"><input type="submit" class="btn btn-info btn-lg" value="单月&nbsp;&nbsp;'+points*1*<?php echo $cfg_vipoff_1;?>+''+pname+'" onclick=self.location="?action=hyz&gid='+id+'&mon=1"><br><input type="submit" class="btn btn-success btn-lg" value="三月&nbsp;&nbsp;'+points*3*<?php echo $cfg_vipoff_3;?>+''+pname+'" onclick=self.location="?action=hyz&gid='+id+'&mon=3"><br><input type="submit" class="btn btn-primary btn-lg" value="半年&nbsp;&nbsp;'+points*6*<?php echo $cfg_vipoff_6;?>+''+pname+'" onclick=self.location="?action=hyz&gid='+id+'&mon=6"><br><input type="submit" class="btn btn-danger btn-lg" value="一年&nbsp;&nbsp;'+points*12*<?php echo $cfg_vipoff_12;?>+''+pname+'" onclick=self.location="?action=hyz&gid='+id+'&mon=12"></div>'
        }); 
    }
</script>
<?php
$action = isset($action) ? trim($action) : 'cc';
$page = isset($page) ? intval($page) : 1;
$uid=$_SESSION['sea_user_id'];
$uid = intval($uid);
$year=date('Y');
//邮件激活
if($mod=='activate'){
	require_once('data/admin/smtp.php');
	if($smtpreg=='off'){showMsg("抱歉，系统已关闭邮件激活功能！","index.php",0,100000);exit();}
	$dsql->ExecuteNoneQuery("update `sea_member` set acode = 'y' where acode='$acode'");
	showMsg("恭喜，账户激活成功！","login.php",0,3000);exit();
}


//找回密码
if($mod=='repsw'){
	require_once('data/admin/smtp.php');
	if($smtppsw=='off'){showMsg("抱歉，系统已关闭密码找回功能！","index.php",0,100000);exit();}
	echo <<<EOT
	        
<body>
	<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	<div class="hy-member-user hy-layout clearfix">
    			<div class="item">
    				
    				<dl class="margin-0 clearfix">
    					<dt><span class="user"></span></dt>
    					<dd>
    						<span class="name">正在找回您的密码<span>
    						<span class="group">通过电子邮箱找回您的密码<span>
    					</dd>
    			   </dl>   				
    			</div>
	    	</div>	    	
		    <div class="hy-member hy-layout clearfix">
		    	
				
				<form action="?mod=repsw2" method="post"><li class="cckkey"><span class="text-muted"><strong>系统会发送重置密码链接到您的注册邮箱<br>输入会员账号：</strong><br></span><input type="text" name="repswname" class="form-control" id="repswname" placeholder="输入会员账号" style="width:250px;"> <br><input type="submit" name="cckb" id="cckb" value="找回密码" class="btn btn-warning"></li></form>
		    	               
		    </div>
		   
	    </div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
	exit();
}

if($mod=='repsw2'){
	
	require_once('data/admin/smtp.php');
	if($smtppsw=='off'){showMsg("抱歉，系统已关闭密码找回功能！","index.php",0,100000);exit();}
	
	if(empty($repswname)){{showMsg("请输入账户名称！","-1",0,3000);exit();}}
	
	$row=$dsql->GetOne("select * from sea_member where username='$repswname'");
	$repswemail=$row['email'];
	$repswstate=$row['state'];
	if(empty($repswemail) OR $repswemail==""){{showMsg("输入的账户名称不存在！","-1",0,10000);exit();}}
	if($repswstate !=1){{showMsg("您的账户已被管理员禁用，禁止找回密码！","-1",0,10000);exit();}}
	
	$randtime=uniqid();
	$repswcode=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser.$randtime); //构造唯一码
	$dsql->ExecuteNoneQuery("update `sea_member` set repswcode = '$repswcode' where username= '$repswname'");
	
	require_once('data/admin/smtp.php');

		
	$smtprmail = $repswemail;
	$smtprtitle = '【'.$cfg_webname.'】Email 找回密码操作邮件';
$smtprbody = '<strong>Email 找回密码操作邮件</strong><br><br>尊敬的：'.$repswname.'<br>这封信是由 '.$cfg_webname.' 发送的。<br>您收到这封邮件，是由于在 '.$cfg_webname.' 进行了找回密码操作。如果您并没有访问过 '.$cfg_webname.'，或没有进行上述操作，请忽略这封邮件。您不需要退订或进行其他进一步的操作。<br><br>如果您是 '.$cfg_webname. '的用户并且正在进行找回密码操作，我们需要对您的信息的有效性进行验证以避免密码被盗用。<br>您只需点击下面的链接即可重置您的密码：<br><a target="_blank" href="'.$cfg_basehost.'/member.php?mod=repsw3&repswcode='.$repswcode.'&repswname='.$repswname.'">'.$cfg_basehost.'/member.php?mod=repsw3&repswcode='.$repswcode.'&repswname='.$repswname.'</a><br>(如果上面不是链接形式，请将该地址手工粘贴到浏览器地址栏再访问)<br>此链接只允许访问一次！<br><br>感谢您的访问，祝您使用愉快！<br><br>此致<br>'.$cfg_webname.'管理团队.<br>'.$cfg_basehost.'<br>';

	require_once("include/class.phpmailer.php"); 
	$mail = new PHPMailer();
	$mail->SMTPDebug = 0;//是否启用smtp的debug进行调试
	$mail->isSMTP();
	$mail->SMTPAuth=true;//smtp需要鉴权 这个必须是true
	$mail->Host = $smtpserver;//服务器地址
	$mail->SMTPSecure = 'ssl';//设置使用ssl加密方式登录鉴权
	$mail->Port = intval($smtpserverport);//设置ssl连接smtp服务器的远程服务器端口号 可选465或587
	$mail->CharSet = 'UTF-8';
	$mail->FromName = $smtpname;//设置发件人名称
	$mail->Username =$smtpuser;//smtp登录的账号
	$mail->Password = $smtppass;//smtp登录的密码 
	$mail->From = $smtpusermail;//发件人邮箱地址
	$mail->isHTML(true); //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
	$mail->addAddress($smtprmail);//设置收件人邮箱地址 
	$mail->Subject ="=?utf-8?B?" . base64_encode($smtprtitle) . "?=";//添加该邮件的主题
	//$mail->Subject = $smtprtitle;//添加该邮件的主题
	$mail->Body = $smtprbody;//添加邮件正文

	 
	//发送命令 返回布尔值 
	//PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
	$status = $mail->send();
	 
	//简单的判断与提示信息
	if($status) {
	 ShowMsg('申请成功!<br>一封验证邮件已发送到您的注册邮箱，请查收！','login.php',0,100000);
	}else{
	 ShowMsg('抱歉！激活邮件发送失败，请联系客服解决此错误。','login.php',0,100000);
	}						
	
	exit();
}



if($mod=='repsw3'){
	require_once('data/admin/smtp.php');
	if($smtppsw=='off'){showMsg("抱歉，系统已关闭密码找回功能！","index.php",0,100000);exit();}
	
	$repswname=$_GET['repswname'];
	$repswcode=$_GET['repswcode'];
	
	$repswname = RemoveXSS(stripslashes($repswname));
	$repswname = addslashes(cn_substr($repswname,60));
	
	$repswcode = RemoveXSS(stripslashes($repswcode));
	$repswcode = addslashes(cn_substr($repswcode,60));
	
	if(empty($repswname) OR $repswname==""){showMsg("授权码错误或已过期！","index.php",0,100000);exit();}
	if(empty($repswcode) OR $repswcode=="" OR $repswcode=="y"){showMsg("授权码错误或已过期！","index.php",0,100000);exit();}
	
	$row=$dsql->GetOne("select * from sea_member where username='$repswname'");
	$repswcode2=$row['repswcode'];

	if($repswcode != $repswcode2){showMsg("授权码错误或已过期！","index.php",0,100000);exit();}
	echo <<<EOT
	        
<body>
	<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	<div class="hy-member-user hy-layout clearfix">
    			<div class="item">
    				
    				<dl class="margin-0 clearfix">
    					<dt><span class="user"></span></dt>
    					<dd>
    						<span class="name">正在找回您的密码<span>
    						<span class="group">通过电子邮箱找回您的密码<span>
    					</dd>
    			   </dl>   				
    			</div>
	    	</div>	    	
		    <div class="hy-member hy-layout clearfix">
		    	
				
				<form action="?mod=repsw4" method="post"><li class="cckkey"><strong>会员账号：{$repswname}</strong><br><br>新密码：<br></span><input type="password" name="repswnew1" class="form-control" id="repswnew1" value="" style="width:250px;"><br>确认新密码：<br></span><input type="password" name="repswnew2" class="form-control" id="repswnew2" value="" style="width:250px;"> <br><input type="hidden" name="repswname" id="repswname" value="{$repswname}"><input type="hidden" name="repswcode" id="repswcode" value="{$repswcode}"><input type="submit" name="cckb" id="cckb" value="提交" class="btn btn-warning"></li></form>
		    	               
		    </div>

	    </div>
	</div>
	<div class="tabbar visible-xs">
<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>	
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
	exit();
}


if($mod=='repsw4'){
	$repswname=$_POST['repswname'];
	$repswcode=$_POST['repswcode'];
	$repswnew1=$_POST['repswnew1'];
	$repswnew2=$_POST['repswnew2'];
	
	$repswcode = RemoveXSS(stripslashes($repswcode));
	$repswcode = addslashes(cn_substr($repswcode,60));
	
	$repswname = RemoveXSS(stripslashes($repswname));
	$repswname = addslashes(cn_substr($repswname,60));
	
	$repswnew1 = RemoveXSS(stripslashes($repswnew1));
	$repswnew1 = addslashes(cn_substr($repswnew1,60));
	
	$repswnew2 = RemoveXSS(stripslashes($repswnew2));
	$repswnew2 = addslashes(cn_substr($repswnew2,60));
	
	require_once('data/admin/smtp.php');
	if($smtppsw=='off'){showMsg("抱歉，系统已关闭密码找回功能！","index.php",0,100000);exit();}
	if($repswnew1 != $repswnew2){showMsg("两次输入密码不一致！","-1",0,3000);exit();}
	if(empty($repswname) OR $repswname==""){showMsg("授权码错误或已过期！","index.php",0,100000);exit();}
	if(empty($repswcode) OR $repswcode=="" OR $repswcode=="y"){showMsg("授权码错误或已过期！","index.php",0,100000);exit();}

	$row=$dsql->GetOne("select * from sea_member where username='$repswname'");
	$repswcode2=$row['repswcode'];

	if($repswcode != $repswcode2){showMsg("授权码错误或已过期！","index.php",0,100000);exit();}
	
	$pwd = substr(md5($repswnew1),5,20);

	$dsql->ExecuteNoneQuery("update `sea_member` set password = '$pwd',repswcode = 'y' where username='$repswname'");
	ShowMsg('密码重置成功，请使用新密码登陆！','login.php');
	exit();
	
	
}



////////////////////////////////////
$front='front';
$hashstr=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser.$front);//构造session安全码
if(empty($uid) OR $_SESSION['hashstr'] !== $hashstr)
{
	showMsg("请先登录","login.php");
	exit();
}


if($action=='chgpwdsubmit')
{
	if(trim($newpwd)<>trim($newpwd2))
	{
		ShowMsg('两次输入密码不一致','-1');	
		exit();	
	}
	$result  = filter_var($email, FILTER_VALIDATE_EMAIL);if($result==false){ShowMsg('请输入正确的邮箱地址','-1');exit();}

//处理头像上传
function ImageResize2($srcFile,$toW,$toH,$toFile="")
{

	if($toFile=="")
	{
		$toFile = $srcFile;
	}
	
	$srcInfo = getimagesize($srcFile);
	switch ($srcInfo[2])
	{
		case 1:
			$im = imagecreatefromgif($srcFile);
			break;
		case 2:
			$im = imagecreatefromjpeg($srcFile);
			break;
		case 3:
			$im = imagecreatefrompng($srcFile);
			break;
		case 18:
			$im = imagecreatefromwebp($srcFile);
			break;
		case 6:
			$im = imagecreatefromwbmp($srcFile);
			break;
	}
	$srcW=ImageSX($im);
	$srcH=ImageSY($im);
	if($srcW<=$toW && $srcH<=$toH )
	{
		return true;
	}
	$toWH=$toW/$toH;
	$srcWH=$srcW/$srcH;
	if($toWH<=$srcWH)
	{
		$ftoW=$toW;
		$ftoH=$ftoW*($srcH/$srcW);
	}
	else
	{
		$ftoH=$toH;
		$ftoW=$ftoH*($srcW/$srcH);
	}
	if($srcW>$toW||$srcH>$toH)
	{
		if(function_exists("imagecreatetruecolor"))
		{
			@$ni = imagecreatetruecolor($ftoW,$ftoH);
			if($ni)
			{
				imagecopyresampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
			else
			{
				$ni=imagecreate($ftoW,$ftoH);
				imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
		}
		else
		{
			$ni=imagecreate($ftoW,$ftoH);
			imagecopyresized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
		}
		switch ($srcInfo[2])
		{
			case 1:
				imagegif($ni,$toFile);
				break;
			case 2:
				imagejpeg($ni,$toFile,99);
				break;
			case 3:
				imagepng($ni,$toFile);
				break;
			case 18:
				imagewebp($ni,$toFile);
				break;
			case 6:
				imagebmp($ni,$toFile);
				break;
			default:
				return false;
		}
		imagedestroy($ni);
	}
	imagedestroy($im);
	return true;
}	
	if($_FILES['image']['name'] !="" AND $cfg_upic=='1'){
		
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $name_arr = explode('.',$_FILES['image']['name']);
        $file_ext=strtolower(end($name_arr));
        $extensions= array("jpeg","jpg","png","gif","bmp","webp");
        /* 规定可以上传的扩展名文件 */
		if($file_size > 2048000) {$errors='头像文件大小不能超过2M';ShowMsg($errors,'-1');exit;} 
		if(in_array($file_ext,$extensions)=== false){$errors="头像文件必须是图片";ShowMsg($errors,'-1');exit;}
		$is_img = getimagesize($_FILES["image"]["tmp_name"]);
		if(!$is_img){$errors="头像文件必须是图片";ShowMsg($errors,'-1');exit;}
       
        
       /* 把图片从临时文件夹内的文件移动到当前脚本所在的目录 */
		$path="uploads/user/".date('Y-n',time())."/";
		$path2=$path.$_SESSION['sea_user_id']."_".time().".".$file_ext;
		mkdir($path);
		$picok=move_uploaded_file($file_tmp,$path2); 
		if($picok==false){$path2 ="";}		
		if($picok AND $oldpic !='uploads/user/a.png'){unlink($oldpic);}
	}
	if($path2 !=""){
	$filePath = sea_ROOT.'/'.$path2;
	$errno2= ImageResize2($filePath,$cfg_ddimg_width="100",$cfg_ddimg_height="100",$toFile="");
	}
	
	if($path2 ==""){$path2=$oldpic;}
	$nickname = RemoveXSS(stripslashes($nickname));
	$nickname = addslashes(cn_substr($nickname,60));
	if(!empty($newpwd)||!empty($email)||!empty($nickname))
	{
	if(empty($newpwd)){$pwd = $oldpwd;} else{$pwd = substr(md5($newpwd),5,20);};
	$dsql->ExecuteNoneQuery("update `sea_member` set password = '$pwd',email = '$email',nickname = '$nickname',pic='$path2' where id= '$uid'");
	ShowMsg('个人信息修改成功','-1');	
	exit();	
	}
}
elseif($action=='cancelfav')
{
	$id=intval($id);
	$dsql->executeNoneQuery("delete from sea_favorite where id=".$id);
	echo "<script>location.href='?action=favorite'</script>";
	exit();
}elseif($action=='cancelfavs')
{
	if(empty($fid))
	{
		showMsg("请选择要取消收藏的视频","-1");
		exit();
	}
	foreach($fid as $id)
	{
		$id=intval($id);
		$dsql->executeNoneQuery("delete from sea_favorite where id=".$id);
	}
	echo "<script>location.href='?action=favorite'</script>";
	exit();
}
elseif($action=='cz')
{
	
	$key=$_POST['cckkey'];
	$key = RemoveXSS(stripslashes($key));
	$key = addslashes(cn_substr($key,200));
	if($key==""){showMsg("没有输入充值卡号","-1");exit;}
	$sqlt="SELECT * FROM sea_cck where ckey='$key'";
	$row1 = $dsql->GetOne($sqlt);
    if(!is_array($row1) OR $row1['status']<>0){
        showMsg("充值卡不正确或已被使用","-1");exit;
    }else{
		$uname=$_SESSION['sea_user_name'];
		$points=$row1['climit'];
        $dsql->executeNoneQuery("UPDATE sea_cck SET usetime=NOW(),uname='$uname',status='1' WHERE ckey='$key'");
		$dsql->executeNoneQuery("UPDATE sea_member SET points=points+$points WHERE username='$uname'");
		showMsg("恭喜！充值成功！","member.php?action=cc");exit;
    }
}
elseif($action=='hyz')
{
	//对所有数据进行重新查询，防止伪造POST数据进行破解
	
	//获取会员组基本信息
	$gid = intval($gid);
	if(empty($gid))
	{showMsg("请选择要购买的会员组","member.php?action=cc");exit;}
	$mon = intval($mon);
	if(empty($mon))
	{showMsg("请选择要购买的时长","member.php?action=cc");exit;}
	$sqlhyz1="SELECT * FROM sea_member_group where gid='$gid'"; 
	$rowhyz1 = $dsql->GetOne($sqlhyz1);
    if(!is_array($rowhyz1)){
        showMsg("会员组不存在","-1");exit;
    }else{
		$hyzjf=$rowhyz1['g_upgrade']*$mon; //购买会员组所需积分  
    }
	//获取会员基本信息
	$uname=$_SESSION['sea_user_name'];
	$sqlhyz2="SELECT points,vipendtime FROM sea_member where username='$uname'"; 
	$rowhyz2 = $dsql->GetOne($sqlhyz2);
    if(!is_array($rowhyz2)){
        showMsg("会员信息不存在","-1");exit;
    }else{
		$userjf=$rowhyz2['points']; //会员剩余积分
    }
	//计算折扣
	if($mon==1){$cfg_vipoff=$cfg_vipoff_1;}
	elseif($mon==3){$cfg_vipoff=$cfg_vipoff_3;}
	elseif($mon==6){$cfg_vipoff=$cfg_vipoff_6;}
	elseif($mon==12){$cfg_vipoff=$cfg_vipoff_12;}
	else{$cfg_vipoff=$cfg_vipoff_1;}
	$hyzjf=$hyzjf*$cfg_vipoff;
	//echo '<br><br><br>===';echo $hyzjf;echo '===<br><br><br>';die;
	if($userjf<$hyzjf)
	{
		showMsg("积分不足","-1");exit; //判断积分是否足够购买
	} 
	else
	{
		$ntime=time();
		if($_SESSION['sea_user_group']==$gid){$ntime=$rowhyz2['vipendtime'];}
		$vipendtime1=2678400*$mon;
		$vipendtime=$ntime+$vipendtime1;
		$sql="UPDATE sea_member SET points=points-$hyzjf,gid=$gid,vipendtime=$vipendtime where username='$uname'";
		$dsql->executeNoneQuery($sql);
		$dsql->ExecuteNoneQuery("insert into sea_hyzbuy values('','$uname','$gid','$hyzjf','$mon','".time()."')");
		showMsg("恭喜！购买会员组成功！","member.php");exit;
	}
	
}
elseif($action=='cc')
{
	$ccuid=intval($_SESSION['sea_user_id']);
	$cc2=$dsql->GetOne("select * from sea_member where id=$ccuid");
	$ccgid=$cc2['gid'];	
	$_SESSION['sea_user_group'] = $ccgid;
	$cc1=$dsql->GetOne("select * from sea_member_group where gid=$ccgid");
	$ccgroup=$cc1['gname'];
	$ccgroupupgrade=$cc1['g_upgrade'];
	
	$ccjifen=$cc2['points'];
	$ccemail=$cc2['email'];
	$ccvipendtime=$cc2['vipendtime'];
	if($ccvipendtime<time()){
		$_SESSION['sea_user_group'] = 2;
		$dsql->ExecuteNoneQuery("update `sea_member` set gid=2 where id=$ccuid");
		if($ccgid !=2){
			ShowMsg("您购买的会员组已到期，请注意续费!","member.php",0,5000);exit;
		}
	}
	if($ccgid==2){$ccvipendtime='无限期';}else{$ccvipendtime=date('Y-m-d H:i:s',$ccvipendtime);}
	$cclog=$cc2['logincount'];
	$ccnickname=$cc2['nickname'];
	$msgbody=nl2br($cc2['msgbody']);
	$msgstate=$cc2['msgstate'];
	
	if($cfg_spoints==0){
		$stxt= '';
	 }else{
		$u=addslashes($_SESSION['sea_user_id']);
		if(empty($u) OR !is_numeric($u)){exit;}
		$row = $dsql->GetOne("Select stime from sea_member where id='$u'");
		$nowtime=time();
		$lasttime=$row['stime'];	
		if($nowtime-$lasttime > 86400 )
		{$stxt= '<button onClick="self.location=\'s.php\'" class="btn btn-warning" type="button"><strong>我要签到</strong> <span class="badge">+'.$cfg_pointsname.'</span>  </button>';}
		else
		{$stxt= '<button class="btn"  type="button"><strong>今日已经签到</strong></button>';}
		
	 }
require_once("data/admin/notify.php");
if(empty($notify1) OR $notify1 ==""){$notify1css='display:none';} 
if(empty($notify2) OR $notify2 ==""){$notify2css='display:none';} 
if(empty($notify3) OR $notify3 ==""){$notify3css='display:none';}
if(empty($msgbody) OR $msgbody =="" OR $msgstate=='y'){$notify4css='display:none';$msgbody='';} 
$upic=$cc2['pic'];	
if($upic ==""){$upic='uploads/user/a.png';}  
	echo <<<EOT
	        
<body>
	<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
					<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  		<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png"/></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	<div class="hy-member-user hy-layout clearfix">
    			<div class="item">
    				<div class="integral">{$cfg_pointsname}：{$ccjifen}</div>
    				<dl class="margin-0 clearfix">
    					<dt><span class="user"><img src="{$upic}" style="width:60px;height:60px;border-radius:50%;"/></span></dt>
    					<dd>
    						<span class="name">{$_SESSION['sea_user_name']}<span>
    						<span class="group">{$ccgroup}<span>
    					</dd>
    			   </dl>   				
    			</div>
	    	</div>	 


<div class="alert alert-info alert-dismissible" role="alert" style="margin:3px -1px;{$notify1css}">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="icon iconfont icon-notice"></i></span></button>
{$notify1}
</div>
<div class="alert alert-info alert-dismissible" role="alert" style="margin:3px -1px;{$notify2css}">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="icon iconfont icon-notice"></i></span></button>
{$notify2}
</div>
<div class="alert alert-info alert-dismissible" role="alert" style="margin:3px -1px;{$notify3css}">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="icon iconfont icon-notice"></i></span></button>
{$notify3}
</div>
<div class="alert alert-success alert-dismissible" role="alert" style="margin:3px -1px;{$notify4css}">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="icon iconfont icon-icon_special"></i></span></button>
{$msgbody}<a style="float:right;" href="?action=delmsg"><i class="icon iconfont icon-delete" style="color:#888;"><font style="font-size:12px;">删除</font></i></a>
</div>   	
		    <div class="hy-member hy-layout clearfix" style="margin-top:5px;">
		    	<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a> 
						<a class="text-muted pull-right hidden-xs" style="margin-right:10px;" href="index.php"><i class="icon iconfont icon-home"></i> 网站首页</a> 
						
						<li class="active"><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li><a href="?action=buy" title="消费">消费</a></li>
						<li><a href="?action=Rc" title="互动">互动</a></li>						
					</ul>
				</div>
		    	<div class="tab-content">
					<div class="tab-pane fade in active">
						<div class="col-md-9 col-sm-12 col-xs-12">					
							<ul class="user">
							{$stxt}
								<li><span class="text-muted">会员编号：</span>{$_SESSION['sea_user_id']}</li>
								<li><span class="text-muted">您的账户：</span>{$_SESSION['sea_user_name']}</li>
								<li><span class="text-muted">您的邮箱：</span>{$ccemail}</li>
								<li><span class="text-muted">联系方式：</span>{$ccnickname}</li>
								<li><span class="text-muted">登陆次数：</span>{$cclog}</li>
								<li><span class="text-muted">会员时限：</span>{$ccvipendtime}</li>
EOT;
			                echo  "<li><span class=\"text-muted\">用户级别：</span>{$ccgroup}</li><br><span class=\"text-muted\">升级会员：</span>";
							$sql="select * from sea_member_group where g_upgrade >= $ccgroupupgrade and gid>2";
							
							$dsql->SetQuery($sql);
							$dsql->Execute('al');
							while($rowr=$dsql->GetObject('al'))
							{
								if($ccgid==$rowr->gid){$xufei='续费&nbsp;';}else{$xufei='';}
								echo "&nbsp;<input type=\"submit\" class=\"btn btn-info btn-xs\" value='".$xufei.$rowr->gname."' onClick=\"show(".$rowr->gid.",".$rowr->g_upgrade.",'".$cfg_pointsname."');\">";
							}
								if($cfg_kami==""){$kamistyle='style="display:none;"';}
								echo
			                     "<li><span class=\"text-muted\">{$cfg_pointsname}数量：</span>{$ccjifen} {$cfg_pointsname}</li>".
			                     "<li><span class=\"text-muted\">推广链接：</span>{$_SERVER['HTTP_HOST']}/i.php?uid={$_SESSION['sea_user_id']}</li>".
			                    "<form action=\"?action=cz\" method=\"post\"><li class=\"cckkey\"><span class=\"text-muted\">充值积分：</span><input type=text name=cckkey class=\"form-control\" id=cckkey placeholder=\"输入充值卡卡号\" > <input type=submit name=cckb id=cckb value='{$cfg_pointsname}充值' class=\"btn btn-primary\">&nbsp;&nbsp;<a target=\"_blank\" class=\"btn btn-danger\" ".$kamistyle." href=\"".$cfg_kami."\">购买充值卡</a></li></form></div>";
			echo <<<EOT
												
																			
											</ul>
										</div>
										<div class="col-md-3 col-sm-12 col-xs-12">
											<ul class="password">
												<h3 class="text-muted">修改个人信息</h3>
EOT;
						$row1=$dsql->GetOne("select * from sea_member where id='$uid'");
							$oldpwd=$row1['password'];
							$oldemail=$row1['email'];
							$oldpic=$row1['pic'];
							$oldnickname=$row1['nickname'];
							if($cfg_upic !='1'){$upicstyle=' style="display:none;"';}
							echo "<form id=\"f_Activation\"   action=\"?action=chgpwdsubmit\" method=\"post\" enctype=\"multipart/form-data\">".
								"<li><input type=\"password\" name=\"oldpwd\" value=\"$oldpwd\" class=\"form-control\" placeholder=\"输入旧密码\" /></li>". "<li><input type=\"password\" name=\"newpwd\"  class=\"form-control\" placeholder=\"输入新密码\" /></li>".  
								"<li><input type=\"password\" name=\"newpwd2\" class=\"form-control\" placeholder=\"再次确认\" /></li>".  
								"<li><input type=\"text\" name=\"email\" value=\"$oldemail\" class=\"form-control\" placeholder=\"邮箱地址\" /></li>".
								"<li><input type=\"text\" name=\"nickname\" value=\"$oldnickname\" class=\"form-control\" placeholder=\"联系方式\" /></li>".
							    "<li ".$upicstyle."><label>上传头像</label><input type=\"file\" name=\"image\" id=\"image\" multiple class=\"file-loading\" /></li>".
								"<li style=\"display:none;\"><input type=\"hidden\" name=\"oldpic\" value=\"$oldpic\" /></li>".
								"<li><input type=\"submit\" name=\"gaimi\" class=\"btn btn-block btn-warning\" value=\"确认修改\"></li>".
						        "</form>";
						echo <<<EOT
							</ul>
						</div>
					</div>            
			   </div>               
		    </div>
		    <a class="btn btn-block btn-danger visible-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
	    </div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>			
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
	
</body>
EOT;


}
elseif($action=='delmsg')
{
	$dsql->ExecuteNoneQuery("update `sea_member` set msgstate = 'y'  where id= '$uid'");
	showMsg("站内信息删除成功！","-1");exit;
}
elseif($action=='favorite')
{
	$page = $_GET["page"]; 
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_favorite where uid=".$uid);
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}  
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 	
	$dsql->setQuery("select * from sea_favorite where uid=".$uid." ORDER BY kptime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('favlist');
	echo <<<EOT
	
	<body>
	<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">	    	
	    	<div class="hy-member  hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li class="active"><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li><a href="?action=buy" title="消费">消费</a></li>
						<li><a href="?action=Rc" title="互动">互动</a></li>							
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active">
						<table class="table">
							<thead>
		                    <tr>
		                        <th class="text-muted"> 视频</th>
		                        <th class="text-muted">收藏时间</th>
		                        <th class="text-muted hidden-xs">播放数</th>
		                        <th class="text-muted hidden-xs"> 连载集数</th>
		                        <th class="text-muted hidden-xs">状态</th>
		                        <th class="text-muted"> 操作 </th>	
		                    </tr>
		                    </thead>
EOT;
							while($row=$dsql->getArray('favlist'))
							{
								$rs=$dsql->getOne("select v_hit,v_state,v_pic,v_name,v_enname,v_note,v_addtime,tid from sea_data where v_id=".$row['vid']);
								if(!$rs) {continue;}
								$hit=$rs['v_hit'];
								$pic=$rs['v_pic'];
								$name=$rs['v_name'];
								$state=$rs['v_state'];
								$note=$rs['v_note'];
							
							echo <<<EOT
							    <tr>
									<td>
										<a href="
EOT;
								echo getContentLink($rs['tid'],$row['vid'],"",date('Y-n',$rs['v_addtime']),$rs['v_enname']);
								echo <<<EOT
													" target="_blank" >
EOT;
								echo $name;
								echo <<<EOT
													</a>
													<td>					
EOT;
								echo MyDate('',$row['kptime']);
								echo <<<EOT
													</td>
													<td class="hidden-xs">{$hit}</td>
							                        <td class="hidden-xs">{$state}</td>
							                        <td class="hidden-xs">{$note}</td>			
							                        <td>
								<a onClick="return(confirm('确定取消收藏该影片？'))" href="?action=cancelfav&id=
EOT;
								echo $row['id'];
		echo <<<EOT
								">取消</a>				
								</td>
		                    </tr>
EOT;
												  }			
							 echo <<<EOT
	 </table>
	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=favorite&page=1">‹‹</a>
								<a href="?action=favorite&page={$pre_page}">‹</a>												
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=favorite&page={$next_page}">›</a>
								<a href="?action=favorite&page={$page_count}">››</a>						
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='buy')
{
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_buy where uid=".$uid);
	$rcount=$row['dd'];	
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_buy where uid=".$uid." group by vid ORDER BY kptime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('buylist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li class="active"><a href="?action=buy" title="消费">消费</a></li>	
						<li><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
						<li style="float:right;color:#FFF;"><a class="btn btn-primary btn-xs" href="?action=buy3" title="充值记录">充值记录</a></li>
						<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=buy2" title="会员购买">会员购买</a></li>
						<li style="float:right;margin-right:10px;"><a class="btn btn-danger btn-xs"  href="?action=buy" title="视频购买">视频购买</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active">
						<table class="table">
						<thead>
		                 <tr>
                        <th class="text-muted"> 视频</th>
                        <th class="text-muted">  购买时间 </th>
                        <th class="text-muted hidden-xs">播放数</th>
                        <th class="text-muted hidden-xs"> 连载集数</th>
                        <th class="text-muted hidden-xs">状态</th>
                        <th class="text-muted hidden-xs">操作</th>
                    </tr>
                    </thead>
EOT;
	while($row=$dsql->getArray('buylist'))
{
	$rs=$dsql->getOne("select v_hit,v_state,v_pic,v_name,v_enname,v_note,v_addtime,tid from sea_data where v_id=".$row['vid']);
	if(!$rs) {echo "<tr><td align=\"left\"><input type=\"checkbox\"></td><td colspan=\"5\">该视频不存在或已经删除</td></tr>";continue;}
	$hit=$rs['v_hit'];
	$pic=$rs['v_pic'];
	$name=$rs['v_name'];
	$state=$rs['v_state'];
	$note=$rs['v_note'];
	echo <<<EOT
                    <tr>
                        <td>
						<a href="
EOT;
						echo getContentLink($rs['tid'],$row['vid'],"",date('Y-n',$rs['v_addtime']),$rs['v_enname']);
						echo <<<EOT
						" target="_blank">
EOT;
						echo $name;
						echo <<<EOT
						</a>
                        </td>
                        <td>
EOT;
                            echo MyDate('',$row['kptime']);
							echo <<<EOT
                        </td>
                        <td class="hidden-xs">
						{$hit}
                        </td>
                        <td class="hidden-xs">
						{$state}
                        </td>
                        <td class="hidden-xs">
						{$note}
                        </td>
                        <td class="hidden-xs">
						已购买
                        </td>
                    </tr>
EOT;
					}
					echo <<<EOT
                </table>
	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=buy&page=1">‹‹</a>
								<a href="?action=buy&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=buy&page={$next_page}">›</a>
								<a href="?action=buy&page={$page_count}">››</a>							
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='buy2')
{
	$uname=$_SESSION['sea_user_name'];
	$gid=$_SESSION['sea_user_group'];
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_hyzbuy where uname='$uname'");
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_hyzbuy where uname='$uname' ORDER BY paytime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('buylist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li class="active"><a href="?action=buy" title="消费">消费</a></li>	
						<li><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
						<li style="float:right;color:#FFF;"><a class="btn btn-primary btn-xs" href="?action=buy3" title="充值记录">充值记录</a></li>
						<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=buy2" title="会员购买">会员购买</a></li>
						<li style="float:right;margin-right:10px;"><a class="btn btn-danger btn-xs"  href="?action=buy" title="视频购买">视频购买</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active">
						<table class="table">
						<thead>
		                 <tr>

                        <th class="text-muted">购买时间</th>
						<th class="text-muted">购买时长</th>
						<th class="text-muted">消耗{$cfg_pointsname}</th>
						<th class="text-muted">会员组</th>
                    </tr>
                    </thead>
EOT;
	while($row=$dsql->getArray('buylist'))
{
	$rs=$dsql->getOne("select gname from sea_member_group where gid=".$row['gid']);
	$gname=$rs['gname'];
	echo <<<EOT
                    <tr>
                        <td>
EOT;
                            echo MyDate('',$row['paytime']);
							echo <<<EOT
                        </td>
                        <td>
						{$row['mon']}个月
                        </td>
						<td>
						{$row['paypoints']}
                        </td>
                        <td>
						{$gname}
                        </td>


                    </tr>
EOT;
					}
					echo <<<EOT
                </table>
	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=buy2&page=1">‹‹</a>
								<a href="?action=buy2&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=buy2&page={$next_page}">›</a>
								<a href="?action=buy2&page={$page_count}">››</a>						
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='buy3')
{
	$uname=$_SESSION['sea_user_name'];
	$gid=$_SESSION['sea_user_group'];
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_cck where status=1 and uname='$uname'");
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_cck where status=1 and  uname='$uname' ORDER BY usetime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('buylist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li class="active"><a href="?action=buy" title="消费">消费</a></li>	
						<li><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
						<li style="float:right;color:#FFF;"><a class="btn btn-primary btn-xs" href="?action=buy3" title="充值记录">充值记录</a></li>
						<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=buy2" title="会员购买">会员购买</a></li>
						<li style="float:right;margin-right:10px;"><a class="btn btn-danger btn-xs"  href="?action=buy" title="视频购买">视频购买</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active">
						<table class="table">
						<thead>
		                 <tr>

                        <th class="text-muted">充值时间</th>
						<th class="text-muted">{$cfg_pointsname}数量</th>
						<th class="text-muted hidden-xs">充值卡号</th>
                    </tr>
                    </thead>
EOT;
	while($row=$dsql->getArray('buylist'))
{

	echo <<<EOT
                    <tr>
                        <td>
EOT;
                            echo MyDate('',strtotime($row['usetime']));

							echo <<<EOT
                        </td>
                        <td>
						{$row['climit']}
                        </td>
                        <td class="hidden-xs">
						{$row['ckey']}
                        </td>


                    </tr>
EOT;
					}
					echo <<<EOT
                </table>
	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=buy3&page=1">‹‹</a> 
								<a href="?action=buy3&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=buy3&page={$next_page}">›</a>
								<a href="?action=buy3&page={$page_count}">››</a>							
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='gb')
{
	$uname=$_SESSION['sea_user_name'];
	$gid=$_SESSION['sea_user_group'];
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_guestbook where uname='$uname'");
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_guestbook where uname='$uname' ORDER BY dtime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('clist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li><a href="?action=buy" title="消费">消费</a></li>	
						<li class="active"><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
					<li style="float:right;"><a class="btn btn-danger btn-xs"  href="?action=err" title="我的报错">我的报错</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-info btn-xs"  href="?action=Rc" title="回复我的">回复我的</a></li>	
					<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=VNc" title="我的评论">我的评论</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-primary btn-xs" href="?action=gb" title="我的留言">我的留言</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active" style="clear:both;margin-top:50px;">

EOT;
	while($row=$dsql->getArray('clist'))
{
	//$rs=$dsql->getOne("select n_name from sea_data where gid=".$row['gid']);
	//$gname=$rs['gname'];
	
	echo '<div class="panel panel-info"><div class="panel-heading" style="padding-top:7px;height:30px;font-size: 12px;">';
	echo '<span style="float:right;">'.MyDate('',$row['dtime']).' <a title="删除" href=javascript:if(confirm("确定要删除吗?"))location="?action=del_gb&id='.$row['id'].'"><i class="icon iconfont icon-delete" style="color:#688faf;font-size:12px;">删除</i></a></span><span style="float:left;"><a target="_blank" style="color: #31708f;" href="gbook.php">'.$row['title'].'</a></span>';
	echo '</div><div class="panel-body pspan">';
    echo $row['msg'];
	echo '</div></div>';
	}
					echo <<<EOT

	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=gb&page=1">‹‹</a>
								<a href="?action=gb&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=gb&page={$next_page}">›</a>
								<a href="?action=gb&page={$page_count}">››</a>						
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='VNc')
{
	$uname=$_SESSION['sea_user_name'];
	$gid=$_SESSION['sea_user_group'];
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_comment where username='$uname'");
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_comment where username='$uname' ORDER BY dtime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('clist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li><a href="?action=buy" title="消费">消费</a></li>	
						<li class="active"><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
					<li style="float:right;"><a class="btn btn-danger btn-xs"  href="?action=err" title="我的报错">我的报错</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-info btn-xs"  href="?action=Rc" title="回复我的">回复我的</a></li>	
					<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=VNc" title="我的评论">我的评论</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-primary btn-xs" href="?action=gb" title="我的留言">我的留言</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active" style="clear:both;margin-top:50px;">

EOT;
	while($row=$dsql->getArray('clist'))
{
	if($row['m_type']==0){
		$rs=$dsql->getOne("select tid,v_id,v_addtime,v_enname,v_name from sea_data where v_id=".$row['v_id']);
		$clink=getContentLink($rs['tid'],$rs['v_id'],"",date('Y-n',$rs['v_addtime']),$rs['v_enname']);
		$cname=$rs['v_name'];
		$ctype='[视频]';
	}elseif($row['m_type']==1){
		$rs=$dsql->getOne("select tid,n_id,n_title from sea_news where n_id=".$row['v_id']);		
		$clink=getArticleLink($rs['tid'],$rs['n_id'],'');
		$cname=$rs['n_title'];
		$ctype='[新闻]';
	}
	
	echo '<div class="panel panel-info"><div class="panel-heading" style="padding-top:7px;height:30px;font-size: 12px;">';
	echo '<span style="float:right;">'.MyDate('',$row['dtime']).' <a title="删除" href=javascript:if(confirm("确定要删除吗?"))location="?action=del_pl&id='.$row['id'].'&vid='.$row['v_id'].'&itype='.$row['m_type'].'"><i class="icon iconfont icon-delete" style="color:#688faf;font-size:12px;">删除</i></a></span><span style="float:left;"><a target="_blank" style="color: #31708f;" href="'.$clink.'">'.$ctype.cn_substrR($cname,30).'</a></span>';
	echo '</div><div class="panel-body pspan">';
    echo $row['msg'];
	echo '</div></div>';
	}
					echo <<<EOT

	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=VNc&page=1">‹‹</a> 
								<a href="?action=VNc&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=VNc&page={$next_page}">›</a>
								<a href="?action=VNc&page={$page_count}">››</a>						
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='Rc')
{
	$uname=$_SESSION['sea_user_name'];
	$gid=$_SESSION['sea_user_group'];
	$page = $_GET["page"];
	$pcount = 20;
	$dsql->setQuery("SELECT id from sea_comment  WHERE username='$uname'");
	$dsql->Execute('cidlist');
	$row_all_id_arr=array();
	while($row=$dsql->getArray('cidlist')){$row_all_id_arr[]=$row['id'];}
	$row_all_id_str  = implode(',',$row_all_id_arr);
	//echo($row_all_id_str);die; //所有主评论id
	$row=$dsql->getOne("select count(id) as dd from sea_comment where reply in($row_all_id_str)");
	$rcount=$row['dd'];
	//echo($rcount); die;//所有回复评论数量
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_comment  where reply in($row_all_id_str) ORDER BY dtime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('clist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li><a href="?action=buy" title="消费">消费</a></li>	
						<li class="active"><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
					<li style="float:right;"><a class="btn btn-danger btn-xs"  href="?action=err" title="我的报错">我的报错</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-info btn-xs"  href="?action=Rc" title="回复我的">回复我的</a></li>	
					<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=VNc" title="我的评论">我的评论</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-primary btn-xs" href="?action=gb" title="我的留言">我的留言</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active" style="clear:both;margin-top:50px;"><form action="?action=savereply" method="post" class="form-inline">

EOT;
if($cfg_feedback_ck=='1')
{
	$vimg= '<input type="text" name="validate" id="vdcode" placeholder="验证码"  class="form-control" style="text-transform:uppercase;width:50px;display:inline;" /> <img id="vdimgck" src="./include/vdimgck.php" style="height:46px;" />';
}else{$vimg='';}
?>
<script>
function check() {
	<?php 
	if($cfg_feedback_ck=='1'){
	echo 'if(document.getElementById("vdcode").value.length<1){alert("验证码必须填写");return false;}';}
	?>
	if(document.getElementById("msg").value.length<1){alert('评论内容必须填写');return false;}
	var obj=document.getElementById("msg").value;
	if(!(/.*[\u4e00-\u9fa5]+.*$/.test(obj)))
	{alert("评论内容请包含中文！");return false;}
	return true;
}
var showR=function (rid,rvid,rmtype) {
        var index = layer.open({
            type: 1,
			anim: 'up',
			style: 'position:fixed; top:0px; left:0; width: 100%; min-height:100px; padding:10px 0; border:none;',
			content: '<center><br><form onsubmit="return check()" action="?action=savereply" class="form-inline" method="post"><input type="hidden" name="rid" value="'+rid+'" /><input type="hidden" name="rvid" value="'+rvid+'" /><input type="hidden" name="rmtype" value="'+rmtype+'" /><input type="text" class="form-control" style="display:inline;width:auto;"  name="msg" id="msg" placeholder="输入200以内回复内容"  /> <?php echo $vimg; ?> <button type="submit" class="btn btn-primary" >提交</button></form></center>'
        }); 
}
</script>
	<?php
	while($row=$dsql->getArray('clist'))
{
	if($row['m_type']==0){
		$rs=$dsql->getOne("select tid,v_id,v_addtime,v_enname,v_name from sea_data where v_id=".$row['v_id']);
		$clink=getContentLink($rs['tid'],$rs['v_id'],"",MyDate('',$rs['v_addtime']),$rs['v_enname']);
		$cname=$rs['v_name'];
		$ctype='[视频]';
	}elseif($row['m_type']==1){
		$rs=$dsql->getOne("select tid,n_id,n_title from sea_news where n_id=".$row['v_id']);		
		$clink=getArticleLink($rs['tid'],$rs['n_id'],'');
		$cname=$rs['n_title'];
		$ctype='[新闻]';
	}
	$rs_c=$dsql->getOne("select id,msg from sea_comment where id=".$row['reply']);
	if($row['username']==""){$row['username']='匿名用户';}
	echo '<div class="panel panel-info"><div class="panel-heading" style="padding-top:7px;height:30px;font-size: 12px;">';
	echo '<span style="float:right;">'.MyDate('',$row['dtime']).'</span><span style="float:left;"><a target="_blank" style="color: #31708f;" href="'.$clink.'">'.$ctype.cn_substrR($cname,30).'</a></span>';
	echo '</div><div class="panel-body pspan"> ';
    echo $rs_c['msg'];
	if($cfg_gbookstart=='0'){$r_txt='';}else{$r_txt=' <i onClick="showR('.$row['id'].','.$row['v_id'].','.$row['m_type'].')"; class="icon iconfont icon-comment" title="回复这条评论" style="color:#4e86a7;font-size:14px;">回复ta</i>';}
	echo '</div><div class="panel-footer"><font style="color:#428bca;">@'.$row['username']."</font>:".$row['msg'].$r_txt.' </div></div>';
	}
					echo <<<EOT

	                     </form>
						 <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=Rc&page=1">‹‹</a> 
								<a href="?action=Rc&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=Rc&page={$next_page}">›</a>
								<a href="?action=Rc&page={$page_count}">››</a>						
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='savereply')
{
	if($cfg_gbookstart=='0'){exit;}
	if($msg ==''){ShowMsg('请输入回复内容！','member.php?action=Rc',0,3000);exit();}
	if($cfg_gbcheck==1){$ischeck =0;}else{$ischeck =1;}
	if($cfg_feedback_ck=='1')
	{
		$svali = strtolower(trim(GetCkVdValue()));
		if(strtolower($validate) != $svali || $svali=='')
		{
			ResetVdValue();
			if($validate!=$svali)
			{
				ShowMsg('验证码错误！','member.php?action=Rc',0,3000);
				exit();
			}
		}
	}
	$ip = GetIP();
	$dtime = time();
	
	//检查评论间隔时间；
	if(!empty($cfg_comment_times))
	{
		$row = $dsql->GetOne("SELECT dtime FROM `sea_comment` WHERE `ip` = '$ip' ORDER BY `id` desc ");
		if($dtime - $row['dtime'] < $cfg_comment_times)
		{
			ShowMsg('评论太快，请休息一下再来评论！','member.php?action=Rc',0,3000);
			exit();
		}
	}

	$msg = cn_substrR(TrimMsg(unescape($msg)),1000);
	//检查禁止词语
	if(!empty($cfg_banwords))
	{
		$myarr = explode ('|',$cfg_banwords);
		for($i=0;$i<count($myarr);$i++)
		{
			$msgisok = strpos($msg, $myarr[$i]);
			if(is_int($msgisok))
			{
			ShowMsg('您发表的评论中有禁用词语！','member.php?action=Rc',0,3000);
			exit();
			}
		}
		
	}
	//保存评论内容

	$uid =$_SESSION['sea_user_id'];
	$tmpname=$_SESSION['sea_user_name'];
	$tmpname = RemoveXSS(stripslashes($tmpname));
	$tmpname = addslashes(cn_substr($tmpname,20));
	$itype = intval($rmtype);
	$rid = intval($rid);
	$rvid = intval($rvid);
	$uid = intval($uid);
	
	if($msg!='')
	{
		$msg = _Replace_Badword($msg);
		$inquery = "INSERT INTO `sea_comment`(`v_id`,`uid`,`username`,`ip`,`ischeck`,`reply`,`agree`,`anti`,`dtime`,`msg`,`m_type`) VALUES ('$rvid','$uid','$tmpname','$ip',$ischeck,$rid,0,0,'$dtime','$msg','$itype'); ";
		
		$rs = $dsql->ExecuteNoneQuery($inquery);
		if(!$rs)
		{
			echo $dsql->GetError();
			exit();
		}
	}
	delfile("data/cache/review/$itype/$rvid.js");
	ShowMsg('恭喜，成功回复一条评论！','member.php?action=Rc',0,3000);
	exit();
}
elseif($action=='err')
{
	$uname=$_SESSION['sea_user_name'];
	$gid=$_SESSION['sea_user_group'];
	$page = $_GET["page"];
	$pcount = 20;
	$row=$dsql->getOne("select count(id) as dd from sea_erradd where author='$uname'");
	$rcount=$row['dd'];
	$page_count = ceil($rcount/$pcount); 
	if(empty($_GET['page'])||$_GET['page']<0){ 
	$page=1; 
	}else { 
	$page=$_GET['page']; 
	}
	$select_limit = $pcount; 
	$select_from = ($page - 1) * $pcount.','; 
	$pre_page = ($page == 1)? 1 : $page - 1; 
	$next_page= ($page == $page_count)? $page_count : $page + 1 ; 
	$dsql->setQuery("select * from sea_erradd where author='$uname' ORDER BY sendtime DESC limit ".($page-1)*$pcount.",$pcount");
	$dsql->Execute('clist');
	echo <<<EOT
	<body>
		<div class="hy-head-menu">
		<div class="container">
		    <div class="row">
			  	<div class="item">
				    <div class="logo hidden-xs">
						<a class="hidden-sm hidden-xs" href="index.php"><img src="pic/member/logo.png" /></a>
			  			<a class="visible-sm visible-xs" href="index.php"><img src="pic/member/logo_min.png" /></a>											  
					</div>						
					<div class="search hidden-xs"> 
				        <form name="formsearch" id="formsearch" action='search.php' method="post" autocomplete="off">																			
							<input class="form-control" placeholder="输入影片关键词..." name="searchword" type="text" id="keyword" required="">
							<input type="submit" id="searchbutton" value="" class="hide">
							<a href="javascript:" class="btns" title="搜索" onClick="$('#formsearch').submit();"><i class="icon iconfont icon-search"></i></a>
						</form>
				    </div>			   
													 
			  	</div>							
		    </div>
		</div>
	</div>
	<div class="container">
	    <div class="row">
	    	
	    	<div class="hy-member hy-layout clearfix">
	    		<div class="hy-switch-tabs">
					<ul class="nav nav-tabs">
						<a class="text-muted pull-right hidden-xs" href="exit.php"><i class="icon iconfont icon-setting"></i> 退出账户</a>
						<li><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="收藏">收藏</a></li>							
						<li><a href="?action=buy" title="消费">消费</a></li>	
						<li class="active"><a href="?action=Rc" title="互动">互动</a></li>	
					</ul>
					<ul  style="float:right; margin-top:10px;">						
					<li style="float:right;"><a class="btn btn-danger btn-xs"  href="?action=err" title="我的报错">我的报错</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-info btn-xs"  href="?action=Rc" title="回复我的">回复我的</a></li>	
					<li style="float:right;margin-right:10px;"><a class="btn btn-success btn-xs" href="?action=VNc" title="我的评论">我的评论</a></li>
					<li style="float:right;margin-right:10px;"><a class="btn btn-primary btn-xs" href="?action=gb" title="我的留言">我的留言</a></li>
					</ul>
				</div>			
				<div class="tab-content">
					<div class="item tab-pane fade in active" style="clear:both;margin-top:50px;">

EOT;
	while($row=$dsql->getArray('clist'))
{
	$rs=$dsql->getOne("select * from sea_data where v_id=".$row['vid']);	
	echo '<div class="panel panel-info"><div class="panel-heading" style="padding-top:7px;height:30px;font-size: 12px;">';
	echo '<span style="float:right;">'.MyDate('',$row['sendtime']).' <a title="删除" href=javascript:if(confirm("确定要删除吗?"))location="?action=del_err&id='.$row['id'].'"><i class="icon iconfont icon-delete" style="color:#688faf;font-size:12px;">删除</i></a></span><span style="float:left;"><a target="_blank" style="color: #31708f;" href="'.getContentLink($rs['tid'],$rs['v_id'],"",date('Y-n',$rs['v_addtime']),$rs['v_enname']).'">'.cn_substrR($rs['v_name'],30).'</a></span>';
	echo '</div><div class="panel-body pspan">';
    echo $row['errtxt'];
	if($row['ischeck']==1){echo '<br><span>管理员回复： 已处理，感谢您的支持！</span>';}
	echo '</div></div>';
	}
					echo <<<EOT

	                     <div class="sea_page">
							<div class="sea_page_box">
								<a href="?action=err&page=1">‹‹</a> 
								<a href="?action=err&page={$pre_page}">‹</a>													
								<span class="sea_num">$page/$page_count</span>
								<a href="?action=err&page={$next_page}">›</a>
								<a href="?action=err&page={$page_count}">››</a>						
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tabbar visible-xs">
		<a href="/" class="item"><i class=" iconfont icon-fdvideo" style="font-size:20px;"></i> 返回首页</a>
	</div>
	<div class="container">
		<div class="row">
			<div class="hy-footer clearfix">
				
				<p class="text-muted">Copyright ©{$year} {$_SERVER['HTTP_HOST']}</p>
			</div>
		</div>
	</div>	
</body>
EOT;
}
elseif($action=='del_gb'){
	$name=$_SESSION['sea_user_name'];
	$name = RemoveXSS(stripslashes($name));
	$name = addslashes(cn_substr($name,20));
	$id = intval($id);
	$dsql->executeNoneQuery("delete from sea_guestbook where uname='$name' and  id=".$id);
	ShowMsg('恭喜，成功删除一条留言！','member.php?action=gb',0,3000);
}
elseif($action=='del_pl'){
	$name=$_SESSION['sea_user_name'];
	$name = RemoveXSS(stripslashes($name));
	$name = addslashes(cn_substr($name,20));
	$id = intval($id);
	$dsql->executeNoneQuery("delete from sea_comment where username='$name' and  id=".$id);
	delfile("data/cache/review/$itype/$vid.js");
	ShowMsg('恭喜，成功删除一条评论！','member.php?action=VNc',0,3000);
}
elseif($action=='del_err'){
	$name=$_SESSION['sea_user_name'];
	$name = RemoveXSS(stripslashes($name));
	$name = addslashes(cn_substr($name,20));
	$id = intval($id);
	$dsql->executeNoneQuery("delete from sea_erradd where author='$name' and id=".$id);
	ShowMsg('恭喜，成功删除一条报错！','member.php?action=err',0,3000);
}

else
{ }
?> 
