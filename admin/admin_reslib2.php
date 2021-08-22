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
<?php  }?>

<?php 
@set_time_limit(0);
ob_implicit_flush();

require_once(sea_DATA."/mark/inc_photowatermark_config.php");

if(empty($action))
{
	$action = '';
}
if(!empty($ac))
{
	$action = $ac;	
}
if(!empty($rid))
{
	$ressite = $rid;	
}
$rid=strip_tags($rid);
$isref=1;//是否使用sock采集 0为不是用,1为使用
$gatherWaitTime=$cfg_cjtime; //资源库采集每页数据间隔时间
$reslibMainSite="";
$backurl=isset($backurl)?$backurl:"admin_reslib2.php?password=$ukey";
$var_url=$url;

if($action=='')
{
	top();
	if(RWCache('collect_xml'))
	echo "<script>openCollectWin(400,'auto','上次采集未完成，继续采集？','".RWCache('collect_xml')."')</script>";
	echo '<br>执行完毕:';
	echo date("Y-m-d H:i:s");

	bottom();
	exit();	
}



if($action=="list")
{
	@session_write_close();
	top();
	if (empty($pg)||$pg<=0)$pg=1;
	if($rid==32)
	{
		$weburl=$var_url."?s=plus-api-xml-cms-max-list-true-cid-{$t}-h-{$h}-p-{$pg}-wd-".gbutf8($wd);
	}
	else
	{
		$weburl=$var_url.(strpos($var_url,'?')!==false?"&":"?")."h=".$h."&wd=".gbutf8($wd)."&t=".$t."&ac=list&bindtype=".getBindedLibIds()."&pg=".$pg;
	}
	$xml = simplexml_load_string(str_replace("m_id","e_id[]",str_replace("?action=","?ressite=".$ressite."&action=",cget($weburl,$isref))));
	if(!$xml){	$xml = simplexml_load_string(str_replace("m_id","e_id[]",str_replace("?action=","?ressite=".$ressite."&action=",cget($weburl,0))));}
	if(!$xml){ echo '获取资源失败';exit;}
	$totalcount = $xml->list['recordcount'];
	$pagesize = $xml->list['pagesize'];	
	$currentpage = $xml->list['page'];
	$pagecount = $xml->list['pagecount'];
	?>
    
    
    
    
  <div class="container" id="cpcontainer">
  <table class="tb">
    <thead>
      <tr class="thead">
        <th colspan="4">&nbsp;<a href="?">采集平台</a></th>
      </tr>
      <tr>
        <th colspan="4"> <ul class="ul">
          <li><a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;url=<?php  echo $url;?>">全部</a></li>
          
          <?php 
          	
				foreach($xml->class->ty as $ty)
				{
					$isbangding = "";
					if(getBindedLocalId($rid."_".$ty['id']))
					{
						if(intval(getBindedLocalId($rid."_".$ty['id'])))
						{
							$isbangding = "已绑定";
						}
						else
						{
							$isbangding = "<font color='red'>未绑定</font>";
						}
					}
					else
					{
						$isbangding = "<font color='red'>未绑定</font>";
					}
					?>
           <li><a href="?ac=list&amp;url=<?php  echo $var_url?>&amp;rid=<?php  echo $rid ?>&amp;t=<?php  echo $ty['id']?>" ><?php  echo $ty; ?></a>&nbsp;&nbsp;<label id="bind_<?php  echo $rid ?>_<?php  echo $ty['id'] ?>"><b><a href="#" onClick="setBindType('<?php  echo $rid ?>_<?php  echo $ty['id'] ?>',0)"><?php  echo $isbangding ?></a></b></label></li>
                    
                    <?php 
				}
		  
		  ?>
        </ul>
        </th>
      </tr>
      <tr>
        <th colspan="4"> <div class="cuspages" style="margin:0">
          <div class="pages" style="margin:0">
          共 <?php  echo($totalcount)?> 条数据 每页 <?php  echo $pagesize; ?> 条 当前 <?php  echo $currentpage?>/<?php  echo $pagecount?> 
          页码
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=1&amp;url=<?php  echo $var_url?>">首页</a>
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=<?php  echo ($pg-1) ?>&amp;url=<?php  echo $var_url?>">上一页</a>
          <?php  
		  	for($i=$pg-3;$i<($pg+4);$i++)
			{
				if($i<1){}
				elseif($i>$pagecount){}
				else{
			
		  ?>
          <a href='?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=<?php  echo ($i) ?>&amp;url=<?php  echo $var_url?>'><?php  echo $i;?></a>
          <?php 
			}
			}
		  ?>
          
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=<?php  echo ($pg+1) ?>&amp;url=<?php  echo $var_url?>">下一页</a>
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=<?php  echo ($pagecount) ?>&amp;url=<?php  echo $var_url?>">尾页</a>&nbsp;&nbsp;
          跳转
            <input type="text" id="skip" value="" onKeyUp="this.value=this.value.replace(/[^\d]+/,'')" style="width:40px"/>
            &nbsp;&nbsp;
            <input type="button" value="确定" class="btn" onClick="location.href='?ac=list&amp;rid=<?php  echo $rid;?>&amp;url=<?php  echo $var_url?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg='+$('skip').value;"/>
          </div>
        </div>
        </th>
      </tr>
    </thead>
    <form action="?" method="get">
      <input type="hidden" name="ac" value="<?php  echo $action?>" />
      <input type="hidden" name="rid" value="<?php  echo $rid?>" />
      <input type="hidden" name="t" value="<?php  echo $t;?>" />
      <input type="hidden" name="h" value="<?php  echo $h;?>" />
      <input type="hidden" name="url" value="<?php  echo $var_url?>" />
      <tr>
        <th colspan="4"> &nbsp;
          <input type="button" class="btn" style="width:65px" onClick="checkOthers('input','ids[]')" value="全选反选" />
          &nbsp;
          <input type="button" class="btn" style="width:45px" onClick="$('choose').submit();" value="采集"/>
          &nbsp;
          <a onClick="ajaxFunction('?ac=day&amp;url=<?php  echo $var_url?>&rid=<?php  echo $rid;?>&t=<?php  echo $t;?>&h=24&backurl='+encodeURIComponent('admin_reslib2.php?password=<?php  echo $ukey ?>&ac=list&amp;url=<?php  echo $var_url?>&rid=<?php  echo $rid;?>'))" href="#" >
          <div class="btn" style="display:inline;width:65px;text-align:center;">采集当天</div></a>
          &nbsp;
          <a onClick="ajaxFunction('?ac=type&amp;url=<?php  echo $var_url?>&rid=<?php  echo $rid;?>&t=<?php  echo $t;?>&backurl='+encodeURIComponent('admin_reslib2.php?password=<?php  echo $ukey ?>&ac=list&amp;url=<?php  echo $var_url?>&rid=<?php  echo $rid;?>'))" href="#" >
          <div class="btn" style="<?php  if($t<1){ ?>display:none;<?php  }else{?>display:inline;<?php  }?>width:65px;text-align:center;">一键采集该分类</div></a>
          &nbsp;
          <input type="button" class="btn" style="width:65px" onClick="location.reload()" value="刷新网页"/>
          &nbsp;&nbsp;查询：
          <input type="text" name="wd" value="" style="width:100px" />
          &nbsp;
          <input type="submit" class="btn" name="submit" value="搜 索" />
          &nbsp;
          <select name="select" onChange="location.href='?ac=list&amp;url=<?php  echo $var_url?>&rid=<?php  echo $rid;?>&t='+this.options[this.selectedIndex].value+'&h=&pg=&wd='">
            <option value="">按分类查看</option>
            <?php 
				foreach($xml->class->ty as $ty)
				{

          			?>
                     <option value="<?php  echo $ty['id']?>"><?php  echo $ty;?></option>
                    
                    <?php 
				}
				
			?>
            
           
          </select>
        <div id='wait'></div>
        </th>
      </tr>
    </form>
    <tr>
      <th>名称</th>
      <th>分类</th>
      <th>来源</th>
      <th width="135">时间</th>
    </tr>
    <tr>
      <td></thead></td>
    </tr>
    <tbody>
    </tbody>
<form action="?" method="post" name="choose" id="choose">
<input type="hidden" name="ac" value="select">
<input type="hidden" name="rid" value="<?php  echo($rid)?>">
<input type="hidden" name="backurl" value="?ac=list&rid=<?php  echo($rid)?>&t=<?php  echo $t;?>&h=<?php  echo $h;?>&wd=<?php  echo $wd;?>&pg=<?php  echo $pg;?>&amp;url=<?php  echo $var_url?>">
<input type="hidden" name="url" value="<?php  echo $var_url?>" />
 
    <?php 
	
	foreach($xml->list->video as $video)
	{
		//echo gbutf8($video->id)."<br/>";
		$starttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$vtime = strtotime($video->last);
		if($vtime>=$starttime&&$vtime<=$starttime+78400)
		{ 
			$ch = 'checked';
			$video->last='<font color="red">'.$video->last.'</font>';
		}
		else
		{
			$ch = '';	
		}
		?>
        	<tr>
		<td nowrap="nowrap"><input type="checkbox" class="checkbox" name="ids[]" value="<?php  echo $video->id; ?>" id="<?php  echo $video->id; ?>" <?php  echo $ch; ?>/><label for="<?php  echo $video->id; ?>"><?php  echo $video->name; ?><font color="#FF0000"><?php  echo $video->note; ?></font></label></td>
		<td nowrap="nowrap"><a href="?ac=list&t=<?php  echo $video->tid; ?>&rid=<?php  echo $rid;?>"><?php  echo $video->type; ?></a></td>
		<td nowrap="nowrap"><?php  echo $video->dt; ?></td>
		<td nowrap="nowrap"><?php  echo $video->last; ?></td>
	</tr>

        <?php 
	}
	?>
	</form>

    <tr>
      <td colspan="4"><div class="cuspages" style="margin:0">
          <div class="pages" style="margin:0">
          共 <?php  echo($totalcount)?> 条数据 每页 <?php  echo $pagesize; ?> 条 当前 <?php  echo $currentpage?>/<?php  echo $pagecount?> 
          页码
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=1&amp;url=<?php  echo $var_url?>">首页</a>
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=<?php  echo ($pg-1) ?>&amp;url=<?php  echo $var_url?>">上一页</a>
          <?php  
		  	for($i=$pg-3;$i<($pg+4);$i++)
			{
				if($i<1){}
				elseif($i>$pagecount){}
				else{
			
		  ?>
          <a href='?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg=<?php  echo ($i) ?>&amp;url=<?php  echo $var_url?>'><?php  echo $i;?></a>
          <?php 
			}
			}
		  ?>
          
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;url=<?php  echo $var_url?>&amp;pg=<?php  echo ($pg+1) ?>">下一页</a>
          <a href="?ac=list&amp;rid=<?php  echo $rid;?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;url=<?php  echo $var_url?>&amp;pg=<?php  echo ($pagecount) ?>">尾页</a>&nbsp;&nbsp;
          跳转
            <input type="text" id="skip2" value="" onKeyUp="this.value=this.value.replace(/[^\d]+/,'')" style="width:40px"/>
            &nbsp;&nbsp;
            <input type="button" value="确定" class="btn" onClick="location.href='?ac=list&amp;rid=<?php  echo $rid;?>&amp;url=<?php  echo $var_url?>&amp;t=<?php  echo $t ?>&amp;h=<?php  echo $h ?>&amp;wd=<?php  echo $wd ?>&amp;pg='+$('skip2').value;"/>
          </div>
        </div></td>
    </tr>
    <tr>
      <td></tbody></td>
    </tr>
  </table>
</div>  
    
    

	<?php 
	bottom();

}
elseif($action=="bind")
{
	echo "<select name='tid' id='tid'><option value=''>解除绑定</option>";
	$typeid=getBindedLocalId($curid);
	makeTypeOptionSelected(0,"&nbsp;|&nbsp;","",$typeid);
	echo "</select>&nbsp;&nbsp;<input type='button' class='btn' value='绑  定' onclick=\"submitBindType(getSelect_Value('tid'),'".$curid."','".$typeid."')\"/>&nbsp;&nbsp;<input type='button' class='btn' value='返  回' onclick='hideBind();'/></form>";
}
elseif($action=="bindsubmit")
{
	if(is_numeric($tid))
	{
		if(!empty($v_oldtype))
		{
			$row = $dsql->GetOne("select tid,unionid from sea_type where tid='$v_oldtype'");
			if(is_array($row)){
				$unionid=delBindId($curid,$row["unionid"]);
				$dsql->ExecuteNoneQuery("update sea_type set unionid='$unionid' where tid=".$row['tid']);
			}
		}
		$row = $dsql->GetOne("select tid,unionid from sea_type where tid='$tid'");
		if(is_array($row)){
			$unionid=addUnionid($curid,$row["unionid"]);
			$dsql->ExecuteNoneQuery("update sea_type set unionid='$unionid' where tid=".$row['tid']);
		}
		echo "bindok";
		exit;
	}else{
		$row = $dsql->GetOne("select tid,unionid from sea_type where concat(',',unionid,',') like '%,".$curid.",%'");
		if(is_array($row)){
			$unionid=delBindId($curid,$row["unionid"]);
			$dsql->ExecuteNoneQuery("update sea_type set unionid='$unionid' where tid=".$row['tid']);
		}
		echo "nobind";
		exit;
	}
}
elseif($action=="select")
{
	if(empty($ids))
	{
		ShowMsg("请选择采集数据","-1");
		exit();
	}
	$a_ids = implode(',',$ids);
	if($rid==32)
	{
		$weburl=$var_url."?s=plus-api-xml-cms-max-vodids-".$a_ids;
	}
	else
	{
		$weburl=$var_url.(strpos($var_url,'?')!==false?"&":"?")."ac=videolist&ressite=".$ressite."&ids=".$a_ids;
	}
	intoDatabase($weburl,"select");
}
elseif($action=="day")
{
	$page = $pg;
	if($rid==32)
	{
		$weburl=$var_url."?s=plus-api-xml-cms-max-cid--h-24-p-{$page}";
	}
	else
	{
		$weburl=$var_url.(strpos($var_url,'?')!==false?"&":"?")."ac=videolist&rid=".$ressite."&t=0&h=24&pg=".$page;
	}
	
	intoDatabase($weburl,"day");
}elseif($action=="week")
{
	$page = $pg;
	if($rid==32)
	{
		$weburl=$var_url."?s=plus-api-xml-cms-max-cid--h-168-p-{$page}";
	}
	else
	{
		$weburl=$var_url.(strpos($var_url,'?')!==false?"&":"?")."ac=videolist&rid=".$ressite."&t=0&h=168&pg=".$page;
	}
	//echo $weburl;
	
	intoDatabase($weburl,"week");
}
elseif($action=="type")
{
	$page = $pg;
	if($rid==32)
	{
		$weburl=$var_url."?s=plus-api-xml-cms-max-cid-{$t}-h--p-{$page}";
	}
	else
	{
		$weburl=$var_url.(strpos($var_url,'?')!==false?"&":"?")."ac=videolist&rid=".$ressite."&t=".$t."&pg=".$page;
	}
	intoDatabase($weburl,"type");
}
elseif($action=="all")
{
	$page = $pg;
	if($rid==32)
	{
		$weburl=$var_url."?s=plus-api-xml-cms-max-cid-{$t}-h--p-{$page}";
	}
	else
	{
		$weburl=$var_url.(strpos($var_url,'?')!==false?"&":"?")."ac=videolist&rid=".$ressite."&pg=".$page;
	}
	intoDatabase($weburl,"all");
}
else
{
	top();
	echo cget($reslibMainSite."resindex.html",$isref);
	bottom();
}

