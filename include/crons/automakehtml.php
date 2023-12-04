<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="display:none;">
<?php 
if(!defined('sea_INC'))
{
	exit("Request Error!");
}
@set_time_limit(0);
if(!class_exists("MainClass_Template")) require_once(sea_INC.'/main2.class.php');

/*
帮助提示： 去掉行前面的 // 即可开启，反之关闭！
帮助提示： 去掉行前面的 // 即可开启，反之关闭！
帮助提示： 去掉行前面的 // 即可开启，反之关闭！
帮助提示： 去掉行前面的 // 即可开启，反之关闭！
帮助提示： 去掉行前面的 // 即可开启，反之关闭！
*/

autocache_clear(sea_ROOT.'/data/cache'); //清理缓存
//makeAllmovie(); //生成地图页
$flag = 1 ;
automakeallcustom(); //生成自定义页面
//makeBaidu();  //生成百度地图
//makeBaidux();  //生成百度结构化数据
//makeGoogle();  //生成google地图
//makeRss(); //生成rss页面

if($cfg_runmode=='0'){
makeIndex();  //生成首页	
automakeDay();  //生成今日更新内容和列表
//automakeTopicIndex();  //生成专辑首页
//automakeAllTopic();  //生成专辑页
}
if($cfg_runmode2=='0'){
makeIndex('news');  //生成新闻首页
automakeNewsDay(); //生成今日更新新闻内容和列表
}

function automakeDay()
{
	global $dsql;
	$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
	$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'));
	$wheresql = " and v_ismake=0 and `v_addtime` BETWEEN '{$today_start}' AND '{$today_end}'";
	$pagesize=100;
	if(!$pCount){
	$rowc=$dsql->GetOne("SELECT count(*) as dd FROM `sea_data` WHERE `v_wrong`=0 ".$wheresql);
	$totalnum = $rowc['dd'];
	if($totalnum==0) return false;
	$TotalPage = ceil($totalnum/$pagesize);
	}else{
	$TotalPage = $pCount;
	}
	$sql="select v_id from sea_data where v_wrong=0 $wheresql order by v_addtime DESC";
	$dsql->SetQuery($sql);
	$dsql->Execute('makeDay');
	while($row=$dsql->GetObject('makeDay'))
	{
		makeContentById($row->v_id);
	}
	$ids="";
	$sqlt="SELECT tid from sea_data where v_wrong=0 ".$wheresql." GROUP BY tid";
	$dsql->SetQuery($sqlt);
	$dsql->Execute('makeDayt');
	while($rowt=$dsql->GetObject('makeDayt'))
	{
		if(!isTypeHide($rowt->tid)){
			if(empty($ids)) $ids=$rowt->tid; else $ids.=",".$rowt->tid;
		}
	}

	if(!empty($ids)){
		$tl=getTypeListsOnCache();
		foreach($tl as $vv){
			if (strpos(" ,".$ids.",",",".$vv->tid.",")>0){
				if ($vv->upid>0 && strpos(" ,".$ids.",",",".$vv->tid.",")==0) $ids=$vv->tid.",".$ids;
			}
		}
	}
	if(!empty($ids)){
		automakeChannelByIDS($ids);
		return true;
	}
}

function automakeNewsDay()
{
	global $dsql;
	$today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
	$today_end = mktime(0,0,0,date('m'),date('d')+1,date('Y'));
	$wheresql = " and `n_addtime` BETWEEN '{$today_start}' AND '{$today_end}'";
	$pagesize=100;
	if(!$pCount){
	$rowc=$dsql->GetOne("SELECT count(*) as dd FROM `sea_news` WHERE `n_recycled`=0 ".$wheresql);
	$totalnum = $rowc['dd'];
	if($totalnum==0) return false;
	$TotalPage = ceil($totalnum/$pagesize);
	}else{
	$TotalPage = $pCount;
	}
	$sql="select n_id from sea_news where n_recycled=0 $wheresql";
	$dsql->SetQuery($sql);
	$dsql->Execute('makeDay');
	while($row=$dsql->GetObject('makeDay'))
	{
		makeArticleById($row->n_id);
	}
	$ids="";
	$sqlt="SELECT tid from sea_news where n_recycled=0 ".$wheresql." GROUP BY tid";
	$dsql->SetQuery($sqlt);
	$dsql->Execute('makeDayt');
	while($rowt=$dsql->GetObject('makeDayt'))
	{
		if(!isTypeHide($rowt->tid)){
			if(empty($ids)) $ids=$rowt->tid; else $ids.=",".$rowt->tid;
		}
	}

	if(!empty($ids)){
		$tl=getTypeListsOnCache();
		foreach($tl as $vv){
			if (strpos(" ,".$ids.",",",".$vv->tid.",")>0){
				if ($vv->upid>0 && strpos(" ,".$ids.",",",".$vv->tid.",")==0) $ids=$vv->tid.",".$ids;
			}
		}
	}
	if(!empty($ids)){
		automakeNewsChannelByIDS($ids);
		return true;
	}
}

