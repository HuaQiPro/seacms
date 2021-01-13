<?php 
require_once(dirname(__FILE__)."/../include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");

echoIndex();

function echoIndex()
{
	global $cfg_iscache,$t1;;
	$cacheName="parsed_news".$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	$templatePath="/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/newsindex.html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$templatePath="/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/newsindex.html";}
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
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseNewsAreaList($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseNewsList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseLinkList($content);
	$content=$mainClassObj->parseIf($content);
	return $content;
}
?>