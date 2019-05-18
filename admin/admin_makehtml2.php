<?php
error_reporting(0);
@set_time_limit(0);
define('sea_ADMIN', preg_replace("|[/\\\]{1,}|",'/',dirname(__FILE__) ) );
require_once(sea_ADMIN."/../include/common.php");
require_once(sea_INC."/check.admin.php");
require_once(sea_ADMIN."/coplugins/Snoopy.class.php");
header("Cache-Control:private");

$pkey = $cfg_cookie_encode; //采集授权密码，请修改为自己的密码，防止被恶意采集
$ukey=$_GET['password'];
if($ukey != $pkey){die('PassWord ERR!');}

$days=1;

$dsql->safeCheck = false;
$dsql->SetLongLink();

//获得当前脚本名称，如果你的系统被禁用了$_SERVER变量，请自行更改这个选项
$EkNowurl = $s_scriptName = '';
$isUrlOpen = @ini_get("allow_url_fopen");
$EkNowurl = GetCurUrl();
$EkNowurls = explode('?',$EkNowurl);
$s_scriptName = $EkNowurls[0];
$Pirurl=getreferer();
if(empty($Pirurl)) $Pirurl=$EkNowurl;


$cuserLogin = new userLogin();



function makeTopicSelect($selectName,$strSelect,$topicId)
{
	global $dsql,$cfg_iscache;
	$sql="select id,name from sea_topic order by sort asc";
	if($cfg_iscache){
	$mycachefile=md5('array_Topic_Lists_all');
	setCache($mycachefile,$sql);
	$rows=getCache($mycachefile);
	}else{
	$rows=array();
	$dsql->SetQuery($sql);
	$dsql->Execute('al');
	while($rowr=$dsql->GetObject('al'))
	{
	$rows[]=$rowr;
	}
	unset($rowr);
	}
	$str = "<select name='".$selectName."' id='".$selectName."' >";
	if(!empty($strSelect)) $str .= "<option value='0'>".$strSelect."</option>";
	foreach($rows as $row)
	{
		if(!empty($topicId) && ($row->id==$topicId)){
		$str .= "<option value='".$row->id."' selected>$row->name</option>";
		}else{
		$str .= "<option value='".$row->id."'>$row->name</option>";
		}
	}
	$str .= "</select>";
	return $str;
}

function makeTypeOptionSelected($topId,$separateStr,$span="",$compareValue,$tptype=0)
{
	$tlist=getTypeListsOnCache($tptype);
	if ($topId!=0){$span.=$separateStr;}else{$span="";}

	foreach($tlist as $row)
	{
		
		if($row->upid==$topId)
		{
		
			if ($row->tid==$compareValue){$selectedStr=" selected";}else{$selectedStr="";}	
			echo "<option value='".$row->tid."'".$selectedStr.">".$span."&nbsp;|—".$row->tname."</option>";
			makeTypeOptionSelected($row->tid,$separateStr,$span,$compareValue,$tptype);
			
		}
	}
	if (!empty($span)){$span=substr($span,(strlen($span)-strlen($separateStr)));}
}
function makeTypeOptionSelected_Multiple($topId,$separateStr,$span="",$compareValue,$tptype=0)
{
	$tlist=getTypeListsOnCache($tptype);
	if ($topId!=0){$span.=$separateStr;}else{$span="";}
	$ids_arr = split('[,]',$compareValue);
	foreach($tlist as $row)
	{
		
		if($row->upid==$topId)
		{
			
			for($i=0;$i<count($ids_arr);$i++)
			{
				if ($row->tid==$ids_arr[$i]){
					$selectedStr=" checked=checked";
					break;
					}
					else
					{
					$selectedStr="";
					}
			}
			
			echo "<input name=v_type_extra[] type=checkbox value=".$row->tid." ".$selectedStr.">".$row->tname."&nbsp;&nbsp;";
			makeTypeOptionSelected_Multiple($row->tid,$separateStr,$span,$compareValue,$tptype);
			
		}
	}
	if (!empty($span)){$span=substr($span,(strlen($span)-strlen($separateStr)));}
	
}


