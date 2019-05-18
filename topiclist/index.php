<?php
require_once(dirname(__FILE__)."/../include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");
 
$paras=str_replace(getfileSuffix(),'',$_SERVER['QUERY_STRING']);
if(strpos($paras,"-")>0){
	$parasArray=explode("-",$paras);
	$id=intval($parasArray[0]);
	$page=intval($parasArray[1]);
}else{
	$id=intval($paras);
	$page=1;
}
$id = isset($id) && is_numeric($id) ? $id : 0;
$page = isset($page) && is_numeric($page) ? $page : 1;
$id=intval($id);
$page=intval($page);
if($id==0){
	showmsg('参数丢失，请返回！', -1);
	exit;
}
echoTopic();

function echoTopic()
{
	global $dsql,$cfg_iscache,$mainClassObj,$page,$t1,$id;
	$sql="select id,name,template,enname from sea_topic where id =".$id;
	$row = $dsql->GetOne($sql);
	if(!is_array($row)) exit("不存在此专题");
	$cacheName="parse_topic_".$id.$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	$topicTemplatePath="/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/".$row['template'];
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$topicTemplatePath="/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/".$row['template'];}
	$currentTopicId=$row['id'];
						//echo $topicId; 专题id
						$sql="select * from sea_topic where id='$id'";
						$rows=array();
						$dsql->SetQuery($sql);
						$dsql->Execute('al');
						while($rowr=$dsql->GetObject('al'))
						{
						$rows[]=$rowr;
						}
						unset($rowr);
						$aa=explode("ttttt",$rows[0]->vod);
						$topicDes=$rows[0]->des;
						$topicKeyword=$rows[0]->keyword;
						$topicName=$rows[0]->name;
						$topicPic=$rows[0]->pic;
						$topicPic=$GLOBALS['cfg_cmspath']."uploads/zt/".$topicPic;
	$page_size = getPageSizeOnCache($topicTemplatePath,"topicpage",$row['template']);
	if (empty($page_size)) $page_size=12;
	if(is_array($aa))
	{
		$TotalResult = count($aa)-1;
	}
	else
	{
		$TotalResult = 0;
	}
	$pCount = ceil($TotalResult/$page_size);
	if($cfg_iscache){
		if(chkFileCache($cacheName)){
			$content = getFileCache($cacheName);
		}else{
			$content = parseTopicPart($topicName,$topicTemplatePath,$id,$topicDes,$topicKeyword,$topicPic);
			$content = str_replace("{seacms:currrent_topic_id}",$currentTopicId,$content);
			setFileCache($cacheName,$content);
		}
	}else{
			$content = parseTopicPart($topicName,$topicTemplatePath,$id,$topicDes,$topicKeyword,$topicPic);
			$content = str_replace("{seacms:currrent_topic_id}",$currentTopicId,$content);
	}
	$content=$mainClassObj->ParsePageList($content,$id,$page,$pCount,$TotalResult,"topicpage",$currentTopicId);
	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{seacms:member}",front_member(),$content);
	echo str_replace("{seacms:runinfo}",getRunTime($t1),$content);

}

function parseTopicPart($ptopicName,$templatePath,$id,$ptopicDes,$ptopicKeyword,$ptopicPic)
{
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content = str_replace("{seacms:currenttypeid}",-444,$content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,$id);
	$content=$mainClassObj->parseNewsList($content,$currentTypeId);
	$content=$mainClassObj->parseTopicList($content);
	$content=$mainClassObj->parseLinkList($content);
	$content = str_replace("{seacms:topicname}",$ptopicName,$content);
	$content = str_replace("{seacms:topicdes}",$ptopicDes,$content);
	$content = str_replace("{seacms:topickeyword}",$ptopicKeyword,$content);
	if(!empty($ptopicPic)){
	if(strpos(' '.$ptopicPic,'://')>0){
	$ptopicPic=str_replace('uploads/zt/','',$ptopicPic);
	}else{
	$ptopicPic= "/".$GLOBALS['cfg_cmspath'].$ptopicPic;
	}
	}else{
	$ptopicPic= "/".$GLOBALS['cfg_cmspath']."pic/nopic.gif";
	}
	$content = str_replace("{seacms:topicpic}",$ptopicPic,$content);
	return $content;
}
?>