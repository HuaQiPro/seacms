<?php 
error_reporting(0);
session_start();
function lib_replace_end_tag($str)  
{  
if (empty($str)) return false;  
	$str = htmlspecialchars($str);  
	$str = str_ireplace('/', "", $str);
	$str = str_ireplace('[', "", $str);
	$str = str_ireplace(']', "", $str);	
	$str = str_ireplace('>', "", $str);  
	$str = str_ireplace('<', "", $str);  
	$str = str_ireplace('?', "", $str);
	$str = str_ireplace('&', "", $str);
	$str = str_ireplace('|', "", $str);
	$str = str_ireplace('(', "", $str);
	$str = str_ireplace(')', "", $str);
	$str = str_ireplace('{', "", $str);
	$str = str_ireplace('}', "", $str);
	$str = str_ireplace('%', "", $str);
	$str = str_ireplace('=', "", $str);
	$str = str_ireplace(',', "", $str);
	$str = str_ireplace(':', "", $str);
	$str = str_ireplace(';', "", $str);
	$str = str_ireplace('*', "", $str);
    $str = str_ireplace('@', "", $str);	
	$str = str_ireplace('--', "", $str);
	$str = str_ireplace('//', "", $str);
	$str = str_ireplace('\\', "", $str);
return $str;
} 
$_GET = stripslashes_array($_GET);  
$_POST = stripslashes_array($_POST);  
$_COOKIE = stripslashes_array($_COOKIE);  
$_REQUEST = stripslashes_array($_REQUEST); 
$GLOBALS = stripslashes_array($GLOBALS); 
$_SERVER = stripslashes_array($_SERVER); 
$_SESSION = stripslashes_array($_SESSION); 
$_FILES = stripslashes_array($_FILES); 
$_ENV = stripslashes_array($_ENV); 
$HTTP_RAW_POST_DATA = stripslashes_array($HTTP_RAW_POST_DATA); 
$http_response_header = stripslashes_array($http_response_header);  
  
function stripslashes_array(&$array) {  
while(list($key,$var) = each($array)) {  
    if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {  
      if (is_string($var)) {  
         $array[$key] = lib_replace_end_tag($var);  
}  
if (is_array($var))  {  
     $array[$key] = stripslashes_array($var);  
}  
}  
}  
return $array;  
} 



require_once("include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/main.class.php");
require_once(sea_INC."/splitword.class.php");

if($cfg_search_type =='0')
{
	ShowMsg("搜索系统关闭！","index.php","0",$cfg_search_time*1000);
	exit();
}
if($cfg_search_type =='2')
{
	include_once("include/scheck.php");
	$cfg_search_type ='-1';
}

$page = (isset($page) && is_numeric($page)) ? $page : 1;
$searchtype=$cfg_search_type;
$searchword = isset($searchword) && $searchword ? $searchword:'';
$searchword = FilterSearch(stripslashes($searchword));
$searchword = addslashes(cn_substr($searchword,20));
$searchword = RemoveXSS($searchword);
$searchword = trim($searchword);
if($cfg_notallowsstr !='' && $searchtype!=5)
{
	$sstr=m_eregi_replace($cfg_notallowsstr,'s-e-a-c-m-s',$searchword);
	$sarray=explode('s-e-a-c-m-s', $sstr);
	$r = null;
	array_walk($sarray, function($v) use (&$r){$r[$v] = strlen($v);});
    $searchword = array_search(max($r), $r);
}
if($searchword=='')
{
	ShowMsg('关键字不能为空！','-1','0',$cfg_search_time*1000);
	exit();
}

function check_str($str,$key){
 foreach($key as $v){
  if(strpos($str,$v)>-1){
   return true;
  }
 }
 return false;
}

$key = array('{','}','(',')','=',',',';','"','<','>','<script','<iframe','@','&','%','$','#','*');
if(check_str($searchword,$key)){ShowMsg('请勿输入危险字符！','index.php','0',$cfg_search_time*1000);exit;}

echoSearchPage();

