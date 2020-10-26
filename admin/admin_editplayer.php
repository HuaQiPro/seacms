<?php 
require_once(dirname(__FILE__)."/config.php");
if(empty($action))
{
	$action = '';
}

$dirTemplate="../js/player";
if($action=='edit')
{
	if(substr(strtolower($filedir),0,12)!=$dirTemplate){
		ShowMsg("只允许编辑templets目录！","admin_player.php?action=boardsource");
		exit;
	}
	$filetype=getfileextend($filedir);
	if ($filetype!="html" && $filetype!="htm" && $filetype!="js" && $filetype!="css" && $filetype!="txt")
	{
		ShowMsg("操作被禁止！","admin_player.php?action=boardsource");
		exit;
	}
	$filename=substr($filedir,strrpos($filedir,'/')+1,strlen($filedir)-1);
	$content=loadFile($filedir);
	$content = m_eregi_replace("<textarea","##textarea",$content);
	$content = m_eregi_replace("</textarea","##/textarea",$content);
	$content = m_eregi_replace("<form","##form",$content);
	$content = m_eregi_replace("</form","##/form",$content);
	include(sea_ADMIN.'/templets/admin_editplayer.htm');
	exit();
}

elseif($action=='save')
{
	if($filedir == '')
	{
		ShowMsg('未指定要编辑的文件或文件名不合法', '-1');
		exit();
	}
	if(substr(strtolower($filedir),0,12)!=$dirTemplate){
		ShowMsg("只允许编辑player目录！","admin_player.php?action=boardsource");
		exit;
	}
	$filetype=getfileextend($filedir);
	if ($filetype!="html" && $filetype!="htm" && $filetype!="js" && $filetype!="css" && $filetype!="txt")
	{
		ShowMsg("操作被禁止！","admin_player.php?action=boardsource");
		exit;
	}
	$folder=substr($filedir,0,strrpos($filedir,'/'));
	if(!is_dir($folder)){
		ShowMsg("目录不存在！","admin_player.php?action=boardsource");
		exit;
	}
	$content = stripslashes($content);
	$content = m_eregi_replace("##textarea","<textarea",$content);
	$content = m_eregi_replace("##/textarea","</textarea",$content);
	$content = m_eregi_replace("##form","<form",$content);
	$content = m_eregi_replace("##/form","</form",$content);
	createTextFile($content,$filedir);
	ShowMsg("操作成功！","admin_player.php?action=boardsource");
	exit;
}

else
{
	if(empty($path)) $path=$dirTemplate; else $path=strtolower($path);
	if(substr($path,0,12)!=$dirTemplate){
		ShowMsg("只允许编辑player目录！","admin_player.php?action=boardsource");
		exit;
	}
	$flist=getFolderList($path);
	include(sea_ADMIN.'/templets/admin_editplayer.htm');
	exit();
}
?>