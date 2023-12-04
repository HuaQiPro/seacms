<?php 
if(!file_exists("data/common.inc.php"))
{
    header("Location:install/index.php");
    exit();
}
require_once ("include/common.php");
if($cfg_runmode==0){ header("Location:index".$cfg_filesuffix2);exit;}

//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end

require_once sea_INC."/main.class.php";
echoIndex();
function echoIndex()
{
	global $cfg_iscache,$t1;
	$cacheName="parsed_index".$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	$templatePath="/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/index.html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$templatePath="/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/index.html";}
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
