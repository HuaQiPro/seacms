<?php 
require_once(dirname(__FILE__)."/config.php");
require_once(sea_INC.'/main2.class.php');
CheckPurview();
if(empty($action))
{
	$action = '';
}

$id = empty($id) ? 0 : intval($id);

if($action=="add")
{
	if(empty($name))
	{
		ShowMsg("专题名称没有填写，请返回检查","-1");
		exit();
	}
	$name = str_replace('\'', ' ', $name);
	if(empty($template)) $template='topic.html';
	if(empty($pic)) $pic='uploads/zt/zt.jpg';
	if(empty($spic)) $spic='uploads/zt/zt.jpg';
	if(empty($gpic)) $gpic='uploads/zt/zt.jpg';
	if(empty($vod)) $vod='0';
	if(empty($news)) $news='0';
	if(empty($enname)) $enname=Pinyin(stripslashes($name));;
	$addtime=time();
	if(empty($sort)) 
		{
		$trow = $dsql->GetOne("select max(sort)+1 as dd from sea_topic");
		$sort = $trow['dd'];
		}
	if (!is_numeric($sort)) $sort=1;
	$addtime=time();
	$in_query = "insert into `sea_topic`(name,enname,template,pic,spic,gpic,sort,vod,news,keyword,des,content,addtime) Values('$name','$enname','$template','$pic','$spic','$gpic','$sort',0,0,'$keyword','$des','$content','$addtime')";
	if(!$dsql->ExecuteNoneQuery($in_query))
	{
		ShowMsg("增加专题失败，请检查您的输入是否存在问题！","-1");
		exit();
	}
	clearTopicCache();
	ShowMsg("成功创建一个专题！","admin_topic.php");
	exit();
}
elseif($action=="ztadd"){
include(sea_ADMIN.'/templets/admin_topic_ztadd.htm');
}
elseif($action=="last")
{
	$row=$dsql->GetOne("select sort from `sea_topic` where id='$id'");
	$cur=$row['sort'];
	$row=$dsql->GetOne("select count(*) as dd from `sea_topic` where sort<'$cur'");
	$cou=$row['dd'];
	if($cou>0)
	{
		$row=$dsql->GetOne("select sort from `sea_topic` where sort<'$cur' order by sort desc");
		$flag=$row['sort'];
		$dsql->ExecuteNoneQuery("update `sea_topic` set sort='$flag' where id='$id'");
	}
	else
	{
		$dsql->ExecuteNoneQuery("update `sea_topic` set sort=sort-1 where id='$id'");
	}
	header("Location:admin_topic.php?id=$id");
	exit;
}
elseif($action=="next")
{
	$row=$dsql->GetOne("select sort from `sea_topic` where id='$id'");
	$cur=$row['sort'];
	$row=$dsql->GetOne("select count(*) as dd from `sea_topic` where sort>'$cur'");
	$cou=$row['dd'];
	if($cou>0)
	{
		$row=$dsql->GetOne("select sort from `sea_topic` where sort>'$cur' order by sort desc");
		$flag=$row['sort'];
		$dsql->ExecuteNoneQuery("update `sea_topic` set sort='$flag' where id='$id'");
	}
	else
	{
		$dsql->ExecuteNoneQuery("update `sea_topic` set sort=sort+1 where id='$id'");
	}
	header("Location:admin_topic.php?id=$id");
	exit;
}
elseif($action=="del")
{
	$dsql->ExecuteNoneQuery("delete from `sea_topic` where id='$id'");
	$dsql->ExecuteNoneQuery("update `sea_data` set v_topic=0 where v_topic='$id'");
	clearTopicCache();
	header("Location:admin_topic.php?id=$id");
	exit;
}
elseif($action=="delall")
{
	if(empty($e_id))
	{
		ShowMsg("请选择需要删除的专题","admin_topic.php");
		exit();
	}
	$ids = implode(',',$e_id);
	$dsql->ExecuteNoneQuery("delete from `sea_topic` where id in ($ids)");
	$dsql->ExecuteNoneQuery("update `sea_data` set v_topic=0 where v_topic in ($ids)");
	clearTopicCache();
	header("Location:admin_topic.php");
	exit;
}
elseif($action=="edit")
{
	if(empty($e_id))
	{
		ShowMsg("请选择需要修改的专题","admin_topic.php");
		exit();
	}
	foreach($e_id as $id)
	{
		
		$sort=$_POST["sort$id"];
	
	if(empty($sort)) 
		{
		$trow = $dsql->GetOne("select max(torder)+1 as dd from sea_topic");
		$sort = $trow['dd'];
		}
	if (!is_numeric($sort)) $sort=1;
	$dsql->ExecuteNoneQuery("update sea_topic set sort='$sort' where id=".$id);
	}
	clearTopicCache();
	header("Location:admin_topic.php");
	exit;
}

elseif($action=="ztedit"){
include(sea_ADMIN.'/templets/admin_topic_ztedit.htm');
}
elseif($action=="zteditsave"){
	$id=$_POST['id'];
	$content=$_POST['content'];
	if(empty($id))
	{
		ShowMsg("请选择需要修改的专题","admin_topic.php");
		exit();
	}
	if(empty($name))
	{
		ShowMsg("专题名称没有填写，请返回检查","-1");
		exit();
	}
	$addtime=time();
	$dsql->ExecuteNoneQuery("update sea_topic set name='$name',enname='$enname',template='$template',pic='$pic',spic='$spic',gpic='$gpic',keyword='$keyword',des='$des',content='$content',addtime='$addtime' where id=".$id);
	if ($GLOBALS ['cfg_runmode'] == '0') {
		ShowMsg("成功修改专题，转到生成页面","?action=makezt&ztid=".$id);
	}
	else
	{ShowMsg("成功修改专题","admin_topic.php");}
	exit();
}
elseif($action=="makezt"){
makeTopicById($ztid);
echo '<script>window.location.href="admin_topic.php";</script>';
exit();
}

else
{
include(sea_ADMIN.'/templets/admin_topic.htm');
exit();
}

function isztMake($enname,$ztid)
{
	if ($GLOBALS ['cfg_runmode'] == '0') {
	$topicLink = "/" . $GLOBALS ['cfg_cmspath'] . $GLOBALS ['cfg_filesuffix'] . "/" . $enname . $GLOBALS ['cfg_filesuffix2'];
	}
	$contentUrl=$topicLink;
	echo "<a href=\"?action=makezt&ztid=$ztid\">";
	if(file_exists('..'.$contentUrl)){
		echo "<img src='img/yes.gif' border='0' title='点击生成HTML' />";
	}else{
		echo "<img src='img/no.gif' border='0' title='点击生成HTML' />";
	}
	echo "</a>";
}

function clearTopicCache()
{
	global $cfg_iscache,$cfg_cachemark;
	if($cfg_iscache)
	{
		$TypeCacheFile=sea_DATA."/cache/".$cfg_cachemark.md5('array_Topic_Lists_all').".inc";
		if(is_file($TypeCacheFile)) unlink($TypeCacheFile);
	}
}
?>