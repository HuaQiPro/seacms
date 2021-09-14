<?php
define('sea_ADMIN', preg_replace("|[/\\\]{1,}|",'/',dirname(__FILE__) ) );
require_once(sea_ADMIN."/../include/common.php");
//require_once(sea_INC."/check.admin.php");
require_once(sea_ADMIN."/coplugins/Snoopy.class.php");
header("Cache-Control:private");
error_reporting(0);
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

//检验用户登录状态
if(!isset($_REQUEST['pwd']) || trim($_REQUEST['pwd']) != '123456'){
	die('deny!');
}

//栏目分类列表
if(isset($_REQUEST["list"])){
	echo '<select name="">';
	echo makeTypeOptionSelected(0, '&nbsp;&nbsp;', '', '', 0);
	echo "</select>";
	die();
}

$v_data = getDataByRequest();
echo $col->_into_database($v_data);
die();

/*
* 参数获取
 */
function getDataByRequest(/* $requestData */){
	$v_data = array();

	//影片名称\标题
	if (isset($_REQUEST['v_name']) && trim($_REQUEST['v_name']) != '') {
		$v_data['v_name'] = $_REQUEST['v_name'];
	}else{
		die('影片名称不能为空！');
	}
	
	// 影片关键词过滤 跳过采集
		global $cfg_cjjump;
		$jumparr = explode('|',$cfg_cjjump);
		foreach ($jumparr as $value)
				  {
					  if(strpos($v_data['v_name'],$value) !== false){return "数据<font color=red>".$v_data['v_name']."</font>影片名称不能为空,或影片名称含过滤词,跳过采集！";}
				  }
	
	//影片分类
	if (isset($_REQUEST['v_type']) && intval($_REQUEST['v_type']) != 0) {
		$v_data['tid'] = intval($_REQUEST['v_type']);
	}else{
		die('所属分类不能为空！');
	}
	//下载地址
	if (isset($_REQUEST['v_downdata']) && trim($_REQUEST['v_downdata']) != '') {
		$v_data['v_downdata'] = trim($_REQUEST['v_downdata']);
	}
	//播放地址
	if (isset($_REQUEST['v_playdata']) && trim($_REQUEST['v_playdata']) != '') {
		$v_data['v_playdata'] = trim($_REQUEST['v_playdata']);
	}
	if (trim($_REQUEST['v_playdata'])=="" AND trim($_REQUEST['v_downdata'])=="" ) {
		die('播放地址和下载地址不可同时为空！');
	}
	//图片地址\缩略图
	if (isset($_REQUEST['v_pic']) && trim($_REQUEST['v_pic']) != '') {
		$v_data['v_pic'] =  $_REQUEST['v_pic'];
	}
	//影片状态\连载
	if (isset($_REQUEST['v_state']) && trim($_REQUEST['v_state']) != '') {
		$v_data['v_state'] = $_REQUEST['v_state'];
	}
	//影片\语言
	if (isset($_REQUEST['v_lang']) && trim($_REQUEST['v_lang']) != '') {
		$v_data['v_lang'] = $_REQUEST['v_lang'];
	}
	//影片\影片别名
	if (isset($_REQUEST['v_nickname']) && trim($_REQUEST['v_nickname']) != '') {
		$v_data['v_nickname'] = $_REQUEST['v_nickname'];
	}
	//影片\更新周期 最终值为 周一 周二 xx 多个值用英文 , 隔开
	if (isset($_REQUEST['v_reweek']) && trim($_REQUEST['v_reweek']) != '') {
		$v_data['v_reweek'] = $_REQUEST['v_reweek'];
	}
	//影片\豆瓣
	if (isset($_REQUEST['v_douban']) && trim($_REQUEST['v_douban']) != '') {
		$v_data['v_douban'] = $_REQUEST['v_douban'];
	}
	//影片\时光
	if (isset($_REQUEST['v_mtime']) && trim($_REQUEST['v_mtime']) != '') {
		$v_data['v_mtime'] = $_REQUEST['v_mtime'];
	}
	//影片\IMDB
	if (isset($_REQUEST['v_imdb']) && trim($_REQUEST['v_imdb']) != '') {
		$v_data['v_imdb'] = $_REQUEST['v_imdb'];
	}
	//影片\上映电视台
	if (isset($_REQUEST['v_tvs']) && trim($_REQUEST['v_tvs']) != '') {
		$v_data['v_tvs'] = $_REQUEST['v_tvs'];
	}
	//影片\制作公司
	if (isset($_REQUEST['v_company']) && trim($_REQUEST['v_company']) != '') {
		$v_data['v_company'] = $_REQUEST['v_company'];
	}
	//影片\时长
	if (isset($_REQUEST['v_len']) && trim($_REQUEST['v_len']) != '') {
		$v_data['v_len'] = $_REQUEST['v_len'];
	}
	//影片\总集数
	if (isset($_REQUEST['v_total']) && trim($_REQUEST['v_total']) != '') {
		$v_data['v_total'] = $_REQUEST['v_total'];
	}
	//影片\地区
	if (isset($_REQUEST['v_publisharea']) && trim($_REQUEST['v_publisharea']) != '') {
		$v_data['v_publisharea'] = $_REQUEST['v_publisharea'];
	}
	//影片年份\上映日期
	if (isset($_REQUEST['v_publishyear']) && trim($_REQUEST['v_publishyear']) != '') {
		$v_data['v_publishyear'] = $_REQUEST['v_publishyear'];
	}
	//备注
	if (isset($_REQUEST['v_note']) && trim($_REQUEST['v_note']) != '') {
		$v_data['v_note'] = $_REQUEST['v_note'];
	}
	//剧情分类
	if (isset($_REQUEST['v_jq']) && trim($_REQUEST['v_jq']) != '') {
		$v_data['v_jq'] = $_REQUEST['v_jq'];
	}
	//主演
	if (isset($_REQUEST['v_actor']) && trim($_REQUEST['v_actor']) != '') {
		$v_data['v_actor'] = $_REQUEST['v_actor'];
	}
	//导演
	if (isset($_REQUEST['v_director']) && trim($_REQUEST['v_director']) != '') {
		$v_data['v_director'] = $_REQUEST['v_director'];
	}
	//简介
	if (isset($_REQUEST['v_content']) && trim($_REQUEST['v_content']) != '') {
		$v_data['v_des'] = $_REQUEST['v_content'];
	}
	//标签\关键词
	if (isset($_REQUEST['v_tags']) && trim($_REQUEST['v_tags']) != '') {
		$v_data['v_tags'] = $_REQUEST['v_tags'];
	}
	//所属专题
	if (isset($_REQUEST['v_tags']) && intval($_REQUEST['v_topic']) !== 0) {
		$v_data['v_topic'] = intval($_REQUEST['v_topic']);
	}
	//版本
	if (isset($_REQUEST['v_ver']) && trim($_REQUEST['v_ver']) != '') {
                $v_data['v_ver'] = $_REQUEST['v_ver'];
    }
	$v_data['v_enname'] = Pinyin($v_data['v_name']);
	$v_data['v_letter'] = strtoupper(substr($v_data['v_enname'],0,1));
//备用说明
	if (isset($_REQUEST['v_longtxt']) && trim($_REQUEST['v_longtxt']) != '') {
		$v_data['v_longtxt'] = $_REQUEST['v_longtxt'];
	}

		global $cfg_cj_rq,$cfg_cj_rq_s,$cfg_cj_rq_e,$cfg_cj_dc,$cfg_cj_dc_s,$cfg_cj_dc_e,$cfg_cj_pf,$cfg_cj_pf_s,$cfg_cj_pf_e;
		
		//pf
		if($cfg_cj_pf=='1'){$v_data['v_scorenum'] = 1; $v_data['v_score'] = mt_rand($cfg_cj_pf_s,$cfg_cj_pf_e);}
		//dc
		if($cfg_cj_dc=='1'){$v_data['v_digg'] = mt_rand($cfg_cj_dc_s,$cfg_cj_dc_e); $v_data['v_tread'] = mt_rand($cfg_cj_dc_s,$cfg_cj_dc_e);}
		//rq
		if($cfg_cj_rq=='1'){$v_data['v_hit'] = mt_rand($cfg_cj_rq_s,$cfg_cj_rq_e);}

	return $v_data;
}

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
					$selectedStr=" selected";
					break;
					}
					else
					{
					$selectedStr="";
					}
			}
			
			echo "<option value='".$row->tid."'".$selectedStr.">".$span."&nbsp;|—".$row->tname."</option>";
			makeTypeOptionSelected_Multiple($row->tid,$separateStr,$span,$compareValue,$tptype);
			
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