function makeTypeOptionSelected_Jq($topId,$separateStr,$span="",$compareValue,$tptype=0)
{
	$tlist=getjqTypeListsOnCache($tptype);
	if ($topId!=0){$span.=$separateStr;}else{$span="";}
	$ids_arr = split('[,]',$compareValue);
	foreach($tlist as $row)
	{
		
		if($row->upid==$topId)
		{
			
			for($i=0;$i<count($ids_arr);$i++)
			{
				if ($row->tname==$ids_arr[$i]){
					$selectedStr=" checked=checked";
					break;
					}
					else
					{
					$selectedStr="";
					}
			}
			
			echo "<input name=v_jqtype_extra[] type=checkbox value=".$row->tname." ".$selectedStr.">".$row->tname."&nbsp;&nbsp;";
			makeTypeOptionSelected_Jq($row->tid,$separateStr,$span,$compareValue,$tptype);
			
		}
	}
	if (!empty($span)){$span=substr($span,(strlen($span)-strlen($separateStr)));}
	
}

function getreferer()
{
	if(isset($_SERVER['HTTP_REFERER']))
	$refurl=$_SERVER['HTTP_REFERER'];
	$url='';
	if(!empty($refurl)){
		$refurlar=explode('/',$refurl);
		$i=count($refurlar)-1;
		$url=$refurlar[$i];
	}
	return $url;
}

function downSinglePic($picUrl,$vid,$vname,$filePath,$infotype)
{
	$spanstr=empty($infotype) ? "" : "<br/>";
	if(empty($picUrl) || substr($picUrl,0,7)!='http://'){
		echo "数据<font color=red>".$vname."</font>的图片路径错误1,请检查图片地址是否有效  ".$spanstr;
		return false;
	}
	$fileext=getFileFormat($filePath);
	$ps=split("/",$picUrl);
	$filename=urldecode($ps[count($ps)-1]);
	if ($fileext!="" && strpos("|.jpg|.gif|.png|.bmp|.jpeg|",strtolower($fileext))>0){
		if(!(strpos($picUrl,".ykimg.com/")>0)){
			if(empty($filename) || strpos($filename,".")==0){
				echo "数据<font color=red>".$vname."</font>的图片路径错误2,请检查图片地址是否有效 ".$spanstr;
				return false;
			}
		}
		$imgStream=getRemoteContent(substr($picUrl,0,strrpos($picUrl,'/')+1).str_replace('+','%20',urlencode($filename)));
		$createStreamFileFlag=createStreamFile($imgStream,$filePath);
		if($createStreamFileFlag){
			$streamLen=strlen($imgStream);
			if($streamLen<2048){
				echo "数据<font color=red>".$vname."</font>的图片下载发生错误5,请检查图片地址是否有效  ".$spanstr;
				return false;
			}else{
				return number_format($streamLen/1024,2);
			}
		}else{
			if(empty($vid)){
				echo "数据<font color=red>".$vname."</font>的图片下载发生错误3,请检查图片地址是否有效  ".$spanstr;
				return false;
			}else{
				echo "数据<font color=red>".$vname."</font>的图片下载发生错误4,id为<font color=red>".$vid."</font>,请检查图片地址是否有效  ".$spanstr;
				return false;
			}
		}
	}else{
		echo "数据<font color=red>".$vname."</font>的图片下载发生错误6,请检查图片地址是否有效  ".$spanstr;
		return false;
	}
}

function uploadftp($picpath,$picfile,$v_name,$picUrl)
{
	require_once(sea_INC."/ftp.class.php");
	$Newpicpath = str_replace("../","",$picpath);
	$ftp = new AppFtp($GLOBALS['app_ftphost'] ,$GLOBALS['app_ftpuser'] ,$GLOBALS['app_ftppass'] , $GLOBALS['app_ftpport'] , $GLOBALS['app_ftpdir']);
	if( $ftp->ftpStatus == 1){;
		$localfile= sea_ROOT .'/'. $Newpicpath . $picfile;
		$remotefile= $GLOBALS['app_ftpdir'].$Newpicpath . $picfile;
		$ftp -> mkdirs( $GLOBALS['app_ftpdir'].$Newpicpath );
		$ftpput = $ftp->put($localfile, $remotefile);
		if(!$ftpput){
			echo "数据$v_name上传图片到FTP远程服务器失败!本地地址$picUrl<br>";
			return false;
		}
		$ftp->bye();
		if ($GLOBALS['app_ftpdel']==1){
			unlink( $picpath . $picfile );
		}
	}
	else{
		echo $ftp->ftpStatusDes;return false;
	}
}

