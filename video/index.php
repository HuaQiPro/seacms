<?php 
session_start();
require_once(dirname(__FILE__)."/../include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");

if($GLOBALS['cfg_runmode']==2||$GLOBALS['cfg_paramset']==0){
	$paras=str_replace(getfileSuffix(),'',$_SERVER['QUERY_STRING']);
	if(strpos($paras,"-")>0){
		$parasArray=explode("-",$paras);
		if(count($parasArray>2)){
		$vid=$parasArray[0];
		$id=$parasArray[1];
		$from=$parasArray[2];
		}else{
			showmsg('参数丢失，请返回！', -1);
			exit;
		}
	}else{
		$vid=$paras;
		$id=0;
		$from=0;
	}
	$vid = (isset($vid) && is_numeric($vid) ? $vid : 0);
	$from = (isset($from) && is_numeric($from) ? $from : 0);
	$id = (isset($id) && is_numeric($id) ? $id : 0);
}else{
	$vid = $$GLOBALS['cfg_paramid'];
	$id = $$GLOBALS['cfg_parampage'];
	$from = $$GLOBALS['cfg_paramindex'];
	$vid = (isset($vid) && is_numeric($vid) ? $vid : 0);
	$from = (isset($from) && is_numeric($from) ? $from : 0);
	$id = (isset($id) && is_numeric($id) ? $id : 0);
}
$id=intval($id);
$vid=intval($vid);
$from=intval($from);
if($vid==0){
	showmsg('参数丢失，请返回！', -1);
	exit;
}
	require(dirname(__FILE__)."/../data/config.plus.inc.php"); 
	//网站改版
    if($PLUS["JmpVideo"]['off']){$pID=$PLUS["JmpVideo"]['data'][$vid];if($pID){$vid=$pID;}}
if($GLOBALS['cfg_runmode']==1) {$action=$_GET['action'];}
$uid=$_SESSION['sea_user_id'];
$uid = intval($uid);
if($action=="pay")
{
		if(empty($_SESSION['sea_user_id'])){showMsg("请先登录","../login.php"); exit();}	
		$row7=$dsql->GetOne("Select v_money from sea_data where v_id='$vid'");
		$vmoney=intval($row7['v_money']);
		if($vmoney=="" OR empty($vmoney)){$vmoney=0;}
		$row6=$dsql->GetOne("Select * from sea_member where id='$uid'");
		if($row6['points']<$vmoney)
		{
			showMsg("抱歉，".$cfg_pointsname."不足，系统将跳转至充值中心","../member.php",0,5000); exit();
		}
		$vid = (isset($vid) && is_numeric($vid) ? $vid : 0);
		$from2 = (isset($from2) && is_numeric($from2) ? $from2 : 0);
		$row3 = $dsql->GetOne("Select * From sea_buy where vid=$vid and vfrom=$from2 and uid=$uid ");
		$turl= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$turl= str_replace('&action=pay&from2='.$from2,'',$turl);
		$turl= "//".$turl;
		if(!is_array($row3))
		{
			$rowpay = $dsql->GetOne("Select * From sea_data where v_id=$vid");
			$vmoneypay=$rowpay['v_money'];
			$sqlpay="Update sea_member set points = points-$vmoneypay where id=$uid";
			$dsql->ExecuteNoneQuery("$sqlpay");
			$dsql->ExecuteNoneQuery("insert into sea_buy values('','$uid','$vid','$from2','$vmoneypay','".time()."')");
			echo "<script>alert('购买成功');top.location.href='".$turl."';</script>";
			exit;
		}
		else
		{echo "<script>alert('已购买！');top.location.href='".$turl."';</script>";exit;}
	
}

echoPlay($vid);