function automakeChannelByIDS($ids)
{
	$typeIdArray = array();
	$typeIdArray = explode(",",$ids);
	foreach($typeIdArray as $typeId)
	{
		automakeChannelById($typeId);
	}
}

function automakeNewsChannelByIDS($ids)
{
	$typeIdArray = array();
	$typeIdArray = explode(",",$ids);
	foreach($typeIdArray as $typeId)
	{
		automakeNewsChannelById($typeId);
	}
}

function automakeChannelById($typeId)
{
	makeChannelById($typeId);
}

function automakeNewsChannelById($typeId)
{
	makeNewsChannelById($typeId);
}

function automakeTopicIndex()
{
	global $mainClassObj, $dsql;
	$row = $dsql->GetOne("select template from sea_topic");
	$templatePath="/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/topicindex.html";
	$rowc=$dsql->GetOne("select count(*) as dd from sea_topic");
	$page_size = getPageSizeOnCache($templatePath,"topicindex",$row['template']);
	if (empty($page_size)) $page_size=12;
	if(is_array($rowc))
	{
		$TotalResult = $rowc['dd'];
	}
	else
	{
		$TotalResult = 0;
	}
	$pCount=ceil($TotalResult/$page_size);
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"");
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseVideoList($content,'','','');
	$content=$mainClassObj->parseLinkList($content);
	$content=replaceCurrentTypeId($content,-444);
		$content=str_replace("{seacms:member}",front_member(),$content);
	$tempStr = $content;
	for($i=1;$i<=$pCount;$i++)
	{
		$content=$tempStr;
		$content=str_replace("<head>",'<head><script>var seatype="topiclist"; var seaid=0;var seapage='.$i.';</script><script src="/'.$GLOBALS['cfg_cmspath'].'js/seajump.js"></script>',$content);
		$content=$mainClassObj->parseTopicIndexList($content,$i);
		$content=$mainClassObj->parseIf($content);
		if($i==1)$topicindexname=sea_ROOT."/".$GLOBALS['cfg_album_name']."/index".$GLOBALS['cfg_filesuffix2'];
		else $topicindexname=sea_ROOT."/".$GLOBALS['cfg_album_name']."/index".$i.$GLOBALS['cfg_filesuffix2'];
		createTextFile($content,$topicindexname);
	}
	
}

function automakeAllTopic()
{
	global $dsql;
	$dsql->SetQuery("select id from sea_topic order by sort asc");
	$dsql->Execute('altopic');
	while($rowr=$dsql->GetObject('altopic'))
	{
		$rows[]=$rowr;
	}
	unset($rowr);
	if(!is_array($rows)) return false;
	foreach($rows as $row){
		automakeTopicById($row->id);
	}
}

function automakeTopicById($topicId)
{
	makeTopicById($topicId);
}