function uploadftp2($picUrl)
{
	require_once(sea_INC."/ftp.class.php");
	$ftp = new AppFtp($GLOBALS['app_ftphost'] ,$GLOBALS['app_ftpuser'] ,$GLOBALS['app_ftppass'] , $GLOBALS['app_ftpport'] , $GLOBALS['app_ftpdir']);
	$picpath = dirname($picUrl).'/';
	if( $ftp->ftpStatus == 1){;
		$localfile= sea_ROOT .'/'. $picUrl;
		$remotefile= $GLOBALS['app_ftpdir'].$picUrl;
		$ftp -> mkdirs( $GLOBALS['app_ftpdir'].$picpath );
		$ftpput = $ftp->put($localfile, $remotefile);
		if(!$ftpput){
			return false;
		}
		$ftp->bye();
		if ($GLOBALS['app_ftpdel']==1){
			unlink( sea_ROOT .'/'. $picUrl );
		}
		return true;
	}
	else{
		echo $ftp->ftpStatusDes;return false;
	}
}

function cache_clear($dir) {
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

function getFolderList($cDir)
{
	$dh = dir($cDir);
	$k=0;
	while($filename=$dh->read())
	{
		if($filename=='.' || $filename=='..' || m_ereg("\.inc",$filename)) continue;
		$filetime = filemtime($cDir.'/'.$filename);
		$f[$k]['filetime'] = isCurrentDay($filetime);
		$f[$k]['filename']=$filename;
		if(!m_ereg("\.",$filename)){
			$f[$k]['fileinfo']="文件夹";
		}else{
			$f[$k]['fileinfo']=getTemplateType($filename);
		}
		if(!m_ereg("\.",$filename)){
			$f[$k]['filesize']=getRealSize(getDirSize($cDir.'/'.$filename));
		}else{
			$f[$k]['filesize']=getRealSize(filesize($cDir.'/'.$filename));
		}
		$f[$k]['fileicon']=viewIcon($filename);
		$f[$k]['filetype']=getFileType($filename);
		$k++;
	}
	return $f;
}

function getFileType($filedir)
{
	if(!m_ereg("\.",$filedir)){
		return "folder";
	}else{
		$filetype=strtolower(getfileextend($filedir));
		$imgFileStr=".jpg|.jpeg|.gif|.bmp|.png";
		$pageFileStr =".html|.htm|.js|.css|.txt";
		if(strpos($imgFileStr,$filetype)>0) return "img";
		if(strpos($pageFileStr,$filetype)>0) return "txt";
	}
}

function viewIcon($filename)
{
	if(!m_ereg("\.",$filename)){
		return "folder";
	}else{
		$fileType=strtolower(getfileextend($filename));
		if($fileType=="js" || $fileType=="css"){
			return $fileType;
		}else{
			if ($fileType=="jpg" || $fileType=="jpeg") return "jpg";
			if ($fileType=="htm" || $fileType=="html" || $fileType=="shtml") return "html";
			if ($fileType=="gif" || $fileType=="png") return "gif";
			return "file";
		}
	}
}

function getfileextend($filename)
{ 
	$extend =explode(".", $filename);
	$va=count($extend)-1;
	return $extend[$va];
}

/*老函数，去除
//获取默认文件说明信息
function GetInfoArray($filename)
{
	$arrs = array();
	$dlist = file($filename);
	foreach($dlist as $d)
	{
		$d = trim($d);
		if($d!='')
		{
			list($dname,$info) = explode(',',$d);
			$arrs[$dname] = $info;
		}
	}
	return $arrs;
}
*/

function getDirSize($dir)
{ 
	$handle = opendir($dir);
	$sizeResult = '';
	while (false!==($FolderOrFile = readdir($handle)))
	{ 
		if($FolderOrFile != "." && $FolderOrFile != "..") 
		{ 
			if(is_dir("$dir/$FolderOrFile"))
			{ 
				$sizeResult += getDirSize("$dir/$FolderOrFile"); 
			}
			else
			{ 
				$sizeResult += filesize("$dir/$FolderOrFile"); 
			}
		}    
	}
	closedir($handle);
	return $sizeResult;
}

// 单位自动转换函数
function getRealSize($size)
{ 
	$kb = 1024;         // Kilobyte
	$mb = 1024 * $kb;   // Megabyte
	$gb = 1024 * $mb;   // Gigabyte
	$tb = 1024 * $gb;   // Terabyte
	if($size == 0){
		return "0 B";
	}
	else if($size < $mb)
	{ 
     	return round($size/$kb,2)." K";
	}
	else if($size < $gb)
	{ 
    	return round($size/$mb,2)." M";
	}
	else if($size < $tb)
	{ 
    	return round($size/$gb,2)." G";
	}
	else
	{ 
     	return round($size/$tb,2)." T";
	}
}

//修改 by 心情
function getTemplateType($filename){
	switch(strtolower($filename)){
		case 'index.html':
			$getTemplateType="首页模版";
			break;
		case "head.html":
			$getTemplateType="模板头文件";
			break;
		case "foot.html":
			$getTemplateType="模板尾文件";
			break;
		case "play.html":
			$getTemplateType="播放页模板";
			break;
		case "map.html":
			$getTemplateType="HTML地图页模板";
			break;
		case "search.html":
			$getTemplateType="搜索页模板";
			break;
		case "topic.html":
			$getTemplateType="专题页模板";
			break;
		case "topicindex.html":
			$getTemplateType="专题首页模板";
			break;
		case "comment.html":
			$getTemplateType="评论页模板";
			break;
		case "channel.html":
			$getTemplateType="分类页模板";
			break;
		case "openplay.html":
			$getTemplateType="播放页模板(弹窗模式)";
			break;
		case "content.html":
			$getTemplateType="内容页模板";
			break;
		case "gbook.html":
			$getTemplateType="留言本页面模板";
			break;
		default:
			if(stristr($filename,'.gif') or stristr($filename,'.jpg') or stristr($filename,'.png')){
				$getTemplateType="图片文件";
				}
			elseif(stristr($filename,'.css')){
				 $getTemplateType="样式文件";
				}
			elseif(stristr($filename,'self')){
				 $getTemplateType="自定义模板";
				}
			elseif(stristr($filename,'http.txt')){
				 $getTemplateType="伪静态配置模板";
				}
			elseif(stristr($filename,'.html') or stristr($filename,'.htm')){
				 $getTemplateType="静态页面文件";
				}
			elseif(stristr($filename,'.js')){
				$getTemplateType="脚本文件";
				}	
			else{
				$getTemplateType="其它文件";
				}
	}
	return $getTemplateType;
}

function viewFoot()
{
	global $dsql,$starttime;
	echo "<div align=center>";
	$starttime = explode(' ', $starttime);
	$endtime = explode(' ', microtime()); 
	echo "</div><div class=\"bottom\"><table width=\"100%\" cellspacing=\"5\"><tr><td align=\"center\">本页面用时".
	($endtime[0]+($endtime[1]-$starttime[1])-$starttime[0])."秒,共执行".$dsql->QueryTimes()."次数据查询</td></tr><tr><td align=\"center\"><a target=\"_blank\" href=\"http://www.seacms.net/\">Powered By SeaCms</a></td></tr></table></div>\n</body>\n</html>";
}

function viewHead($str)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<TITLE>海洋影视管理系统</TITLE>
<link href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<?php }
ob_implicit_flush();
set_time_limit(0);
require_once(sea_INC.'/main2.class.php');
//CheckPurview(); //如需要禁止编辑员生成权限，去掉此注释即可。
if(empty($action))
{
	$action = '';
}

