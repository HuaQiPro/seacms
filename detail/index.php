<?php 
require_once(dirname(__FILE__)."/../include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");


if($GLOBALS['cfg_runmode']==2||$GLOBALS['cfg_paramset']==0){
	$paras=str_replace(getfileSuffix(),'',$_SERVER['QUERY_STRING']);
	$id=intval($paras);
	$id = (isset($id) && is_numeric($id) ? $id : 0);
}else{
	$id=$$GLOBALS['cfg_paramid'];
	$id=intval($id);
}
$id=intval($id);
if($id==0){
	showmsg('参数丢失，请返回！', -1);
	exit;
}
	require(dirname(__FILE__)."/../data/config.plus.inc.php"); 
	//网站改版
    if($PLUS["JmpVideo"]['off']){$pID=$PLUS["JmpVideo"]['data'][$id];if($pID){$id=$pID;}}
echoContent($id);

function echoContent($vId)
{
	global $dsql,$cfg_iscache,$mainClassObj,$t1,$cfg_user,$cfg_isfromsort;
	$row=$dsql->GetOne("Select d.*,p.body as v_playdata,p.body1 as v_downdata,c.body as v_content From `sea_data` d left join `sea_playdata` p on p.v_id=d.v_id left join `sea_content` c on c.v_id=d.v_id where d.v_id='$vId'");
	if(!is_array($row)){ShowMsg("该内容已被删除或者隐藏","../index.php",0,10000);exit();}
	$vType=$row['tid'];
	$vtag=$row['v_name'];
	$contentTmpName=getContentTemplateOnCache($vType);
	$contentTmpName=empty($contentTmpName) ? "content.html" : $contentTmpName;
	$contentTemplatePath = "/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/".$contentTmpName;
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$contentTemplatePath = "/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/".$contentTmpName;}
	$vExtraType = $row['v_extratype'];
	if (strpos(" ,".getHideTypeIDS().",",",".$vType.",")>0){ShowMsg("该内容已被删除或者隐藏","../index.php",0,10000);exit();}
	if ($row['v_recycled']==1){ShowMsg("该内容已被删除或者隐藏","../index.php",0,10000);exit();};
	if ($cfg_user == 1){
        if (!getUserAuth($vType, "detail")){ShowMsg("您当前的会员级别没有权限浏览此内容！","../member.php",0,20000);exit();}
	}
	$contentLink=getContentLink($vType,$vId,"link",date('Y-n',$row['v_addtime']),$row['v_enname']);
	$typeText = getTypeText($vType);
	$currentTypeId=$vType;
	$GLOBALS[tid]=$currentTypeId;
	$typeFlag = "parse_content_" ;
	$cacheName = $typeFlag.$vType.$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	
	if($cfg_iscache){
		if(chkFileCache($cacheName)){
			$content = getFileCache($cacheName);
		}else{
			$content = parseContentPart($contentTemplatePath,$currentTypeId,$vtag);
		}
	}else{
			$content = parseContentPart($contentTemplatePath,$currentTypeId,$vtag);
	}
	$content=$mainClassObj->parsePlayList($content,$vId,$vType,date('Y-n',$row['v_addtime']),$row['v_enname'],$row['v_playdata'],'play');
	$content=$mainClassObj->parsePlayList($content,$vId,$vType,date('Y-n',$row['v_addtime']),$row['v_enname'],$row['v_downdata'],'down');
	$content=str_replace("{playpage:id}",$row['v_id'],$content);
	$content=str_replace("{playpage:upid}",getUpId($vType),$content);
	$content=str_replace("{playpage:name}",$row['v_name'],$content);
	$content=str_replace("{playpage:url}",$GLOBALS['cfg_basehost'].$contentLink,$content);
	$content=str_replace("{playpage:link}",$contentLink,$content);
	
	
	//如果开启播放来源排序，获取第一排序的播放组地址
	if($cfg_isfromsort==1){
		$playDataArray = getPlayurlArray ($row['v_playdata']);
		$playerDic = getPlayerKindsArray ();
		$vnum1 = substr_count ($row['v_playdata'], '$$' );
					if ($vnum1 == 0) {
						$vnum = 0;
					} else {
						$vnum = count ( $playDataArray );
					}
		for($i = 0; $i <= $vnum; $i ++) {
							$singlePlayData = explode ( "$$", $playDataArray [$i] );
							$playerSingleInfoArray[] = $playerDic [$singlePlayData [0]];
							
						}
		$playerSingleInfoArray=array_filter($playerSingleInfoArray);

		foreach($playerSingleInfoArray as $k=>$v){
			{$b[$k]=(string)$v['sort'];}
			}
		$d=array_search(min($b),$b);
	}else{
		$d=0;
	}
	
	
	
	$content=str_replace("{playpage:playlink}",getPlayLink2($vType,$vId,date('Y-n',$row['v_addtime']),$row['v_enname'],$d),$content);
	$content=str_replace("{playpage:typelink}",getChannelPagesLink($vType),$content);
	if(strpos($content,"{playpage:typename}")>0) 
	{
		$content=str_replace("{playpage:typename}",getTypeName($vType).getExtraTypeName($vExtraType),$content);	
	}
	if(strpos($content,"{playpage:linktypename}")>0) 
	{
		$connector = "</a>";
		$content=str_replace("{playpage:linktypename}","<a href=\"".getChannelPagesLink($vType)."\">".getTypeName($vType).$connector.getExtraTypeName($vExtraType,$connector).$connector,$content);	
	}
	$content=str_replace("{playpage:typeid}",$vType,$content);
	$content=str_replace("{playpage:lang}",$row['v_lang'],$content);
	$content=str_replace("{playpage:encodename}",urlencode($row['v_name']),$content);
	$content=str_replace("{playpage:note}",$row['v_note'],$content);
	$content=str_replace("{playpage:longtxt}",$row['v_longtxt'],$content);
	$content=str_replace("{playpage:diggnum}",$row['v_digg'],$content);
	$content=str_replace("{playpage:scorenum}",$row['v_score'],$content);
	$content=str_replace("{playpage:scorenumer}",$row['v_scorenum'],$content);
	$score=number_format($row[v_score]/$row[v_scorenum],1);
	$content=str_replace("{playpage:score}",$score,$content);
	$content=str_replace("{playpage:treadnum}",$row['v_tread'],$content);
	$content=str_replace("{playpage:nolinkkeywords}",$row['v_tags'],$content);
	$content=str_replace("{playpage:nolinkjqtype}",$row['v_jq'],$content);
	$content=str_replace("{playpage:money}",$row['v_money'],$content);
	$content=str_replace("{playpage:dayhit}",$row['v_dayhit'],$content);
	$content=str_replace("{playpage:weekhit}",$row['v_weekhit'],$content);
	$content=str_replace("{playpage:monthhit}",$row['v_monthhit'],$content);
	$content=str_replace("{playpage:nickname}",$row['v_nickname'],$content);
	$content=str_replace("{playpage:reweek}",$row['v_reweek'],$content);
	$content=str_replace("{playpage:vodlen}",$row['v_len'],$content);
	$content=str_replace("{playpage:vodtotal}",$row['v_total'],$content);
	$content=str_replace("{playpage:douban}",$row['v_douban'],$content);
	$content=str_replace("{playpage:mtime}",$row['v_mtime'],$content);
	$content=str_replace("{playpage:imdb}",$row['v_imdb'],$content);
	$content=str_replace("{playpage:tvs}",$row['v_tvs'],$content);
	$content=str_replace("{playpage:company}",$row['v_company'],$content); 	
	$content=str_replace("{playpage:desktopurl}",'/'.$GLOBALS['cfg_cmspath'].'desktop.php?name='.urlencode($row['v_name']).'&url='.urlencode($GLOBALS['cfg_basehost'].$contentLink),$content);
	if (strpos($content,"{playpage:keywords}")>0) $content=str_replace("{playpage:keywords}",getKeywordsList($row['v_tags'],"&nbsp;&nbsp;"),$content);
	if (strpos($content,"{playpage:jqtype}")>0) $content=str_replace("{playpage:jqtype}",getJqList($row['v_jq'],"&nbsp;&nbsp;"),$content);
	$v_pic=$row['v_pic'];
	
	if(!empty($v_pic)){
		if(strpos(' '.$v_pic,'://')>0){
		$content=str_replace("{playpage:pic}",$v_pic,$content);
		}else{
		$content=str_replace("{playpage:pic}",'/'.$GLOBALS['cfg_cmspath'].ltrim($v_pic,'/'),$content);
		}
	}else{
	$content=str_replace("{playpage:pic}",'/'.$GLOBALS['cfg_cmspath'].'pic/nopic.gif',$content);
	}
	
	$v_spic=$row['v_spic'];
	
	if(!empty($v_spic)){
		if(strpos(' '.$v_spic,'://')>0){
		$content=str_replace("{playpage:spic}",$v_spic,$content);
		}else{
		$content=str_replace("{playpage:spic}",'/'.$GLOBALS['cfg_cmspath'].ltrim($v_spic,'/'),$content);
		}
	}else{
	$content=str_replace("{playpage:spic}",'/'.$GLOBALS['cfg_cmspath'].'pic/nopic.gif',$content);
	}
	
	$v_gpic=$row['v_gpic'];
	
	if(!empty($v_gpic)){
		if(strpos(' '.$v_gpic,'://')>0){
		$content=str_replace("{playpage:gpic}",$v_gpic,$content);
		}else{
		$content=str_replace("{playpage:gpic}",'/'.$GLOBALS['cfg_cmspath'].ltrim($v_gpic,'/'),$content);
		}
	}else{
	$content=str_replace("{playpage:gpic}",'/'.$GLOBALS['cfg_cmspath'].'pic/nopic.gif',$content);
	}
	
	$v_actor=$row['v_actor'];
	$v_tags=$row['v_tags'];
	$v_des=$row['v_content'];
	$v_des=htmlspecialchars_decode($v_des);
	$v_des=doPseudo($v_des, $vId);
	$content=str_replace("{playpage:actor}",getKeywordsList($v_actor,"&nbsp;&nbsp;"),$content);
	$content=str_replace("{playpage:director}",getKeywordsList($row['v_director'],"&nbsp;&nbsp;"),$content);
	$content=str_replace("{playpage:tags}",getTagsList($v_tags,"&nbsp;&nbsp;"),$content);
	$content=str_replace("{playpage:nolinkactor}",$v_actor,$content);
	$content=str_replace("{playpage:nolinkdirector}",$row['v_director'],$content);
	$content=str_replace("{playpage:nolinkatags}",$v_tags,$content);
	$content=str_replace("{playpage:publishtime}",$row['v_publishyear'],$content);
	$content=str_replace("{playpage:ver}",$row['v_ver'],$content);
	$content=str_replace("{playpage:publisharea}",$row['v_publisharea'],$content);
	$content=str_replace("{playpage:addtime}",MyDate('Y-m-d H:i',$row['v_addtime']),$content);
	$content=str_replace("{playpage:addtime2}",MyDate('Y-m-d\TH:i:s',$row['v_addtime']),$content);
	$content=str_replace("{playpage:state}",$row['v_state'],$content);
	$content=str_replace("{playpage:commend}",$row['v_commend'],$content);
	$content=str_replace("{playpage:des}",$v_des,$content);
	$content = parseLabelHaveLen($content,$v_actor,"actor");
	$content = parseLabelHaveLen($content,$v_actor,"nolinkactor");
	$content = parseLabelHaveLen($content,$v_tags,"tags");
	$content = parseLabelHaveLen($content,$v_tags,"nolinktags");
	$content = parseLabelHaveLen($content,Html2Text($v_des),"des");
	$content = parseLabelHaveLen($content,$row['v_name'],"name");
	$content = parseLabelHaveLen($content,$row['v_note'],"note");
	$content = $mainClassObj->paresPreNextVideo($content,$vId,$typeFlag,$vType);
	$content = $mainClassObj->paresPreVideo($content,$vId,$typeFlag,$vType);
	$content = $mainClassObj->paresNextVideo($content,$vId,$typeFlag,$vType);
	$content = str_replace("{playpage:textlink}",$typeText."&nbsp;&nbsp;&raquo;&nbsp;&nbsp;".$row['v_name'],$content);
	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{seacms:member}",front_member(),$content);
	echo str_replace("{seacms:runinfo}",getRunTime($t1),$content) ;
}

function parseContentPart($templatePath,$currentTypeId,$vtag)
{
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parsePlayPageSpecial($content);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseNewsList($content,$currentTypeId,$vtag,'');
	$content=$mainClassObj->parseTopicList($content);
	return $content;
}
?>