function echoPlay($vId)
{
	global $dsql,$cfg_isalertwin,$cfg_ismakeplay,$cfg_iscache,$mainClassObj,$cfg_playaddr_enc,$id,$from,$t1,$cfg_runmode,$cfg_user,$cfg_pointsname,$payid,$cfg_cmspath,$cfg_isfromsort;
	
	$row=$dsql->GetOne("Select d.*,p.body as v_playdata,p.body1 as v_downdata,c.body as v_content From `sea_data` d left join `sea_playdata` p on p.v_id=d.v_id left join `sea_content` c on c.v_id=d.v_id where d.v_id='$vId'");
	if(!is_array($row)){ShowMsg("该内容已被删除或者隐藏","../index.php",0,10000);exit();}
	$vType=$row['tid'];
	
	$playTemFileName=getPlayTemplateOnCache($vType);
	$playTemFileName=empty($playTemFileName) ? "play.html" : $playTemFileName;
	$playTemplatePath = "/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/".$playTemFileName;
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$playTemplatePath = "/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/".$playTemFileName;}
	
	
	$vtag=$row['v_name'];
	$vmoney=$row['v_money'];
	if($vmoney=="" OR empty($vmoney)){$vmoney=0;}
	$vtry=$row['v_try'];
	$vExtraType = $row['v_extratype'];
	$uid=$_SESSION['sea_user_id'];
	if (strpos(" ,".getHideTypeIDS().",",",".$vType.",")>0){ShowMsg("该内容已被删除或者隐藏","../index.php",0,10000);exit();}
	if ($row['v_recycled']==1){ShowMsg("该内容已被删除或者隐藏","../index.php",0,10000);exit();};
	if ($cfg_user == 1){
		
		$rowvip=$dsql->GetOne("SELECT v_vip FROM sea_data where v_id=".$vId);
		$vip=$rowvip['v_vip'];
		$urlArray=getLinkArr($row['v_playdata'],$id);
		$totalLinkvip = count($urlArray);
		
		if(strpos($vip,'s')!==false)
		{
			$vips=str_ireplace('s', "", $vip);
			$viparr=array_flip(array_slice($urlArray,0,$vips,true));
		}
		elseif(strpos($vip,'e')!==false)
		{
			$vipe=str_ireplace('e', "", $vip);
			$vipes=$totalLinkvip - $vipe;
			$viparr=array_flip(array_slice($urlArray,$vipes,$vipe,true));		
		}
		elseif(strpos($vip,'a')!==false)
		{
			$viparr=array_flip(array_slice($urlArray,0,$totalLinkvip,true));		
		}
		elseif(strpos($vip,'f')!==false)
		{
			$vips=str_ireplace('f', "", $vip);
			$viparr=array_flip(array_slice($urlArray,$vips,NULL,true));
		}
		else
		{
			$viparr2=explode(',',$vip);
			foreach ($viparr2 as $value) 
			{
			  $viparr[]=$value-1;
			}
		}

		
        if(!getUserAuth($vType, "play")){ShowMsg("您当前的会员级别没有权限浏览此内容！","../member.php",0,20000);exit();}
		//if(in_array($from,$viparr) AND empty($_SESSION['sea_user_id'])){showMsg("请先登录","../login.php"); exit();}	
		if(in_array($from,$viparr) AND $vtry==0 AND !getUserAuth($vType, "pay"))
		{
			$row2=$dsql->GetOne("Select * from sea_buy where vid='$vId' and vfrom=$from and uid='$uid'");
			if(!is_array($row2))
			{
					$vipstop='yes';		
			}
		}
	}
	

	
	
	
	$typeText = getTypeText($vType);
	$contentLink = getContentLink($vType,$vId,"",date('Y-n',$row['v_addtime']),$row['v_enname']);
	$contentLink2 = getContentLink($vType,$vId,"link",date('Y-n',$row['v_addtime']),$row['v_enname']);
	$currentTypeId=$vType;
	$GLOBALS[tid]=$currentTypeId;
	$typeFlag = "parse_play_" ;
	$cacheName = $typeFlag.$vType.$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	if($cfg_iscache){
		if(chkFileCache($cacheName)){
			$content = getFileCache($cacheName);
		}else{
			$content = parsePlayPart($playTemplatePath,$currentTypeId,$vtag);
		}
	}else{
			$content = parsePlayPart($playTemplatePath,$currentTypeId,$vtag);
	}
	$content=$mainClassObj->parsePlayList($content,$vId,$vType,date('Y-n',$row['v_addtime']),$row['v_enname'],$row['v_playdata'],'play');
	$content=$mainClassObj->parsePlayList($content,$vId,$vType,date('Y-n',$row['v_addtime']),$row['v_enname'],$row['v_downdata'],'down');
	$content=str_replace("{playpage:id}",$row['v_id'],$content);
	$content=str_replace("{playpage:upid}",getUpId($vType),$content);
	$content=str_replace("{playpage:name}",$row['v_name'],$content);
	$content=str_replace("{playpage:url}",$GLOBALS['cfg_basehost'].$contentLink2,$content);
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
	$totalLink = getLinkNum($row['v_playdata'],$id);
	$nextplaylink = getPlayLink2($vType,$vId,date('Y-n',$row['v_addtime']),$row['v_enname'],$id,$from+1>=$totalLink?$totalLink:$from+1);
	$preplaylink  = getPlayLink2($vType,$vId,date('Y-n',$row['v_addtime']),$row['v_enname'],$id,$from-1<=0?0:$from-1);
	$content=str_replace("{playpage:nextplaylink}",$nextplaylink,$content);
	$content=str_replace("{playpage:preplaylink}",$preplaylink,$content);
	if(strpos($content,"{playpage:typename}")>0) 
	{
		$content=str_replace("{playpage:typename}",getTypeName($vType).getExtraTypeName($vExtraType),$content);	
	}
	if(strpos($content,"{playpage:linktypename}")>0) 
	{
		$connector = "</a>";
		$content=str_replace("{playpage:linktypename}","<a href=\"".getChannelPagesLink($vType)."\">".getTypeName($vType).$connector.getExtraTypeName($vExtraType,$connector).$connector,$content);	
	}
	$content=str_replace("{playpage:typelink}",getChannelPagesLink($vType),$content);
	$content=str_replace("{playpage:encodename}",urlencode($row['v_name']),$content);
	$content=str_replace("{playpage:note}",$row['v_note'],$content);
	$content=str_replace("{playpage:longtxt}",$row['v_longtxt'],$content);
	$content=str_replace("{playpage:typeid}",$row['tid'],$content); 
	$content=str_replace("{playpage:diggnum}",$row['v_digg'],$content);
	$content=str_replace("{playpage:treadnum}",$row['v_tread'],$content);
	$score=number_format($row[v_score]/$row[v_scorenum],1);
	$content=str_replace("{playpage:score}",$score,$content);
	$content=str_replace("{playpage:scorenum}",$row['v_score'],$content);
	$content=str_replace("{playpage:scorenumer}",$row['v_scorenum'],$content);
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
	$content=str_replace("{playpage:lang}",$row['v_lang'],$content);
	$content=str_replace("{playpage:addtime}",MyDate('Y-m-d H:i',$row['v_addtime']),$content);
	$content=str_replace("{playpage:addtime2}",MyDate('Y-m-d\TH:i:s',$row['v_addtime']),$content);
	$content=str_replace("{playpage:state}",$row['v_state'],$content);
	$content=str_replace("{playpage:commend}",$row['v_commend'],$content);
	$content=str_replace("{playpage:des}",$v_des,$content);
	$content=str_replace("{seacms:shang}",'<a href="#" onclick="shang(prePage,sssss)">上一集</a>',$content) ;
	$content=str_replace("{seacms:xia}",'<a href="#" onclick="xia(nextPage,zno)">下一集</a>',$content) ;
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
	$partName=getPartName2($row['v_playdata'],$id,$from);
	$partNameN=getPartName2($row['v_playdata'],$id,$from+1);
	$content = str_replace("{playpage:from}",$partName[0],$content);
	$content = str_replace("{playpage:part}",$partName[1],$content);
	$content = str_replace("{playpage:dz}",$partName[2],$content);
	$content = str_replace("{playpage:ename}",$partName[3],$content);