function getBindedLocalId($libId)
{
	global $dsql;
	$row = $dsql->GetOne("select count(*) as dd from sea_type where unionid<>''");
	if(!is_array($row)) return '';
	$sqlStr="select tid,unionid from sea_type where unionid<>''";
	$dsql->SetQuery($sqlStr);
	$dsql->Execute('unionid_list');
	while($row=$dsql->GetObject('unionid_list'))
	{
		$unionArray=explode(",",$row->unionid); $arrayLen2=count($unionArray);
		for($i=0;$i<$arrayLen2;$i++){
			if (trim($unionArray[$i])==trim($libId)) return $row->tid;
		}
	}
}

function delBindId($bindid,$unionId)
{
	if(empty($unionId)){
		return $bindid;
	}else{
		if(strpos(",,".$unionId.",,",",".$bindid.",")) return ltrim(rtrim(str_replace(",".$bindid.",",",",",".$unionId.","),","),","); else return $unionId;
	}
}

function addUnionid($bindid,$unionId)
{
	if(empty($unionId)){
		return $bindid;
	}else{
		if(strpos(",".$unionId.",",",".$bindid.",")) return $unionId; else return $unionId.",".$bindid;
	}
}

function getBindedLibIds()
{
	global $dsql;
	$libIds="";
	$sqlStr="select tid,unionid from sea_type where unionid<>''";
	$dsql->SetQuery($sqlStr);
	$dsql->Execute('unionid_list');
	while($row=$dsql->GetObject('unionid_list'))
	{
		$libIds.=$row->unionid.',';
	}
	$libIds=rtrim($libIds,',');
	return $libIds;
}