/**
 * 由playdata判断播放来源
 * @param  [string] $str [description]
 * @return [string]      [description]

function getFromByPlaydata($str)
{
	if (m_ereg(".swf",$str)) return "SWF数据";
	if (m_ereg("qvod",$str)) return "qvod";
	if (m_ereg("bdhd",$str)) return "百度影音";
	if (m_ereg("tudou.com",$str)) return "土豆高清";
	if (m_ereg("sina.com.cn",$str)) return "新浪高清";
	if (m_ereg("sohu.com",$str)) return "搜狐高清";
	if (m_ereg("hd_openv",$str)) return "天线高清";
	if (m_ereg("hd_56",$str)) return "56高清";
	if (m_ereg("56.com",$str)) return "56";
	if (m_ereg("youku.com",$str)) return "优酷";
	if (m_ereg("tudou.com",$str)) return "土豆";
	if (m_ereg("sohu",$str)) return "搜狐";
	if (m_ereg("iask",$str)) return "新浪";
	if (m_ereg("6rooms",$str)) return "六间房";
	if (m_ereg("qq.com",$str)) return "qq";
	if (m_ereg("youtube.com",$str)) return "youtube";
	if (m_ereg("17173.com",$str)) return "17173";
	if (m_ereg("ku6.com",$str)) return "ku6视频";
	if (m_ereg("flv",$str)) return "FLV";
	if (m_ereg("real",$str)) return "real";
	if (m_ereg("media",$str)) return "media";
	if (m_ereg("pps.tv",$str)) return "ppstream";
	if (m_ereg("gvod://",$str)) return "迅播高清";
	if (m_ereg("wp2008",$str)) return "远古高清";
	if (m_ereg("ppvod",$str)) return "ppvod高清";
	if (m_ereg("pvod",$str)) return "PVOD";
	if (m_ereg("cc",$str)) return "播客CC";
	if (m_ereg("pipi.cn",$str)) return "皮皮影音";
	if (m_ereg("webplayer9",$str)) return "久久影音";
	if (m_ereg("jidong",$str)) return "激动";
	if (m_ereg("flashPvod",$str)) return "闪播Pvod";
	if (m_ereg("iqiyi.com",$str)) return "奇艺";

	return 'SWF数据';
}

 * 播放地址格式化
 * 
 * 支持至少三种格式的 $v_playdata
 * 1. 标准格式
 * 2. 一行一个播放地址
 * 3. 带名称的一行一个播放地址，用 $ 分隔
 * 4. 多个播放来源的播放地址，用 $$$ 分隔
 * 。。。
 * @param  [string] $v_playdata [播放地址]
 * @param  string $v_playfrom [播放地址来源]
 * @return [type]             [description]
 */
