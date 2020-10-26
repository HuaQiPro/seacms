<?php 
error_reporting(0);
require_once('webscan/webscan.php');
define('sea_INC', preg_replace("|[/\\\]{1,}|",'/',dirname(__FILE__) ) );
define('sea_ROOT', preg_replace("|[/\\\]{1,}|",'/',substr(sea_INC,0,-8) ) );
define('sea_DATA', sea_ROOT.'/data');
require_once(sea_INC.'/inc/mysql.php' );
require_once(sea_INC."/filter.inc.php");
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}
$starttime = microtime();
require_once(sea_INC.'/common.func.php');
//检查和注册外部提交的变量
$jpurl='//'.$_SERVER['SERVER_NAME'];
foreach($_REQUEST as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err1');
	}
}

foreach($_GET as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err2');
	}
}

foreach($_POST as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err3');
	}
}

foreach($_COOKIE as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err4');
	}
}

foreach($_FILES as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err5');
	}
}

foreach($_SERVER as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err6');
	}
}

foreach($_SESSION as $_k=>$_v)
{
	if( strlen($_k)>0 && m_eregi('^(cfg_|GLOBALS|_GET|_POST|_COOKIE|_REQUEST|_SERVER|_FILES|_SESSION)',$_k))
	{
		Header("Location:$jpurl");
		exit('err7');
	}
}

function isMobile()
{ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}

$isMobile=isMobile();
if($isMobile){$GLOBALS['isMobile']=1;}else{$GLOBALS['isMobile']=0;}

function _RunMagicQuotes(&$svar)
{
	if(!get_magic_quotes_gpc())
	{
		if( is_array($svar) )
		{
			foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
		}
		else
		{
			$svar = addslashes($svar);
		}
	}
	return $svar;
}


foreach(Array('_GET','_POST','_COOKIE','_SERVER') as $_request)
{
	foreach($$_request as $_k => $_v) ${$_k} = _RunMagicQuotes($_v);
}

 
//系统相关变量检测
if(!isset($needFilter))
{
	$needFilter = false;
}
$registerGlobals = @ini_get("register_globals");
$isUrlOpen = @ini_get("allow_url_fopen");
$isSafeMode = @ini_get("safe_mode");
if( m_eregi('windows', @getenv('OS')) )
{
	$isSafeMode = false;
}

//Session保存路径
/*$sessSavePath = sea_DATA."/sessions/";
if(is_writeable($sessSavePath) && is_readable($sessSavePath))
{
	session_save_path($sessSavePath);
}
*/
$timestamp = time();

//数据库配置文件
require_once(sea_DATA.'/common.inc.php');

//系统配置参数
//require_once(sea_DATA."/config.cache.inc.php");

//php5.1版本以上时区设置
//由于这个函数对于是php5.1以下版本并无意义，因此实际上的时间调用，应该用MyDate函数调用
if(PHP_VERSION > '5.1')
{
	$time51 = $cfg_cli_time * -1;
	@date_default_timezone_set('Etc/GMT'.$time51);
}
$cfg_isUrlOpen = @ini_get("allow_url_fopen");

//站点根目录
//$cfg_basedir = m_eregi_replace($cfg_cmspath.'include$','',sea_INC);
$cfg_basedir = m_eregi_replace('include$','',sea_INC);

//模板的存放目录
$cfg_templets_dir = 'templets';

$cfg_phpurl = '/'.$cfg_cmspath;

//附件目录
$cfg_medias_dir = '/'.$cfg_cmspath.$cfg_upload_dir;

//上传的普通图片的路径,建议按默认
$cfg_image_dir = $cfg_medias_dir.'/allimg';

//上传的缩略图
$ddcfg_image_dir = $cfg_medias_dir.'/litimg';

//系统摘要信息，****请不要删除本项**** 否则系统无法正确接收系统漏洞或升级信息
$verLockFile = sea_ROOT.'/data/admin/ver.txt';
$fp = fopen($verLockFile,'r');
$verLocal = trim(fread($fp,64));
fclose($fp);
$verLocal = substr($verLocal,8,strlen($verLocal)-8);
$cfg_version =$verLocal;
$cfg_soft_lang = 'utf-8';
$cfg_softname = '海洋影视内容管理系统';
$cfg_soft_enname = 'sea';
$cfg_soft_devteam = '海洋官方团队';

//新建目录的权限，如果你使用别的属性，本程不保证程序能顺利在Linux或Unix系统运行
$cfg_dir_purview = 0755;

/*if($cfg_sendmail_bysmtp=='Y' && !empty($cfg_smtp_usermail))
{
	$cfg_adminemail = $cfg_smtp_usermail;
}*/

if(!isset($cfg_NotPrintHead)) {
	header("Content-Type: text/html; charset={$cfg_soft_lang}");
}

if($_FILES)
{
	require_once(sea_INC.'/uploadsafe.inc.php');
}

//引入数据库类
require_once(sea_INC.'/sql.class.php');
require_once(sea_INC.'/updatesql.class.php');

require_once(sea_INC.'/image.class.php');
require_once(sea_INC.'/collection.class.php');
//全局常用函数
require_once(sea_INC.'/link.func.php');
require_once(sea_INC.'/mkhtml.func.php');

//引入语言包
require_once(sea_INC.'/lang.php');
//计划任务
include sea_DATA.'/cron.cache.php';
include sea_INC.'/cron.func.php';
if($cronnextrun && $cronnextrun <= $timestamp) {
	if($cron = $dsql->GetOne("SELECT * FROM sea_crons WHERE available>'0' AND nextrun<=$timestamp ORDER BY nextrun")) {
		$lockfile = sea_DATA.'/runcron_'.$cron['cronid'].'.lock';
		$cron['filename'] = str_replace(array('..', '/', '\\'), '', $cron['filename']);
		$filename=$cron['filename'];
		if(strpos($filename,'$')!==false){$filenameArr=explode("$",$filename);$filename=$filenameArr[0];}
		if(strpos($filename,'#')!==false){$filenameArr=explode("#",$filename);$filename=$filenameArr[0];}
		$cronfile = sea_INC.'/crons/'.$filename;
		if(is_writable($lockfile) && filemtime($lockfile) > $timestamp - 600) {
		} else {
			@touch($lockfile);
		}
		@set_time_limit(1000);
		@ignore_user_abort(TRUE);
		cronnextrun($cron);
		if(!include_once $cronfile) {
		}
		@unlink($lockfile);
	}
	$nextrun = $dsql->GetOne("SELECT nextrun FROM sea_crons WHERE available>'0' ORDER BY nextrun");
	if(!$nextrun === FALSE) {
		updatecronscache();
	}
}
?>