function intoDatabase($url,$gtype)
{
	@session_write_close();
	global $dsql,$col,$cfg_gatherset,$backurl,$gatherWaitTime,$ressite,$var_url,$action,$isref,$pg;
	$content=cget($url,$isref);
	$content=filterChar($content);
	$xml = simplexml_load_string($content);
	if(!$xml){	$xml =  simplexml_load_string(cget($url,0));}
	if(!$xml){ echo '获取资源失败';exit;}
	echo "<div style='font-size:13px'><font color=red>资源库视频采集开始：</font><br>";
	if ($gtype=="type" || $gtype=="day"|| $gtype=="week"|| $gtype=="all") 
	{
		if(empty($pg))
			$page=1;
		else
			$page=$pg;
	}
	$temparr=array();
	$temparr=getrulevaluearr($content,'v');
	$pagecount = $xml->list['pagecount'];
	$pagesize = $xml->list['pagesize'];
	$recordcount = $xml->list['recordcount'];
	foreach($xml->list->video as $video)
	{
		$xmltid =  $video->tid;//影片分类id
		$name =  $video->name;//影片名称
		$localId = getBindedLocalId($ressite.'_'.$xmltid);//入库后本地id
		$data = "$$".$video->dl->dd;
		if(!empty($name)&&!empty($data))
		{
			echo $col->xml_db($video,$localId);
		}//if $title
		@ob_flush();
		@flush();
	}//foreach
	if ($action=="select") exit("<script>alert('恭喜，全部搞定');location.href='".urldecode($backurl)."';</script>");
	if($page==$pagecount OR $pagecount==0)
	{
		if ($action=="day" || $action=="type"|| $action=="all"||$action=="week")
		{ 
			RWCache('collect_xml',NULL);
			exit("<script>location.href='".urldecode($backurl)."'</script>");
		}
	}else{
		echo "<br/>暂停".$gatherWaitTime."秒--<font color=red>即将开始同步第".($page+1)."/".$pagecount."页</font><br/></div>";
		$ukey=$_GET['password'];
		if ($action=="day"){
			RWCache('collect_xml',"admin_reslib2.php?password=".$ukey."&action=day&rid=".$ressite."&pg=".($page+1)."&url=".$var_url."&backurl=".urlencode($backurl));
			echo "<script language=\"javascript\">setTimeout(\"makeNextPage();\",".$gatherWaitTime."000);function makeNextPage(){location.href='?password=".$ukey."&action=day&rid=".$ressite."&pg=".($page+1)."&url=".$var_url."&backurl=".urlencode($backurl)."';}</script>";
		}elseif ($action=="week"){
			RWCache('collect_xml',"admin_reslib2.php?password=".$ukey."&action=week&rid=".$ressite."&pg=".($page+1)."&url=".$var_url."&backurl=".urlencode($backurl));
			echo "<script language=\"javascript\">setTimeout(\"makeNextPage();\",".$gatherWaitTime."000);function makeNextPage(){location.href='?password=".$ukey."&action=week&rid=".$ressite."&pg=".($page+1)."&url=".$var_url."&backurl=".urlencode($backurl)."';}</script>";
		}elseif ($action=="type"){
			RWCache('collect_xml',"admin_reslib2.php?password=".$ukey."&action=type&rid=".$ressite."&pg=".($page+1)."&t=".$xmltid."&url=".$var_url."&backurl=".urlencode($backurl));
			echo "<script language=\"javascript\">setTimeout(\"makeNextPage();\",".$gatherWaitTime."000);function makeNextPage(){location.href='?password=".$ukey."&action=type&rid=".$ressite."&pg=".($page+1)."&t=".$xmltid."&url=".$var_url."&backurl=".urlencode($backurl)."';}</script>";
		}elseif ($action=="all"){
			RWCache('collect_xml',"admin_reslib2.php?password=".$ukey."&action=all&rid=".$ressite."&pg=".($page+1)."&url=".$var_url."&backurl=".urlencode($backurl));
			echo "<script language=\"javascript\">setTimeout(\"makeNextPage();\",".$gatherWaitTime."000);function makeNextPage(){location.href='?password=".$ukey."&action=all&rid=".$ressite."&pg=".($page+1)."&url=".$var_url."&backurl=".urlencode($backurl)."';}</script>";
		}
	}
}