//隐藏的播放地址start
$str=$row['v_playdata'];
$arr1=array();
$arr2=array();
$arr1=explode('$$$',$str);
$p=getPlayerKindsArray2();
foreach($p as $key=>$player2)
{
	if($player2[0]==0)
	{$arr2[]=$key;}
}
foreach($arr2 as $player)
{
	foreach($arr1 as $key=>$dz)
	{
		if(strstr($dz,$player)!==false)
		{$arr1[$key]='该组已屏蔽$$已屏蔽';}
	}
}
$str=implode('$$$',$arr1); //最终地址
//隐藏的播放地址end
	if($cfg_playaddr_enc=='escape'){
		$content = str_replace("{playpage:playurlinfo}","<script>var vid=\"".$row['v_id']."\";var vfrom=\"".$id."\";var vpart=\"".$from."\"; var now=unescape(\"".escape($partName[2])."\");var pn=\"".$partName[3]."\";var next=unescape(\"".escape($partNameN[2])."\");var prePage=\"".$preplaylink."\";var nextPage=\"".$nextplaylink."\";</script>",$content);
	}elseif($cfg_playaddr_enc=='base64'){
		$content = str_replace("{playpage:playurlinfo}","<script>var vid=\"".$row['v_id']."\";var vfrom=\"".$id."\";var vpart=\"".$from."\"; var now=base64decode(\"".base64_encode($partName[2])."\");var pn=\"".$partName[3]."\";var next=base64decode(\"".base64_encode($partNameN[2])."\");var prePage=\"".$preplaylink."\";var nextPage=\"".$nextplaylink."\";</script>",$content);
	}else{
		$content = str_replace("{playpage:playurlinfo}","<script>var vid=\"".$row['v_id']."\";var vfrom=\"".$id."\";var vpart=\"".$from."\";var now=\"".$partName[2]."\";var pn=\"".$partName[3]."\"; var next=\"".$partNameN[2]."\";var prePage=\"".$preplaylink."\";var nextPage=\"".$nextplaylink."\";</script>",$content);
	}
	$content = str_replace("{playpage:textlink}",$typeText."&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href='".$contentLink2."'>".$row['v_name']."</a>",$content);
	$playerwidth = 1;
	$playerheight = 1;
	
