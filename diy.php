<?php 
/*******************************************************

名称：万能调用模块

功能：动态调用任意内容，如最新更新，热门排行等等。替代传
	  统的自定义页面，实现免生成即可实时更新页面内容。
	  
用法：重命名此文件为你想要的名字，如 hot.php new.php等等
	  然后在模板目录添加相对应的模板文件，如hot.html new
	  .html等，模板文件名保持和此PHP文件名一致即可。如需
	  要多个文件，直接复制本文件改名即可。
	  
备注：此模块支持缓存和多模板，会在缓存周期内更新。此模块
	  和自定义页面模块互不干扰，为独立运行。用户可以选择
	  单独使用，也可以同时使用。

********************************************************/


require_once ("include/common.php");
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
function php_self(){
    $php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
	$php_self=str_replace('.php','',$php_self);
    return $php_self;
}
$phpself=php_self();
require_once sea_INC."/main.class.php";
echoIndex();
function echoIndex()
{
	global $cfg_iscache,$t1,$phpself;
	$cacheName="parsed_".$phpself.$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	$templatePath="/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/".$phpself.".html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$templatePath="/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/".$phpself.".html";}
	if($cfg_iscache){
		if(chkFileCache($cacheName)){
			$indexStr = getFileCache($cacheName);
		}else{
			$indexStr = parseIndexPart($templatePath);
			setFileCache($cacheName,$indexStr);
		}
	}else{
			$indexStr = parseIndexPart($templatePath);
	}
	$indexStr=str_replace("{seacms:member}",front_member(),$indexStr);
	echo str_replace("{seacms:runinfo}",getRunTime($t1),$indexStr) ;
}

function parseIndexPart($templatePath)
{
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=replaceCurrentTypeId($content,-444);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseNewsAreaList($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseNewsList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseTopicList($content);
	$content=$mainClassObj->parseLinkList($content);
	$content=$mainClassObj->parseIf($content);
	return $content;
}