function getrulevalue($content,$str)
{
	if(!empty($content) && !empty($str)){
		$labelRule = buildregx("<".$str.">(.*?)"."</".$str.">","is");
		preg_match_all($labelRule,$content,$ar);
		return $ar[1][0];
	}
}

function getrulevaluearr($content,$str)
{
	if(!empty($content) && !empty($str)){
		$labelRule = buildregx("<".$str.">(.*?)"."</".$str.">","is");
		preg_match_all($labelRule,$content,$ar);
		return $ar[1];
	}
}

function unescapseCode($str)
{
	if(empty($str)) return ""; else return urldecode(UnicodeUrl2Gbk($str));
}

function replaceCDATA($str)
{
	return str_replace(']]>','',str_replace('<![CDATA[','',$str));
}

function top()
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>资源库采集程序</title>
<link rel="stylesheet" type="text/css" href="img/res.css"/>
<link href="img/admin.css" rel="stylesheet" type="text/css" />
<script src="js/common.js"></script>
<script src="js/main.js"></script>
<style type="text/css">
	input{height:15px;}
	.tb2 td{padding:2px 5px 2px 5px;height:25px}
	.tb2 .thc{text-align:center;line-height:30px; background-color: #F5F7F8;font-size:18px;font-weight:bold;color:#000}
	.tb2 .thr{text-align:center;height:20px}
	.label{padding:0;width:150px;text-align:right;border-right:1px solid #DEEFFA;}
	.btn{height:22px}
	.red{color:red}
	.blue{color:blue}
	.gray{color:gray}
	.ul li{float:left;margin:3px;width:150px}
	h3{padding:0;margin:0;font-size:12px;background:none;border:none}
	.btn{border:1px solid;border-color:#fff #999 #999 #fff;}
</style>
</head>
<body bgcolor="#F7FBFF">
<div class="container" id="cpcontainer">
<?php 
}

function bottom()
{
?>
</div>
</body>
</html> 
<?php 
}
?>