/*
影片数据标准格式参考如下：
	泥巴影音$$第1集$111111111111$niba#第2集$222222222222$niba#第3集$33333333333$niba$$$西瓜影音$$第1集$1111111111$xigua#第2集$22222222222$xigua$$$搜狐视频$$第1集$1111111111$sohu#第2集$222222222$sohu
*/
function formatPlaydata($v_playdata, $v_playfrom=''){

	if (empty($v_playdata)) {
		die('播放地址不能为空！');
	}

	//认为包含 # 分隔符的地址，为已格式化为标准格式
	if (strpos($v_playdata, '#') !== false) {
		return $v_playdata;
	}
	
	//对于每一种播放来源分别处理
	
	$_v_playdata_arr = explode('$$$', $v_playdata);//播放地址数组
	$_v_playfrom_arr = array();	//播放地址来源数组，一一对应播放地址数组
	if (!empty($v_playfrom)) {
		$_v_playfrom_arr = explode('$$$', $v_playfrom);
	}
	
	foreach ($_v_playdata_arr as $key => $value) {
		if (empty($value)) {
			unset($_v_playdata_arr[$key]);
			continue;
		}

		//优酷视频地址的处理
		//$value = formatYoukuData($value);

		//影片来源前缀
		if (isset($_v_playfrom_arr[$key]) && trim($_v_playfrom_arr[$key]) != '') {
			$_v_playfrom_arr[$key] = trim($_v_playfrom_arr[$key]);
		}else{
			$_v_playfrom_arr[$key] = $_v_playdata_arr[$key];
			$_v_playfrom_arr[$key] = empty($_v_playfrom_arr[$key]) ? 'qvod' : $_v_playfrom_arr[$key];
		}
		
		// 来源简写id
		$v_playfrom_id = getReferedId($_v_playfrom_arr[$key]);

		//按行分隔，对每一行地址进行格式化
		
		$_v_play_arr = array_unique(explode("\n", str_replace("\r\n", "\n", $value)));

		foreach ($_v_play_arr as $k => $v) {
			if (empty($v)) {
				unset($_v_play_arr[$k]);
				continue;
			}

			$hasdollar = strpos($v, "$");
			if ($hasdollar !== false) {
				//存在第一个 $符号，继续寻找第二个 $ 符号
				$v = str_replace('$ ', '$', $v);
				$hasdollar = strpos( $v, '$', ($hasdollar+1));
				if ($hasdollar === false) {
					//不存在第二个 $ 符号，即地址格式为：
					//第一集$bdhd://ddddd.mkv

					$_v_play_arr[$k] = $v . '$' . $v_playfrom_id;
				}
			}else{
				//一个 $ 符号都没有，则地址格式为：
				//bdhd://ddddd.mkv
				$_v_play_arr[$k] = '第' . ($k+1) . '集$'. $v . '$' . $v_playfrom_id;
			}
		}

		$_v_playdata_arr[$key] =$_v_playfrom_arr[$key] .'$$'. rtrim(implode('#', $_v_play_arr), '#');
	}

	return implode('$$$', $_v_playdata_arr);
}

