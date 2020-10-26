<?php 
//彻底禁止蜘蛛抓取
if(preg_match("/(googlebot|baiduspider|sogou|360spider|bingbot|Yahoo|spider|bot)/i", $_SERVER['HTTP_USER_AGENT']))
{header('HTTP/1.1 403 Forbidden'); header("status: 403 Forbidden");}

require_once(dirname(__FILE__).'/../include/common.php');
require_once(sea_INC."/check.admin.php");
if(empty($dopost))
{
	$dopost = '';
}
$hashstr=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser); //构造session安全码
//检测安装目录安全性
if( is_dir(dirname(__FILE__).'/../install') )
{
	if(!file_exists(dirname(__FILE__).'/../install/install_lock.txt') )
	{
  	$fp = fopen(dirname(__FILE__).'/../install/install_lock.txt', 'w') or die('安装目录无写入权限，无法进行写入锁定文件，请安装完毕删除安装目录！');
  	fwrite($fp,'ok');
  	fclose($fp);
	}
	//为了防止未知安全性问题，强制禁用安装程序的文件
	if( file_exists("../install/index.php") ) {
		@rename("../install/index.php", "../install/index.phpbak");
	}
}


//ip检测
function GetUIP()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else if(!empty($_SERVER["REMOTE_ADDR"]))
	{
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else
	{
		$cip = '';
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = isset($cips[0]) ? $cips[0] : 'unknown';
	unset($cips);
	return $cip;
}
$uip = GetUIP();
require_once("../data/admin/ip.php");
if($v=="1" AND !strstr($ip,$uip)){die('IP address is forbidden access');}

//登录检测
$admindirs = explode('/',str_replace("\\",'/',dirname(__FILE__)));
$admindir = $admindirs[count($admindirs)-1];
$v=file_get_contents("../data/admin/adminvcode.txt");

if($dopost=='login' AND $v==1)
{
		$validate = empty($validate) ? '' : strtolower(trim($validate));
		$svali = strtolower(GetCkVdValue());
		
			if($validate=='' || $validate != $svali)
			{
				ResetVdValue();
				
				ShowMsg('验证码不正确!','-1');
				exit();
				
			}

	else
	{
		$cuserLogin = new userLogin($admindir);
		if(!empty($userid) && !empty($pwd))
		{
			$res = $cuserLogin->checkUser($userid,$pwd);

			//success
			if($res==1)
			{
				$cuserLogin->keepUser();
				$_SESSION['hashstr']=$hashstr;
				if(!empty($gotopage))
				{
					ShowMsg('成功登录，正在转向管理管理主页！',$gotopage);
					exit();
				}
				else
				{
					ShowMsg('成功登录，正在转向管理管理主页！',"index.php");
					exit();
				}
			}

			//error
			else if($res==-1)
			{
				ShowMsg('你的用户名不存在!','-1');
				exit();
			}
			else
			{
				ShowMsg('你的密码错误!','-1');
				exit();
			}
		}

		//password empty
		else
		{
			ShowMsg('用户和密码没填写完整!','-1');
				exit();
		}
	}
}

if($dopost=='login' AND $v==0)
{
		$cuserLogin = new userLogin($admindir);
		if(!empty($userid) && !empty($pwd))
		{
			$res = $cuserLogin->checkUser($userid,$pwd);

			//success
			if($res==1)
			{
				$cuserLogin->keepUser();
				$_SESSION['hashstr']=$hashstr;
				if(!empty($gotopage))
				{
					ShowMsg('成功登录，正在转向管理管理主页！',$gotopage);
					exit();
				}
				else
				{
					ShowMsg('成功登录，正在转向管理管理主页！',"index.php");
					exit();
				}
			}

			//error
			else if($res==-1)
			{
				ShowMsg('你的用户名不存在!','-1');
				exit();
			}
			else
			{
				ShowMsg('你的密码错误!','-1');
				exit();
			}
		}

		//password empty
		else
		{
			ShowMsg('用户和密码没填写完整!','-1');
				exit();
		}

}
$cdir = $_SERVER['PHP_SELF']; 
include('templets/login.htm');

?>