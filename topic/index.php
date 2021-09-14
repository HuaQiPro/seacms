<?php 
require_once(dirname(__FILE__)."/../include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");

 
$paras=str_replace(getfileSuffix(),'',$_SERVER['QUERY_STRING']);
$page=$paras;
$page = (isset($page) && is_numeric($page) ? $page : 0);
$page=intval($page);
if($page<1)$page=1;
echoTopicIndex();
function echoTopicIndex()
{
	global $mainClassObj,$cfg_iscache,$t1,$cfg_filesuffix2,$dsql,$page;
		$cacheName="parse_topic_index".$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
		$templatePath="/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/topicindex.html";
		if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
		{$templatePath="/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/topicindex.html";}
		$content = parseTopicIndexPart($templatePath,$page);

	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{seacms:member}",front_member(),$content);
	echo str_replace("{seacms:runinfo}",getRunTime($t1),$content) ;
}

function parseTopicIndexPart($templatePath,$page)
{
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=replaceCurrentTypeId($content,-444);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"");
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseVideoList($content,'','','');
	$content=$mainClassObj->parseNewsList($content,'','','');
	$content=$mainClassObj->parseTopicList($content);
	$content=$mainClassObj->parseTopicIndexList($content,$page);
	$content=$mainClassObj->parseLinkList($content);
	$content=$mainClassObj->parseIf($content);
	return $content;
}
?>