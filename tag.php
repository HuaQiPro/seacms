<?php 

error_reporting(0);
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
	$str = str_ireplace('if', "i", $str);
	$str = str_ireplace('0x', "", $str);
    $str = str_ireplace('@', "", $str);	
	$str = str_ireplace('--', "", $str);
	$str = str_ireplace('/*', "", $str);
	$str = str_ireplace('*/', "", $str);
	$str = str_ireplace('*!', "", $str);
	$str = str_ireplace('//', "", $str);
	$str = str_ireplace('\\', "", $str);
	$str = str_ireplace('#', "", $str);
	$str = str_ireplace('%00', "", $str);
	$str = str_ireplace('0x', "", $str);
	$str = str_ireplace('%0b', "", $str);
	$str = str_ireplace('%23', "", $str);
	$str = str_ireplace('%26', "", $str);
	$str = str_ireplace('%7c', "", $str);
	$str = str_ireplace('hex', "he", $str);
	$str = str_ireplace('file_', "fil", $str);
	$str = str_ireplace('updatexml', "update", $str);
	$str = str_ireplace('extractvalue', "extract", $str);
	$str = str_ireplace('union', "unio", $str);
	$str = str_ireplace('benchmark', "bench", $str);
	$str = str_ireplace('sleep', "slee", $str);
	$str = str_ireplace('load_file', "", $str);
	$str = str_ireplace('outfile', "out", $str);
	$str = str_ireplace('ascii', "asc", $str);	
	$str = str_ireplace('char', "cha", $str);
	$str = str_ireplace('chr', "ch", $str);	
	$str = str_ireplace('substr', "sub", $str);
	$str = str_ireplace('substring', "sub", $str);
	$str = str_ireplace('script', "scri", $str);
	$str = str_ireplace('frame', "fra", $str);
	$str = str_ireplace('information_schema', "infor", $str);
	$str = str_ireplace('exp', "ex", $str);
	$str = str_ireplace('information_schema', "infor", $str);
	$str = str_ireplace('GeometryCollection', "Geomet", $str);
	$str = str_ireplace('polygon', "poly", $str);
	$str = str_ireplace('multipoint', "multi", $str);
	$str = str_ireplace('multilinestring', "multi", $str);
	$str = str_ireplace('linestring', "lines", $str);
	$str = str_ireplace('multipolygon', "multi", $str);
	$str = str_ireplace('base64', "bas", $str);
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
require_once(sea_INC."/main.class.php");

$page = (isset($page) && is_numeric($page)) ? $page : 1;

if(!isset($tag)) $tag = '';

$tag = FilterSearch(stripslashes($tag));
$tag = addslashes(cn_substr($tag,20));
$tag = RemoveXSS($tag);
if($tag=='')
{
	ShowMsg('标签不能为空！','-1','0',$cfg_search_time);
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

$key = array('{','}','(',')','=',',',';','"','<','>','script','iframe','@','&','%','$','#','*');
if(check_str($tag,$key)){ShowMsg('请勿输入危险字符！','index.php','0',$cfg_search_time*1000);exit;}

echoTagPage();

function echoTagPage()
{
	global $dsql,$cfg_iscache,$mainClassObj,$page,$t1,$cfg_search_time,$searchtype,$tag;
	if($cfg_search_time) checkSearchTimes($cfg_search_time);
	$searchTemplatePath = "/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/tag.html";
	$pSize = getPageSizeOnCache($searchTemplatePath,"search","");
	if (empty($pSize)) $pSize=12;
	$whereStr=" where v_tag like '%$tag%'";
	$sql="select vids from sea_tags where tag='$tag'";
	$row = $dsql->GetOne($sql);
	if(is_array($row))
	{
		$vids=$row['vids'];
		$TotalResult = count(explode(',', $row['vids']));
	}
	else
	{
		$TotalResult = 0;
	}
	$pCount = ceil($TotalResult/$pSize);
	$cacheName="parse_tag_";
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
	$tempStr = str_replace("{seacms:tag}",$tag,$tempStr);
	$tempStr = str_replace("{seacms:tagnum}",$TotalResult,$tempStr);
	$content=$tempStr;
	$content=$mainClassObj->parsePageList($content,$vids,$page,$pCount,$TotalResult,"tag");
	$content=replaceCurrentTypeId($content,-444);
	$content=$mainClassObj->parseIf($content);
	$content=str_replace("{seacms:member}",front_member(),$content);
	$searchPageStr = $content;
	echo str_replace("{seacms:runinfo}",getRunTime($t1),$searchPageStr) ;
}

function parseSearchPart($templatePath)
{ 
	global $mainClassObj;
	$content=loadFile(sea_ROOT.$templatePath);
	$content=$mainClassObj->parseTopAndFoot($content);
	$content=$mainClassObj->parseSelf($content);
	$content=$mainClassObj->parseGlobal($content);
	$content=$mainClassObj->parseMenuList($content,"",$currentTypeId);
	$content=$mainClassObj->parseVideoList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseNewsList($content,$currentTypeId,'','');
	$content=$mainClassObj->parseTopicList($content);
	return $content;
}

function checkSearchTimes($searchtime)
{
	if(GetCookie("ssea2_tag")=="ok")
	{
		ShowMsg('搜索限制为'.$searchtime.'秒一次','-1','0',$cfg_search_time);
		exit;
	}else{
		PutCookie("ssea2_tag","ok",$searchtime);
	}
	
}
?>