$id = empty($id) ? 0 : intval($id);

if($action=="index")
{
	echoHead();
	echo makeIndex($by);
	echo '<br>执行完毕:';
	echo date("Y-m-d H:i:s");
}
elseif($action=="map")
{
	echoHead();
	echo makeAllmovie($by);
	echoFoot();
}
elseif($action=="js")
{
	echoHead();
	makeVideoJs($by);
	echoFoot();
}
elseif($action=="site")
{
	checkRunMode();
	echo "<script language='javascript'>self.location='?action=allcontent&action2=allcontent&action3=".$action3."&by=".$by."';</script>";
}
elseif($action=="newssite")
{
	checkNewsRunMode();
	echo "<script language='javascript'>self.location='?action=allnewscontent&action2=allnewscontent&action3=".$action3."&by=".$by."';</script>";
}
elseif($action=="day")
{
	checkRunMode();
	echoHead();
	makeDay();
}
elseif($action=="newsday")
{
	checkNewsRunMode();
	echoHead();
	makeNewsDay();
	echoFoot();
}
elseif($action=="single")
{
	checkRunMode();
	echoHead();
	echo makeContentById($id);
	echoFoot();
	if(empty($from)){
		echo "<script>"; 
		echo "location.href='$Pirurl'"; 
		echo "</script>"; 		
	}else{
		echo "<script>"; 
		echo "location.href='$from'"; 
		echo "</script>"; 		
	}
	exit();
}
elseif($action=="selected")
{
	checkRunMode();
	if(empty($e_id))
	{
		showMsg('请选择要生成的影片','-1');
	}
	echoHead();
	foreach($e_id as $id)
	{
		echo makeContentById($id);
	}
	if(empty($from)){
		alertMsg("生成完毕！",$Pirurl);
	}else{
		alertMsg("生成完毕！",$from);
	}
	echoFoot();
}
elseif($action=="singleNews")
{
	checkNewsRunMode();
	echoHead();
	makeArticleById($id);
	echoFoot();
	if(empty($from)){
		echo "<script>"; 
		echo "location.href='$Pirurl'"; 
		echo "</script>-->"; 		
	}else{
		echo "<script>"; 
		echo "location.href='$from'"; 
		echo "</script>"; 		
	}
	exit();
}
elseif($action=="selectednews")
{
	checkNewsRunMode();
	if(empty($e_id))
	{
		showMsg('请选择要生成的新闻','-1');
	}
	echoHead();
	foreach($e_id as $id)
	{
		makeArticleById($id);
	}
	if(empty($from)){
		alertMsg("生成完毕！",$Pirurl);
	}else{
		alertMsg("生成完毕！",$from);
	}
	echoFoot();
}
elseif($action=="content")
{
	checkRunMode();
	if(empty($channel))
	{
		ShowMsg("请选择分类！",-1);
		exit();
	}
	echoHead();
	makeContentByChannel($channel,false);
	echoFoot();
}
elseif($action=="newscontent")
{
	checkNewsRunMode();
	if(empty($channel))
	{
		ShowMsg("请选择分类！",-1);
		exit();
	}
	echoHead();
	makeArticleByChannel($channel,false);
	echoFoot();
}
elseif($action=="allcontent")
{
	checkRunMode();
	$makenoncreate=isset($makenoncreate)?$makenoncreate:0;
	$curTypeIndex=$index;
	$typeIdArray = getTypeIdArrayBySort(0);
	$typeIdArrayLen = count($typeIdArray);
	if (empty($curTypeIndex)){
		$curTypeIndex=0;
	}else{
		if(intval($curTypeIndex)>intval($typeIdArrayLen-1)){
			if (empty($action3)){
				alertMsg ("生成全部内容页完成","");
				exit();
			}elseif($action3=="site"){
				echo "<script language='javascript'>self.location='?action=allchannel&action3=".$action3."';</script>";
				exit();
			}
		}
	}
	$typeId = $typeIdArray[$curTypeIndex];
	if(empty($typeId)) {
		exit("分类丢失"); 
	}else {
		echoHead();
		makeContentByChannel($typeId,false,$makenoncreate);
		echoFoot();
	}
}
elseif($action=="allnewscontent")
{
	checkNewsRunMode();
	$curTypeIndex=$index;
	$typeIdArray = getTypeIdArrayBySort(0,1);
	$typeIdArrayLen = count($typeIdArray);
	if (empty($curTypeIndex)){
		$curTypeIndex=0;
	}else{
		if(intval($curTypeIndex)>intval($typeIdArrayLen-1)){
			if (empty($action3)){
				alertMsg ("生成全部内容页完成","");
				exit();
			}elseif($action3=="site"){
				echo "<script language='javascript'>self.location='?action=allpart&action3=".$action3."';</script>";
				exit();
			}
		}
	}
	$typeId = $typeIdArray[$curTypeIndex];
	if(empty($typeId)){
		exit("分类丢失"); 
	}
	else {
		echoHead();
		makeArticleByChannel($typeId,false);
		echoFoot();
	}
}
elseif($action=="daysview")
{
	checkRunMode();
	$ntime = gmmktime(0, 0, 0, gmdate('m'), gmdate('d'), gmdate('Y'));
	$limitday = $ntime - ($days * 24 * 3600);
	$sql = "SELECT v_id FROM sea_data where v_addtime>".$limitday;
	$dsql->SetQuery($sql);
	$dsql->Execute('makedaysview');
	echoHead();
	while($row=$dsql->GetObject('makedaysview'))
	{
		echo makeContentById($row->v_id);
		@ob_flush();
		@flush();
	}
	unset($row);
	echo '<br>执行完毕:';
	echo date("Y-m-d H:i:s");
}
elseif($action=="newsdaysview")
{
	checkNewsRunMode();
	$ntime = gmmktime(0, 0, 0, gmdate('m'), gmdate('d'), gmdate('Y'));
	$limitday = $ntime - ($days * 24 * 3600);
	$sql = "SELECT n_id FROM sea_news where n_addtime>".$limitday;
	$dsql->SetQuery($sql);
	$dsql->Execute('makenewsdaysview');
	echoHead();
	while($row=$dsql->GetObject('makenewsdaysview'))
	{
		makeArticleById($row->n_id);
		@ob_flush();
		@flush();
	}
	unset($row);
	echoFoot();
}
elseif($action=="channel")
{
	if($page<=1) $page =1 ;
	checkRunMode();
	if(empty($channel))
	{
		exit("请选择分类！");
	}
	echoHead();
	makeChannelById($channel);
	echoFoot();
}
elseif($action=="newschannel")
{
	if($page<=1) $page =1 ;
	checkNewsRunMode();
	if(empty($channel))
	{
		exit("请选择分类！");
	}
	echoHead();
	makeNewsChannelById($channel);
	echoFoot();
}
elseif($action=="allchannel")
{
	checkRunMode();
	$curTypeIndex=$index;
	$typeIdArray = getTypeIdArrayBySort(0);
	$typeIdArrayLen = count($typeIdArray);
	if (empty($curTypeIndex)){
		$curTypeIndex=0;
	}else{
		if(intval($curTypeIndex)>intval($typeIdArrayLen-1)){
			if (empty($action3)){
				echo '<br>执行完毕:';
				echo date("Y-m-d H:i:s");
				exit();
			}elseif($action3=="site"){
				echoHead();
				echo makeIndex();
				echo makeAllmovie();
				echo '<br>执行完毕:';
				echo date("Y-m-d H:i:s");
				exit();
			}
		}
	}
	$typeId = $typeIdArray[$curTypeIndex];
	if(empty($typeId)){
		exit("分类丢失");
	}else{
		echoHead();
		makeChannelById($typeId);
		echo '<br>执行完毕:';
		echo date("Y-m-d H:i:s");
	}
}
elseif($action=="allpart")
{
	checkNewsRunMode();
	$curTypeIndex=$index;
	$typeIdArray = getTypeIdArrayBySort(0,1);
	$typeIdArrayLen = count($typeIdArray);
	if (empty($curTypeIndex)){
		$curTypeIndex=0;
	}else{
		if(intval($curTypeIndex)>intval($typeIdArrayLen-1)){
			if (empty($action3)){
				alertMsg ("生成所有栏目全部搞定","");
				exit();
			}elseif($action3=="site"){
				echoHead();
				echo makeIndex('news');
				echo makeAllmovie('news');
				echoFoot();
				alertMsg ("一键生成全部搞定","");
				exit();
			}
		}
	}
	$typeId = $typeIdArray[$curTypeIndex];
	if(empty($typeId)){
		exit("分类丢失");
	}else{
		echoHead();
		makeNewsChannelById($typeId);
		echoPartSuspend(($curTypeIndex+1),$action3);
		echoFoot();
	}
}
elseif($action=="lengthchannel")
{
	checkRunMode();
	if(empty($channel))
	{
		exit("请选择分类！");
	}
	if (empty($startpage)){
		exit("请填写起始页");
	}elseif(!is_numeric($startpage)){
		exit("请正确填写起始页");
	}
	if (empty($endpage)){
		exit("请填写结束页");
	}elseif(!is_numeric($endpage)){
		exit("请正确填写结束页");
	}
	$startpage = intval($startpage);
	$endpage = intval($endpage);
	if ($startpage<=0) exit("您输入的页数小于0");
	if ($startpage>$endpage) exit("您输入的结束页小于开始页");
	echoHead();
	makeLengthChannelById($channel,$startpage,$endpage);
	echoFoot();
}
elseif($action=="lengthpart")
{
	checkNewsRunMode();
	if(empty($channel))
	{
		exit("请选择分类！");
	}
	if (empty($startpage)){
		exit("请填写起始页");
	}elseif(!is_numeric($startpage)){
		exit("请正确填写起始页");
	}
	if (empty($endpage)){
		exit("请填写结束页");
	}elseif(!is_numeric($endpage)){
		exit("请正确填写结束页");
	}
	$startpage = intval($startpage);
	$endpage = intval($endpage);
	if ($startpage<=0) exit("您输入的页数小于0");
	if ($startpage>$endpage) exit("您输入的结束页小于开始页");
	echoHead();
	makeLengthPartById($channel,$startpage,$endpage);
	echoFoot();
}
elseif($action=="channelbyids")
{
	checkRunMode();
	echoHead();
	makeChannelByIDS();
	echoFoot();
}
elseif($action=="partbyids")
{
	checkNewsRunMode();
	echoHead();
	makePartByIDS();
	echoFoot();
}
elseif($action=="topic")
{
	checkRunMode();
	if(empty($topic))
	{
		exit("请选择专题!");
	}
	echoHead();
	makeTopicById($topic);
	makeTopicIndex();
	echoFoot();
}
elseif($action=="alltopic")
{
	checkRunMode();
	echoHead();
	makeAllTopic();
	makeTopicIndex();
	echoFoot();
}
elseif($action=="custom")
{
	$customtemplate = $custom;
	if (empty($customtemplate)) exit("请选择模板");
	echoHead();
	makeCustomInfo($customtemplate);
	echoFoot();

}
elseif($action=="customs")
{
	$customtemplate = $custom;
	if (empty($customtemplate)) exit("请选择模板");
	echoHead();
	for($i=0;$i<count($customtemplate);$i++)
	{
		makeCustomInfo($customtemplate[$i]);
	}
	echoFoot();	
}
elseif($action=="allcustom")
{
	$templetdird = $cfg_basedir."templets/".$cfg_df_style."/".$cfg_df_html."/";
	$dh = dir($templetdird);
	echoHead();
	while($filename=$dh->read())
	{
	if(strpos($filename,"elf_")>0) makeCustomInfo($filename);
	}
	echoFoot();

}
elseif($action=="baidu")
{
	echoHead();
	echo makeBaidu();
	echoFoot();
}
elseif($action=="google")
{
	echoHead();
	echo makeGoogle();
	echoFoot();
}
elseif($action=="rss")
{
	echoHead();
	echo makeRss();
	echoFoot();
}
elseif($action=="baidux")
{
	echoHead();
	echo makeBaidux();
	echoFoot();
}
else
{
include(sea_ADMIN.'/templets/admin_makehtml.htm');
exit();
}
?>