/*---------插件管理---------*/ 
	require(dirname(__FILE__)."/../data/config.plus.inc.php");   	
  	
    //提高兼容性
	  $HideVideo_off=$PLUS["HideVideo"]['off'] ; $HideVideo_data=$PLUS["HideVideo"]['data'];  $HideVideo_info=$PLUS["HideVideo"]['info']; 
      $HideName_off=$PLUS["HideName"]['off'] ; $HideName_data=$PLUS["HideName"]['data'];  $HideName_info=$PLUS["HideName"]['info']; 
      $HideType_off=$PLUS["HideType"]['off'] ;  $HideType_data=$PLUS["HideType"]['data']; $HideType_info=$PLUS["HideType"]['info']; 
    
	//版权屏蔽
    if($HideVideo_off && in_array($vId,$HideVideo_data)){
		//$content=$mainClassObj->parseGlobal($HideVideo_info);ShowMsg($content,"../index.php",0,2000);exit();
		$content = str_replace("{playpage:player}",$HideVideo_info,$content);
		}
    
	//视频屏蔽
	elseif($HideName_off &&  $HideName_data[0]!="" && preg_match("{".implode("|",$HideName_data) . "}i", $row['v_name'])) {
	 	//$content=$mainClassObj->parseGlobal($HideName_info);ShowMsg($content,"../index.php",0,2000);exit();
		$content = str_replace("{playpage:player}",$HideName_info,$content);
	 		 	
	//限制分类必须使用移动设备
    }elseif($HideType_off  &&  $GLOBALS['isMobile']==false &&  $HideType_data[0]!=""  &&  in_array($row['tid'],$HideType_data)){      	
      	$content = str_replace("{playpage:player}",$HideType_info,$content);					 
    }
	//视频收费
	elseif($vipstop=="yes"){ 
		$viptips="<div class='x-showtips-txt'  style='position:absolute;top:50%;left:50%;width: 100%;transform:translate(-50%,-50%);text-align:center;background: #1a1b1b;padding: 20px;'><div class='x-tips-title' style='font-size:16px;font-weight:700;color:#fff';>抱歉，本片需要购买观看完整版</div><div class='x-tips-subTitle'   style='font-size: 12px;color: #ccc;margin-top: 4px;max-height: 17px';>开通VIP购买此片，可享受会员权限</div><div class='x-showtips-btn'   style='width: 100%;float: left;padding: 5px';><div class='x-btn x-btn-try'  style='border: 1px solid #ebba73;border-radius: 22.5px;box-sizing: border-box;width: 150pxposition: relative;margin-top: 14px;display: inline-block;padding: 0 12px; margin-right: 12px;color: #c8a764;background-image: linear-gradient(270deg,#1b1b1b 0,#000 99%)';><div class='x-btn-text' ><a style='display: inline-block;text-align: center;font-size: 13px;color: #ebba73;height: 32px;line-height: 36px; width: 100%;max-width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;text-decoration:none'; href=//".$_SERVER['HTTP_HOST']."/video/?".$vId."-".$id."-".$from.getfileSuffix()."&action=pay&from2=".$from.">非会员".$vmoney."".$cfg_pointsname."购买</a></div></div><div class='x-btn x-btn-buy' style='border: 1px solid #ebba73;border-radius: 22.5px;box-sizing: border-box;width: 150px;position: relative;margin-top: 14px;display: inline-block;padding: 0 12px;background-image: linear-gradient(132deg,#e1b271 0,#fce5aa 100%)';><div class='x-btn-text x-btn-buy-text'><a  style='display: inline-block;text-align: center;font-size: 13px;color: #ebba73;height: 32px;line-height: 36px;width: 100%;max-width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-size: 13px;font-size: 13px;font-weight: 700;color: #5b3301;text-decoration:none'; href='/member.php'>开通会员</a></div></div></div></div>";
	
      	$content = str_replace("{playpage:player}",$viptips,$content);					 
    }
	else{  	
/*---------插件管理---------*/
     
$password=$row['v_psd'];
$playerurl='/'.$cfg_cmspath.'/js/player/';
$playerurl=str_replace('//','/',$playerurl);
if(!empty($password) AND !in_array($from,$viparr)){
		if($_POST['password'] !== $password)  {
		$content = str_replace("{playpage:player}","<!DOCTYPE html><html><head><title>请输入视频播放口令后继续</title></head><body leftmargin='0' topmargin='0'><center><div style='font-size:12px; width:100%;height:100%;'><div style='width:220px; height:50px;text-align:left; margin-top:30px;'>请输入密码后继续：<br /><form action='' method='post'><input style='border:1px solid #3374b4;height:33px;line-height:33px;padding-left:5px' type='password' name='password' /><input style='border:1px solid #3374b4;background:#3374b4;height:33px;width:50px;color:#fff;text-decoration:none;' type='submit' value='播 放' /></form></div></div><br><img style='margin:15px 0 5px 0' src='".$GLOBALS ['cfg_ewm']."' height='100' width='100'><br/>扫描二维码关注微信<br />回复<font color='red'>".$vId."</font>获取播放密码</center></body></html>",$content);
		}
	else{
		if($cfg_runmode==2) $content = str_replace("{playpage:player}","<iframe id='cciframe' scrolling='no' frameborder='0' allowfullscreen></iframe><script>var pn=pn;var forcejx1=forcejx;var forcejx2=\"no\";var forcejx3=forcejx;if(forcejx1!=forcejx2 && contains(unforcejxARR,pn)==false){pn=forcejx3;}else{pn=pn;}document.getElementById(\"cciframe\").width = playerw;document.getElementById(\"cciframe\").height = playerh;document.getElementById(\"cciframe\").src = '{$playerurl}'+ pn + '.html';</script>",$content);
		else $content = str_replace("{playpage:player}","<iframe id='cciframe' scrolling='no' frameborder='0' allowfullscreen></iframe><script>var pn=pn;var forcejx1=forcejx;var forcejx2=\"no\";var forcejx3=forcejx;if(forcejx1!=forcejx2 && contains(unforcejxARR,pn)==false){pn=forcejx3;}else{pn=pn;}document.getElementById(\"cciframe\").width = playerw;document.getElementById(\"cciframe\").height = playerh;document.getElementById(\"cciframe\").src = '{$playerurl}'+ pn + '.html';</script>",$content);
	}	
	
}
else{
	if($cfg_runmode==2) $content = str_replace("{playpage:player}","<iframe id='cciframe' scrolling='no' frameborder='0' allowfullscreen></iframe><script>var pn=pn;var forcejx1=forcejx;var forcejx2=\"no\";var forcejx3=forcejx;if(forcejx1!=forcejx2 && contains(unforcejxARR,pn)==false){pn=forcejx3;}else{pn=pn;}document.getElementById(\"cciframe\").width = playerw;document.getElementById(\"cciframe\").height = playerh;document.getElementById(\"cciframe\").src = '{$playerurl}'+ pn + '.html';</script>",$content);
	else $content = str_replace("{playpage:player}","<iframe id='cciframe' scrolling='no' frameborder='0' allowfullscreen></iframe><script>var pn=pn;var forcejx1=forcejx;var forcejx2=\"no\";var forcejx3=forcejx;if(forcejx1!=forcejx2 && contains(unforcejxARR,pn)==false){pn=forcejx3;}else{pn=pn;}document.getElementById(\"cciframe\").width = playerw;document.getElementById(\"cciframe\").height = playerh;document.getElementById(\"cciframe\").src = '{$playerurl}'+ pn + '.html';</script>",$content);
}
	}

	$content=$mainClassObj->parseIf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=str_replace("{playpage:link}",$contentLink,$content);
	$content=str_replace("{seacms:member}",front_member(),$content);
	 echo str_replace("{seacms:runinfo}",getRunTime($t1),$content) ;
}