function echoSearchPage()
{
	global $dsql,$cfg_iscache,$mainClassObj,$page,$t1,$cfg_search_time,$searchword,$searchtype,$cfg_issearchlog;
	if($cfg_search_time) checkSearchTimes($cfg_search_time);
	$searchTemplatePath = "/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/newssearch.html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$searchTemplatePath = "/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/newssearch.html";}
	$pSize = getPageSizeOnCache($searchTemplatePath,"newssearch","");
	if (empty($pSize)) $pSize=12;
	switch (intval($searchtype)) {
		case -1:
			$whereStr=" where n_recycled=0 and (n_title like '%$searchword%' or n_keyword like '%$searchword%' or n_author like '%$searchword%' or n_from like '%$searchword%')";
		break;
		case 1:
			$whereStr=" where n_recycled=0 and n_title like '%$searchword%'";	
		break;

	}
	$sql="select count(*) as dd from sea_news ".$whereStr;
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
	$cacheName="parse_searchnews_".$GLOBALS['cfg_mskin'].$GLOBALS['isMobile'];
	if($cfg_iscache){
		if(chkFileCache($cacheName)){
			$content = getFileCache($cacheName);
		}else{
			$content = parseSearchPart($searchTemplatePath);
			setFileCache($cacheName,$content);
		}
	}else{
			$content = parseSearchPart($searchTemplatePath);
	}
	$tempStr = $content;
	$tempStr = str_replace("{seacms:newssearchword}",$searchword,$tempStr);
	$tempStr = str_replace("{seacms:newssearchnum}",$TotalResult,$tempStr);
	$tempStr = str_replace("{seacms:page}",$page,$tempStr);
	$content=$tempStr;
	$content=$mainClassObj->parseNewsPageList($content,"",$page,$pCount,"newssearch");
	$content=replaceCurrentTypeId($content,-444);
	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{seacms:member}",front_member(),$content);
	$searchPageStr = $content;
	if($cfg_issearchlog=="y"){GetKeywords($searchword,$TotalResult);}
	echo str_replace("{seacms:runinfo}",getRunTime($t1),$searchPageStr) ;
}

function parseSearchPart($templatePath)
{
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=$mainClassObj->parseAreaList($content);
	$content=$mainClassObj->parseHistory($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"");
	$content=$mainClassObj->parseVideoList($content,'','','');
	$content=$mainClassObj->parseNewsList($content,'','','');
	$content=$mainClassObj->parseNewsAreaList($content);
	$content=$mainClassObj->parseTopicList($content);
	return $content;
}

function checkSearchTimes($searchtime)
{
	if(GetCookie("ssea2_search")=="ok")
	{
		ShowMsg('搜索限制为'.$searchtime.'秒一次','-1','0');
		//PutCookie("ssea2_search","ok",$searchtime);
		exit;
	}else{
		PutCookie("ssea2_search","ok",$searchtime);
	}
	
}

//获得关键字的分词结果，并保存到数据库 
function GetKeywords($keyword,$TotalResult)
{
	global $dsql;
	$keyword = cn_substr($keyword,50);
	if($keyword=="" OR empty($keyword)){return $keywords;}
	$row = $dsql->GetOne("Select spwords From `sea_search_keywords` where keyword='".addslashes($keyword)."'; ");
	if(!is_array($row))
	{
		if(strlen($keyword)>7)
		{
			$sp = new SplitWord();
			$keywords = $sp->SplitRMM($keyword);
			$sp->Clear();
			$keywords = m_ereg_replace("[ ]{1,}"," ",trim($keywords));
		}
		else
		{
			$keywords = $keyword;
		}
		$inquery = "INSERT INTO `sea_search_keywords`(`keyword`,`spwords`,`count`,`result`,`lasttime`,`tid`)
  VALUES ('".addslashes($keyword)."', '".addslashes($keywords)."', '1', '$TotalResult', '".time()."','1'); ";
		$dsql->ExecuteNoneQuery($inquery);
	}
	else
	{
		$dsql->ExecuteNoneQuery("Update `sea_search_keywords` set count=count+1,tid='1',result='$TotalResult',lasttime='".time()."' where keyword='".addslashes($keyword)."'; ");
		$keywords = $row['spwords'];
	}
	return $keywords;
}
?>