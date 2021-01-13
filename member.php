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
	$nickname = RemoveXSS(stripslashes($nickname));
	$nickname = addslashes(cn_substr($nickname,60));
	if(!empty($newpwd)||!empty($email)||!empty($nickname))
	{
	if(empty($newpwd)){$pwd = $oldpwd;} else{$pwd = substr(md5($newpwd),5,20);};
	$dsql->ExecuteNoneQuery("update `sea_member` set password = '$pwd',email = '$email',nickname = '$nickname' where id= '$uid'");
	ShowMsg('资料修改成功','-1');	
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
    				<div class="integral">{$cfg_pointsname}：{$ccjifen}</div>
    				<dl class="margin-0 clearfix">
    					<dt><span class="user"></span></dt>
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
						<li class="active"><a href="?action=cc" title="基本资料">基本资料</a></li>							
						<li><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li><a href="?action=buy" title="购买记录">购买记录</a></li>
						<li><a href="/" title="首页">首页</a></li>						
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
												<h3 class="text-muted">修改资料</h3>
EOT;
						$row1=$dsql->GetOne("select * from sea_member where id='$uid'");
							$oldpwd=$row1['password'];
							$oldemail=$row1['email'];
							
							$oldnickname=$row1['nickname'];
							echo "<form id=\"f_Activation\"   action=\"?action=chgpwdsubmit\" method=\"post\">".
								"<li><input type=\"password\" name=\"oldpwd\" value=\"$oldpwd\" class=\"form-control\" placeholder=\"输入旧密码\" /></li>".    
								"<li><input type=\"password\" name=\"newpwd\"  class=\"form-control\" placeholder=\"输入新密码\" /></li>".  
								"<li><input type=\"password\" name=\"newpwd2\" class=\"form-control\" placeholder=\"再次确认\" /></li>".  
								"<li><input type=\"test\" name=\"email\" value=\"$oldemail\" class=\"form-control\" placeholder=\"邮箱地址\" /></li>".
								"<li><input type=\"test\" name=\"nickname\" value=\"$oldnickname\" class=\"form-control\" placeholder=\"联系方式\" /></li>".
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
						<li class="active"><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li><a href="?action=buy" title="购买记录">购买记录</a></li>
						<li><a href="/" title="首页">首页</a></li>							
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
								echo date('Y-m-d',$row['kptime']);
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
								">取消收藏</a>				
								</td>
		                    </tr>
EOT;
												  }			
							 echo <<<EOT
	 </table>
	                     <div class="hy-page clearfix">
							<ul class="cleafix">
								<li><a href="?action=favorite&page=1">首页</a> </li>
								<li><a href="?action=favorite&page={$pre_page}">上一页</a></li>														
								<li><span class="num">$page/$page_count</span></li>
								<li><a href="?action=favorite&page={$next_page}">下一页</a></li>
								<li><a href="?action=favorite&page={$page_count}">尾页</a></li>							
							</ul>					
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
						<li><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li class="active"><a href="?action=buy" title="购买记录">购买记录</a></li>	
						<li><a href="/" title="首页">首页</a></li>	
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
                            echo date('Y-m-d',$row['kptime']);
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
	                     <div class="hy-page clearfix">
							<ul class="cleafix">
								<li><a href="?action=buy&page=1">首页</a> </li>
								<li><a href="?action=buy&page={$pre_page}">上一页</a></li>														
								<li><span class="num">$page/$page_count</span></li>
								<li><a href="?action=buy&page={$next_page}">下一页</a></li>
								<li><a href="?action=buy&page={$page_count}">尾页</a></li>							
							</ul>					
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
						<li><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li class="active"><a href="?action=buy" title="购买记录">购买记录</a></li>	
						<li><a href="/" title="首页">首页</a></li>	
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
                            echo date('Y-m-d',$row['paytime']);
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
	                     <div class="hy-page clearfix">
							<ul class="cleafix">
								<li><a href="?action=buy2&page=1">首页</a> </li>
								<li><a href="?action=buy2&page={$pre_page}">上一页</a></li>														
								<li><span class="num">$page/$page_count</span></li>
								<li><a href="?action=buy2&page={$next_page}">下一页</a></li>
								<li><a href="?action=buy2&page={$page_count}">尾页</a></li>							
							</ul>					
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
						<li><a href="?action=favorite"title="我的收藏">我的收藏</a></li>							
						<li class="active"><a href="?action=buy" title="购买记录">购买记录</a></li>	
						<li><a href="/" title="首页">首页</a></li>	
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
                            echo date('Y-m-d',strtotime($row['usetime']));

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
	                     <div class="hy-page clearfix">
							<ul class="cleafix">
								<li><a href="?action=buy3&page=1">首页</a> </li>
								<li><a href="?action=buy3&page={$pre_page}">上一页</a></li>														
								<li><span class="num">$page/$page_count</span></li>
								<li><a href="?action=buy3&page={$next_page}">下一页</a></li>
								<li><a href="?action=buy3&page={$page_count}">尾页</a></li>							
							</ul>					
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
else
{ }
?> 
