<?php 
session_start();
require_once("include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC.'/main.class.php');
if($cfg_user==0) 
{
	ShowMsg('系统已关闭会员功能!','index.php');
	exit();
}

$svali = $_SESSION['sea_ckstr'];
$action = isset($action) ? trim($action) : '';
if($action=='reg')
{
	
$checkmail="/([a-z0-9\-_\.]+@[a-z0-9]+\.[a-z0-9\-_\.]+)+/i";
if(!preg_match($checkmail,$email)||!$email)
{
ShowMsg('请正确填写邮箱!','-1');        
exit();        
}
 
$dtime = time();

if($cfg_feedback_ck=='1')
{
$validate = empty($validate) ? '' : strtolower(trim($validate));
if($validate=='' || $validate != $svali)
	{
		ResetVdValue();
		ShowMsg('验证码不正确!','-1');
		exit();
	}
}

if(trim($m_pwd)<>trim($m_pwd2) || trim($m_pwd)=='')
	{
		ShowMsg('两次输入密码不一致或密码为空','-1');	
		exit();	
	}	
	
$username = $m_user;
$username = RemoveXSS(stripslashes(trim($username)));
$username = addslashes(cn_substr($username,200));
$email = RemoveXSS(stripslashes($email));
$email = addslashes(cn_substr($email,200));

$row1=$dsql->GetOne("select username  from sea_member where username='$username'");
if($row1['username']==$username)
{
		ShowMsg('用户已存在','-1');	
		exit();	
}
$row2=$dsql->GetOne("select email  from sea_member where email='$email'");
if($row2['email']==$email)
{
		ShowMsg('邮箱已存在','-1');	
		exit();	
}


	$pwd = substr(md5($m_pwd),5,20);
	$ip = GetIP();
	$randtime=uniqid();
	$acode=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser.$randtime); //构造唯一码	
	
	
	$regpoints=intval($cfg_regpoints);
	if($regpoints=="" OR empty($regpoints)){$regpoints=0;} 
	if($username) {
		$dsql->ExecuteNoneQuery("INSERT INTO `sea_member`(id,username,password,email,regtime,regip,state,gid,points,logincount,stime,vipendtime,acode,repswcode,msgstate,pic)
                  VALUES ('','$username','$pwd','$email','$dtime','$ip','1','2','$regpoints','1','1533686888','$dtime','$acode','y','y','uploads/user/a.png')");

		require_once('data/admin/smtp.php');
		if($smtpreg=='on')
		{
				$smtprmail= $email;

				$smtprtitle = '【'.$cfg_webname.'】Email 地址验证，账号激活邮件';
$smtprbody = '<strong>Email 地址验证 账户激活</strong><br><br>尊敬的：'.$username.'<br>这封信是由 '.$cfg_webname.' 发送的。<br>您收到这封邮件，是由于在 '.$cfg_webname.' 进行了新用户注册。如果您并没有访问过 '.$cfg_webname.'，或没有进行上述操作，请忽略这封邮件。您不需要退订或进行其他进一步的操作。<br><br>如果您是 '.$cfg_webname. '的新用户，我们需要对您的地址有效性进行验证以避免垃圾邮件或地址被滥用。<br>您只需点击下面的链接即可激活您的帐号：<br><a target="_blank" href="'.$cfg_basehost.'/member.php?mod=activate&acode='.$acode.'">'.$cfg_basehost.'/member.php?mod=activate&acode='.$acode.'</a><br>(如果上面不是链接形式，请将该地址手工粘贴到浏览器地址栏再访问)<br><br>感谢您的访问，祝您使用愉快！<br><br>此致<br>'.$cfg_webname.'管理团队.<br>'.$cfg_basehost.'<br>';
				require_once("include/class.phpmailer.php"); 
				require_once("data/admin/smtp.php"); 
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
				 ShowMsg('注册成功,需要激活!<br>一封激活邮件已发送到您刚刚填写的邮箱，请查收！','login.php',0,100000);
				}else{
				 ShowMsg('抱歉！激活邮件发送失败，请联系客服解决此错误。','login.php',0,100000);
				}									
			
			}
		else
		{
			$row=$dsql->GetOne("select id from sea_member where username='$username'");
			$uid=$row['id'];
			$_SESSION['sea_user_id'] = $uid;
			$_SESSION['sea_user_name'] = $username;
			$lifeTime = 2592000; 
			setcookie(session_name(), session_id(), time() + $lifeTime, "/");
			$_SESSION['sea_user_group'] = 2;
			$front='front';
			$hashstr=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser.$front);//构造session安全码
			$_SESSION['hashstr']=$hashstr;
			$dsql->ExecuteNoneQuery("UPDATE `sea_member` set logincount=logincount+1 where id='$uid'");
			ShowMsg("注册成功，正在转向会员中心！","member.php",0,3000);exit;
			
			}
		exit;
	}
}
else
{
	$tempfile = sea_ROOT."/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/reg.html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$tempfile = sea_ROOT."/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/reg.html";}
	$content=loadFile($tempfile);
	$t=$content;
	$t=$mainClassObj->parseTopAndFoot($t);
	$t=$mainClassObj->parseHistory($t);
	$t=$mainClassObj->parseSelf($t);
	$t=$mainClassObj->parseGlobal($t);
	$t=$mainClassObj->parseAreaList($t);
	$t=$mainClassObj->parseNewsAreaList($t);
	$t=$mainClassObj->parseMenuList($t,"");
	$t=$mainClassObj->parseVideoList($t,-444,'','');
	$t=$mainClassObj->parseNewsList($t,-444,'','');
	$t=$mainClassObj->parseTopicList($t);
	$t=replaceCurrentTypeId($t,-444);
	$t=$mainClassObj->parseIf($t);
	if($cfg_feedback_ck=='1')
	{$t=str_replace("{register:viewRegister}",viewRegister(),$t);}
	else
	{$t=str_replace("{register:viewRegister}",viewRegister2(),$t);}
	
	$t=str_replace("{register:main}",viewMain(),$t);

	$t=str_replace("{seacms:runinfo}",getRunTime($t1),$t);
	$t=str_replace("{seacms:member}",front_member(),$t);
	echo $t;

} 

