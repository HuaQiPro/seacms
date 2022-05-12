<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if($action=="set")
{
	$weburl= $_POST['weburl'];
	$token = $_POST['token'];
	$open=fopen("../data/admin/ping.php","w" );
	$str='<?php  ';
	$str.='$weburl = "';
	$str.="$weburl";
	$str.='"; ';
	$str.='$token = "';
	$str.="$token";
	$str.='"; ';
	$str.=" ?>";
	fwrite($open,$str);
	fclose($open);
	ShowMsg("成功保存设置!","admin_ping.php");
	exit;
}
if($action=="reset"){
	$query = "update `sea_data` set v_push=0";
    $dsql->ExecuteNoneQuery($query);
	ShowMsg("已将所有视频状态修改为未推送!","admin_ping.php");
	exit;
}
elseif($action=="resetn"){
	$query = "update `sea_news` set n_push=0 ";
    $dsql->ExecuteNoneQuery($query);
	ShowMsg("已将所有新闻状态修改为未推送!","admin_ping.php");
	exit;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>百度主动推送设置</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;百度主动推送设置 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<form action="admin_ping.php?action=set" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">百度主动推送设置</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
<?php  require_once("../data/admin/ping.php"); ?>
网站域名：<input  name="weburl" value="<?php  echo $weburl;?>">&nbsp;&nbsp;&nbsp;&nbsp;
准入密钥：<input name="token" value="<?php  echo $token;?>">&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="确 认" class="btn" >
</td>
</tr>
<tr>
<td width="90%" align="left" height="30" class="td_border">
<a href="?action=v_all"><input type=button value="批量推送新视频" οnclick="window.location.href('?action=v_all')"></a> &nbsp;&nbsp;
<a href="?action=n_all"><input type=button value="批量推送新文章" οnclick="window.location.href('?action=n_all')"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:if(confirm('确定重置所有视频为未推送吗?该操作不可恢复！'))location='?action=reset'"><input type=button value="重置所有视频为未推送"/></a> &nbsp;&nbsp;
<a href="javascript:if(confirm('确定重置所有新闻为未推送吗?该操作不可恢复！'))location='?action=resetn'"><input type=button value="重置所有新闻为未推送" /></a> 
</td>
</tr>

</tbody></table>	
</form>
<?php
if($action==""){viewFoot();}
?>

<?php
//文章推送
if($action=="n_all"){
require_once("../include/common.php");
require_once("../include/main.class.php");
require_once("../data/config.cache.inc.php");
//设置每次推送的条数
$tnum = 10;
$remain = $_REQUEST['remain'];
$remain = isset($remain) ? intval($remain) : $tnum;
/*百度推送系统更新，不再限制推送条数，但remain参数保留，始终为1,为了防止官方把remain参数再次用上，这边也把remain保留，但不影响推送功能 
百度推送好像限制了不能重复推送，否则将禁止该网站推送功能，现在只能每个地址推送一次。如果有的网友想重复推送，请往下看。
if($remain>30)
    $pagesize=30;
else
    $pagesize=$remain;
*/
$pagesize=$tnum;
$wheresql = "where n_push = 0 ";
$trow = $dsql->GetOne("Select count(*) as dd From `sea_news` $wheresql");
$totalnum = $trow['dd'];
//当总页数小于每页数目的时候，替换pagesize
if($totalnum<$pagesize)
    $pagesize = $totalnum;
if(empty($totalpage)) $totalpage=ceil($totalnum/$pagesize);

if($totalnum==0 || $page>$totalpage || $remain==0){
    echo "<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;[文章]恭喜，已推送所有文章!<br /><br />";
	viewFoot();
    exit();
    }

$dsql->SetQuery("Select * From `sea_news` $wheresql order by n_id desc limit 0,$pagesize");
$dsql->Execute('news_list');
$plink ="";
$urls  = array();
$ids  = array();
$result ="{}";
while($row=$dsql->GetObject('news_list'))
{
    $n_id = $row->n_id;
    /*-----------------------------------------------------------------------------------------------------------*/
    $plink = $cfg_basehost.getArticleLink($row->tid,$n_id,''); //来源页面网址
	//die($plink);
    array_push($urls ,$plink);
    array_push($ids ,$n_id);
    @ob_flush();
    @flush();
}

$api = 'http://data.zz.baidu.com/urls?site='.$weburl.'&token='.$token;
$ch = curl_init();
$options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
$result_json = json_decode($result, true);
 
if(isset($result_json["remain"]))
{
    //对ids数组循环，提示推送成功id，更新对应id的n_push=1
    foreach ($ids as $nid){
      $query = "Update `sea_news` set n_push=1 where n_id='$nid'";
      $dsql->ExecuteNoneQuery($query);
      echo '[文章]已成功推送:<font style="color:blue;">'.$nid.'</font>';
      echo '<br />';
    }
    $remaincount = $result_json["remain"];
    echo "<br>[文章]暂停3秒后继续推送<script language=\"javascript\">setTimeout(\"baiduPush();\",3000);function baiduPush(){location.href='?action=n_all&remain=".$remaincount."';}</script><br /><br /></div>";viewFoot();
}
else
{
    echo "<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;[文章]推送已达上限或密钥信息错误！<br /><br />";
    viewFoot();
    exit();
}

}
?>



<?php
//视频推送
if($action=="v_all"){
require_once("../include/common.php");
require_once("../include/main.class.php");
require_once("../data/config.cache.inc.php");
//设置每次推送的条数
$tnum = 10;
$remain = $_REQUEST['remain'];
$remain = isset($remain) ? intval($remain) : $tnum;
/*百度推送系统更新，不再限制推送条数，但remain参数保留，始终为1,为了防止官方把remain参数再次用上，这边也把remain保留，但不影响推送功能 
百度推送好像限制了不能重复推送，否则将禁止该网站推送功能，现在只能每个地址推送一次。如果有的网友想重复推送，请往下看。
if($remain>30)
    $pagesize=30;
else
    $pagesize=$remain;
*/
$pagesize=$tnum;
$wheresql = "where v_push = 0 ";
$trow = $dsql->GetOne("Select count(*) as dd From `sea_data` $wheresql");
$totalnum = $trow['dd'];
//当总页数小于每页数目的时候，替换pagesize
if($totalnum<$pagesize)
    $pagesize = $totalnum;
if(empty($totalpage)) $totalpage=ceil($totalnum/$pagesize);

if($totalnum==0 || $page>$totalpage || $remain==0){
    echo "<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;[视频]恭喜，已推送所有视频!<br /><br />";
	viewFoot();
    exit();
    }

$dsql->SetQuery("Select * From `sea_data` $wheresql order by v_id desc limit 0,$pagesize");
$dsql->Execute('video_list');
$plink ="";
$urls  = array();
$ids  = array();
$result ="{}";
while($row=$dsql->GetObject('video_list'))
{
    $v_id = $row->v_id;
    /*-----------------------------------------------------------------------------------------------------------*/
    $plink = $cfg_basehost.getContentLink($row->tid,$v_id,"",date('Y-n',$row->v_addtime),$row->v_enname); //来源页面网址
	//die($plink);
    array_push($urls ,$plink);
    array_push($ids ,$v_id);
    @ob_flush();
    @flush();
}

$api = 'http://data.zz.baidu.com/urls?site='.$weburl.'&token='.$token;
$ch = curl_init();
$options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
$result_json = json_decode($result, true);
 
if(isset($result_json["remain"]))
{
    //对ids数组循环，提示推送成功id，更新对应id的v_push=1
    foreach ($ids as $vid){
      $query = "Update `sea_data` set v_push=1 where v_id='$vid'";
      $dsql->ExecuteNoneQuery($query);
      echo '[视频]已成功推送:<font style="color:blue;">'.$vid.'</font>';
      echo '<br />';
    }
    $remaincount = $result_json["remain"];
    echo "<br>[视频]暂停3秒后继续推送<script language=\"javascript\">setTimeout(\"baiduPush();\",3000);function baiduPush(){location.href='?action=v_all&remain=".$remaincount."';}</script><br /><br /></div>";viewFoot();
}
else
{
    echo "<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;[视频]推送已达上限或密钥信息错误！<br /><br />";
	viewFoot();
    exit();
}

}
?>

</div>
	</div>
</div>

</body>
</html>