/**
 * 下载地址格式化，参考播放地址格式
 * @param  [string] $v_downdata [description]
 * @param  [string] $v_downfrom [description]
 * @return [string]             [description]
 */
function formatDowndata($v_downdata, $v_downfrom=''){
	if (empty($v_downdata)) {
		return '';
	}

	//认为包含 $down 分隔符的地址，为已格式化为标准格式
	if (strpos($v_downdata, '$down') !== false) {
		return $v_downdata;
	}
	
	//对于每一种来源分组分别处理
	
	$_v_downdata_arr = explode('$$$', $v_downdata);//下载地址分组数组
	$_v_downfrom_arr = array();	//地址分组来源数组，一一对应地址数组
	if (!empty($v_downfrom)) {
		$_v_downfrom_arr = explode('$$$', $v_downfrom);
	}
	
	foreach ($_v_downdata_arr as $key => $value) {
		if (empty($value)) {
			unset($_v_downdata_arr[$key]);
			continue;
		}

		//影片下载来源分组名前缀
		if (isset($_v_downfrom_arr[$key]) && trim($_v_downfrom_arr[$key]) != '') {
			$_v_downfrom_arr[$key] = trim($_v_downfrom_arr[$key]);
		}else{
			$_v_downfrom_arr[$key] = '下载地址' . ($key+1);
		}
		
		// 来源简写id
		$v_downfrom_id = 'down';

		//按行分隔，对每一行地址进行格式化
		
		$_v_down_arr = array_unique(explode("\n", str_replace("\r\n", "\n", $value)));

		foreach ($_v_down_arr as $k => $v) {
			if (empty($v)) {
				unset($_v_down_arr[$k]);
				continue;
			}

			$hasdollar = strpos($v, "$");
			if ($hasdollar !== false) {
				//存在第一个 $符号，继续寻找第二个 $ 符号
				$v = str_replace('$ ', '$', $v);
				$hasdollar = strpos( $v, '$', ($hasdollar+1));
				if ($hasdollar === false) {
					//不存在第二个 $ 符号，即地址格式为：
					//第一集$bdhd://ddddd.mkv

					$_v_down_arr[$k] = $v . '$' . $v_downfrom_id;
				}
			}else{
				//一个 $ 符号都没有，则地址格式为：
				//bdhd://ddddd.mkv
				$_v_down_arr[$k] = '下载' . ($k+1) . '$'. $v . '$' . $v_downfrom_id;
			}
		}

		$_v_downdata_arr[$key] =$_v_downfrom_arr[$key] .'$$'. rtrim(implode('#', $_v_down_arr), '#');
	}

	return implode('$$$', $_v_downdata_arr);
}

/**
 * 对优酷地址的处理
 * @param string $playdata [一行一个，为优酷的播放页地址]
 * @return [string] 处理为优酷的 swf 播放地址

function formatYoukuData($playdata=''){
	if (empty($playdata) || strpos($playdata, 'v.youku.com/v_show')==false) {
		return $playdata;
	}

	$playdata_arr = explode('\n', str_replace('\r\n', '\n', $playdata));
	foreach ($playdata_arr as $key => $value) {
		if (empty($value)) {
			unset($playdata_arr[$key]);
		}
		$_preg_vid = preg_match('/http\:\/\/v\.youku\.com\/v_show/id_(.*)\.html/', $value, $matches);
		if ($_preg_vid) {
			$playdata_arr[$key] = 'player.youku.com/player.php/sid/' . $matches[1] . '/v.swf';
		}
	}
	
	return $playdata = implode('\r\n', $playdata_arr);
}
 */
?>