<?php 
require_once(dirname(__FILE__)."/config.php");
require_once(sea_DATA."/config.user.inc.php");

if($action=='delall'){
	$query="TRUNCATE sea_search_keywords";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("已经清空全部搜索记录","admin_searchwords.php");
	exit();	
}

include(sea_ADMIN.'/templets/admin_searchwords.htm');
?>