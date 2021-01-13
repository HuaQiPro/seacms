<?php 
session_start();
require_once("include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");
if($cfg_user==0) 
{
	ShowMsg('系统已关闭会员功能!','index.php');
	exit();
}
$front='front';
$hashstr=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser.$front); //构造session安全码 
$svali = $_SESSION['sea_ckstr'];
if($dopost=='login')
{
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
	if($userid=='')
	{
		ShowMsg('请输入用户名!','-1');
		exit();
	}
	if($pwd=='')
	{
		ShowMsg('请输入密码!','-1');
		exit();
	}

$userid = RemoveXSS(stripslashes($userid));
$userid = addslashes(cn_substr($userid,60));


$pwd = substr(md5($pwd),5,20);
$row1=$dsql->GetOne("select * from sea_member where state=1 and username='$userid'");
if($row1['username']==$userid AND $row1['password']==$pwd)
		{
					//验证是否激活邮箱
					require_once('data/admin/smtp.php');
					if($smtpreg=='on'){
						$sql="SELECT acode FROM sea_member where username= '$userid'"; 
						$row = $dsql->GetOne($sql);
						if($row['acode']!='y'){
							showMsg("您的账户尚未激活，请激活后登陆！","index.php",0,10000);
							exit;
						}
					}
					$_SESSION['sea_user_id'] = $row1['id'];
					$uid=$row1['id'];
					$_SESSION['sea_user_name'] = $row1['username'];
					$lifeTime = 2592000; 
					setcookie(session_name(), session_id(), time() + $lifeTime, "/");
					if($row1['vipendtime']<time()){
						$_SESSION['sea_user_group'] = 2;
						$dsql->ExecuteNoneQuery("update `sea_member` set gid=2 where id=$uid");
						$_SESSION['hashstr']=$hashstr;
						$dsql->ExecuteNoneQuery("UPDATE `sea_member` set logincount=logincount+1 where id='$uid'");
						if($row1['gid'] !=2){
							ShowMsg("您购买的会员已到期，请注意续费!","member.php",0,5000);
						}else{
							ShowMsg("成功登录，正在转向会员中心！","member.php",0,3000);
						}
					}else{
						$_SESSION['sea_user_group'] = $row1['gid'];
						$_SESSION['hashstr']=$hashstr;
						$dsql->ExecuteNoneQuery("UPDATE `sea_member` set logincount=logincount+1 where id='$uid'");
						ShowMsg("成功登录，正在转向会员中心！","member.php",0,3000);
					}
					
					
					exit();

		}
		else
		{
			ShowMsg("密码错误或账户已被禁用","login.php",0,3000);
			exit();
		}
}
else
{
	$tempfile = sea_ROOT."/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/login.html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$tempfile = sea_ROOT."/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/login.html";}
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
	{$t=str_replace("{login:viewLogin}",viewLogin(),$t);}
	else
	{$t=str_replace("{login:viewLogin}",viewLogin2(),$t);}
	$t=str_replace("{login:main}",viewMain(),$t);
	$t=str_replace("{seacms:runinfo}",getRunTime($t1),$t);
	$t=str_replace("{seacms:member}",front_member(),$t);
	echo $t;
	exit();
}

function viewMain(){
	$main="<div class='leaveNavInfo'><h3><span id='adminleaveword'></span>".$GLOBALS['cfg_webname']."会员登录</h3></div>";
	return $main;
}

function viewLogin(){
	$mystr=
"<ul>".
"<form id=\"f_login\"   action=\"/".$GLOBALS['cfg_cmspath']."login.php\" method=\"post\">".
"<input type=\"hidden\" value=\"login\" name=\"dopost\" />".
"<li><input type=\"input\" name=\"userid\" autofocus class=\"form-control\" placeholder=\"用户名\" /></li>".
"<li><input type=\"password\" name=\"pwd\" class=\"form-control\" placeholder=\"密码\" /></li>".
"<li><img id=\"vdimgck\" src=\"./include/vdimgck.php\" alt=\"看不清？点击更换\" align=\"absmiddle\" class=\"pull-right\" style='width:70px; height:32px;' onClick=\"this.src=this.src+'?'\"/><input name=\"validate\" type=\"text\" placeholder=\"验证码\" style='width:50%;text-transform:uppercase;' class=\"form-control\" /> </li>".
"<li><input type=\"submit\" value=\"登录\" class=\"btn btn-block btn-warning\"/></li>".
"<li class=\"text-center\"><a class=\"text-muted\" href=\"./reg.php\">注册用户</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=\"text-muted\" href=\"./member.php?mod=repsw\">找回密码</a></li>".
"</ul>";
	return $mystr;
}

function viewLogin2(){
	$mystr=
	"<ul>".
"<form id=\"f_login\"   action=\"/".$GLOBALS['cfg_cmspath']."login.php\" method=\"post\">".
"<input type=\"hidden\" value=\"login\" name=\"dopost\" />".
"<li><input type=\"input\" name=\"userid\" autofocus class=\"form-control\" placeholder=\"用户名\" /></li>".
"<li><input type=\"password\" name=\"pwd\" class=\"form-control\" placeholder=\"密码\" /></li>".
"<li><input type=\"submit\" value=\"登录\" class=\"btn btn-block btn-warning\"/></li>".
"<li class=\"text-center\"><a class=\"text-muted\" href=\"./reg.php\">注册用户</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class=\"text-muted\" href=\"./member.php?mod=repsw\">找回密码</a></li>".
"</form>".
"</ul>";
	return $mystr;
}