function viewMain(){
	$main="<div class='leaveNavInfo'><h3><span id='adminleaveword'></span>".$GLOBALS['cfg_webname']."会员注册</h3></div>";
	return $main;
}



function viewActivation($activeuser,$activepwd){
	$mystr="<div id=\"register\">".
"<form id=\"f_register\" action=\"/".$GLOBALS['cfg_cmspath']."reg.php?action=activationsubmit\" method=\"post\">".
"<input type=\"hidden\" name=\"activeuser\" value=\"$activeuser\">".
"<input type=\"hidden\" name=\"activepwd\" value=\"$activepwd\">".
"<table align=\"center\" style=\"margin:0 auto\">". 
"<tr>".
"<td>用户名$activeuser</td> </tr>".
"<tr>".
"<td><input type=\"submit\" value=\"激活\" class=\"btn\"/></td> </tr>".
"</table></form>".
"</div>";
	return $mystr;
}

function viewRegister()
{
	$mystr=
"<ul>".
"<form id=\"f_Activation\"   action=\"/".$GLOBALS['cfg_cmspath']."reg.php?action=reg\" method=\"post\">".
"<li><input type=\"input\" name=\"m_user\" id=\"m_user\" autofocus class=\"form-control\" placeholder=\"用户名\" /></li>".
"<li><input type=\"password\" name=\"m_pwd\" class=\"form-control\" placeholder=\"密码\" /></li>".
"<li><input type=\"password\" name=\"m_pwd2\" class=\"form-control\" placeholder=\"确认密码\" /></li>".
"<li><input type=\"text\" name=\"email\" class=\"form-control\" placeholder=\"邮箱地址\" /></li>".
"<li><img id=\"vdimgck\" src=\"./include/vdimgck.php\" alt=\"看不清？点击更换\" align=\"absmiddle\" class=\"pull-right\" style='width:70px; height:32px;' onClick=\"this.src=this.src+'?'\"/><input type=\"text\" name=\"validate\" id=\"vdcode\" placeholder=\"验证码\" style='width:50%;text-transform:uppercase;' class=\"form-control\" /> </li>".
"<li><input type=\"submit\" value=\"注册\" class=\"btn btn-block btn-warning\"/></li>".
"<li class=\"text-center\"><a class=\"text-muted\" href=\"./login.php\">已有账号，直接登录？</a></li>".
"</form>".
"</ul>";
	return $mystr;
}

function viewRegister2()
{
	$mystr=
"<ul>".
"<form id=\"f_Activation\"   action=\"/".$GLOBALS['cfg_cmspath']."reg.php?action=reg\" method=\"post\">".
"<li><input type=\"input\" name=\"m_user\" id=\"m_user\" autofocus class=\"form-control\" placeholder=\"用户名\" /></li>".
"<li><input type=\"password\" name=\"m_pwd\" class=\"form-control\" placeholder=\"密码\" /></li>".
"<li><input type=\"password\" name=\"m_pwd2\" class=\"form-control\" placeholder=\"确认密码\" /></li>".
"<li><input type=\"text\" name=\"email\" class=\"form-control\" placeholder=\"邮箱地址\" /></li>".
"<li><input type=\"submit\" value=\"注册\" class=\"btn btn-block btn-warning\"/></li>".
"<li class=\"text-center\"><a class=\"text-muted\" href=\"./login.php\">已有账号，直接登录？</a></li>".
"</form>".
"</ul>";
	return $mystr;
}