function autoparseCachePart($pageType,$templatePath,$currentTypeId=-444)
{
	global $mainClassObj;
	switch ($pageType) {
		case "channel":
			$content=loadFile(sea_ROOT.$templatePath);
			$content=$mainClassObj->parseTopAndFoot($content);
			$content=$mainClassObj->parseSelf($content);
			$content=$mainClassObj->parseHistory($content);
			$content=$mainClassObj->parseGlobal($content);
			$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
			$content=$mainClassObj->parseAreaList($content);
			$content=$mainClassObj->parseVideoList($content,$currentTypeId);
			$content=$mainClassObj->parseTopicList($content);
			$content = str_replace("{channelpage:typetext}",getTypeText($currentTypeId),$content);
			$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
		break;
		case "newspage":
			$content=loadFile(sea_ROOT.$templatePath);
			$content=$mainClassObj->parseTopAndFoot($content);
			$content=$mainClassObj->parseHistory($content);
			$content=$mainClassObj->parseSelf($content);
			$content=$mainClassObj->parseGlobal($content);
			$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
			$content=$mainClassObj->parseAreaList($content);
			$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseNewsList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseTopicList($content);
			$content = str_replace("{newspagelist:typetext}",getTypeText($currentTypeId,1),$content);
			$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
		break;
		case "parse_content_":
			$content=loadFile(sea_ROOT.$templatePath);
			$content=$mainClassObj->parsePlayPageSpecial($content);
			$content=$mainClassObj->parseTopAndFoot($content);
			$content=$mainClassObj->parseSelf($content);
			$content=$mainClassObj->parseHistory($content);
			$content=$mainClassObj->parseGlobal($content);
			$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
			$content=$mainClassObj->parseAreaList($content);
			$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseTopicList($content);
			$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
		break;
		case "parse_play_":
			$content=loadFile(sea_ROOT.$templatePath);
			$content=$mainClassObj->parsePlayPageSpecial($content);
			$content=$mainClassObj->parseTopAndFoot($content);
			$content=$mainClassObj->parseSelf($content);
			$content=$mainClassObj->parseHistory($content);
			$content=$mainClassObj->parseGlobal($content);
			$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
			$content=$mainClassObj->parseAreaList($content);
			$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseTopicList($content);
			$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
		break;
		case "topic":
			$content=loadFile(sea_ROOT.$templatePath);
			$content=$mainClassObj->parseTopAndFoot($content);
			$content=$mainClassObj->parseSelf($content);
			$content=$mainClassObj->parseHistory($content);
			$content=$mainClassObj->parseGlobal($content);
			$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
			$content=$mainClassObj->parseAreaList($content);
			$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseTopicList($content);
			$content=$mainClassObj->parseLinkList($content);
			$content = str_replace("{seacms:currenttypeid}",-444,$content);
		break;
		case "parse_article_":
			$content=loadFile(sea_ROOT.$templatePath);
			$content=$mainClassObj->parseTopAndFoot($content);
			$content=$mainClassObj->parseNewsPageSpecial($content);
			$content=$mainClassObj->parseSelf($content);
			$content=$mainClassObj->parseHistory($content);
			$content=$mainClassObj->parseGlobal($content);
			$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
			$content=$mainClassObj->parseAreaList($content);
			$content=$mainClassObj->parseNewsAreaList($content);
			$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseNewsList($content,$currentTypeId,'','');
			$content=$mainClassObj->parseTopicList($content);
			$content=$mainClassObj->parseLinkList($content);
			$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
	}
	return $content;
}


function automakeCustomInfo($templatename)
{
	global $mainClassObj,$dsql,$customLink;
	$self_str="self_";
	$pcount=0;
	$templatePath = "/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/".$templatename; 
    $customLink="/".$GLOBALS['cfg_cmspath'].str_replace(".html","",str_replace("#", "/", str_replace($self_str,"",$templatename)))."<page>.html";
	$content=loadFile(sea_ROOT.$templatePath);
	if(strpos($content, "{/seacms:customvideolist}")>0){
		$pSize = getPageSizeOnCache($templatePath,"customvideo",$templatename);
		if (empty($pSize)) $pSize=12;
		$sql="select count(*) as dd from sea_data where v_recycled=0";
		$row = $dsql->GetOne($sql);
		if(is_array($row))
		{
			$TotalResult = $row['dd'];
		}
		else
		{
			$TotalResult = 0;
		}
		$pCount = ceil($TotalResult/$pSize);
	}
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseNewsAreaList($content);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseNewsList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseTopicList($content);
	$content=$mainClassObj->parseLinkList($content);
	$content=replaceCurrentTypeId($content,-444);
	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{seacms:runinfo}","",$content);
		$content=str_replace("{seacms:member}",front_member(),$content);
	if(strpos($content, "{/customvideolist}")===false)$pcount=1;
	for($i=1;$i<=100;$i++)
	{
		$tmp=$content;
		$tmp=str_replace("{customvideo:page}", $i, str_replace("{customvideopage:page}",$i,$tmp));
		$tmp=$mainClassObj->parsePageList($tmp, 0, $i, $pCount,$TotalResult, "customvideo");
		$link=getCustomLink($i);
		$dir=str_replace($GLOBALS['cfg_cmspath'],'',$link);
		createTextFile ($tmp,sea_ROOT.$dir);
		if($i>=$pCount)break;
	}
}

function autocache_clear($dir) {
  $dh=@opendir($dir);
  while ($file=@readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(is_file($fullpath)) {
          @unlink($fullpath);
      }
    }
  }
  closedir($dh); 
}


function automakeallcustom()
{
	global $cfg_basedir,$cfg_df_style,$cfg_df_html;
	$templetdird = $cfg_basedir."templets/".$cfg_df_style."/".$cfg_df_html."/";
	$dh = dir($templetdird);
	while($filename=$dh->read())
	{
	if(strpos($filename,"elf_")>0) automakeCustomInfo($filename);
	}
}
?>
</body>
</html>