function parsePlayPart($templatePath,$currentTypeId,$vtag)
{
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parsePlayPageSpecial($content);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);	
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseNewsList($content,$currentTypeId,$vtag,'');
	$content=$mainClassObj->parseTopicList($content);
	$content = str_replace("{seacms:currenttypeid}",$currentTypeId,$content);
	return $content;
}

function getPartName2($playData,$m,$n){
	$PartName=array();
	$playDataarray1=explode("$$$",$playData);
	if(strpos($playDataarray1[$m],"$$")>0){
		$playDataarray2=explode("$$",$playDataarray1[$m]);
		$PartName[0]=$playDataarray2[0];
			$playDataarray3=explode("#",$playDataarray2[1]);
			if(strpos($playDataarray3[$n],"$")>0){
				$playDataarray4=explode("$",$playDataarray3[$n]);
				$PartName[1]=$playDataarray4[0];
				$PartName[2]=$playDataarray4[1];
				$PartName[3]=$playDataarray4[2];
			}
	}

return $PartName;
}




function getLinkNum($playData,$m){
	//if(strpos($playData,"$$$")>0){
	$playDataarray1=explode("$$$",$playData);
	$playDataarray2=explode("$$",$playDataarray1[$m]);
	$playDataarray3=$playDataarray2[1];
	return count(explode('#',$playDataarray3))-1; 
}

function getLinkArr($playData,$m){
	//if(strpos($playData,"$$$")>0){
	$playDataarray1=explode("$$$",$playData);
	$playDataarray2=explode("$$",$playDataarray1[$m]);
	$playDataarray3=$playDataarray2[1];
	return explode('#',$playDataarray3);
}
?>