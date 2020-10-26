<?php 
if(!defined('sea_INC'))
{
	exit("Request Error!");
}
require_once(sea_ROOT.'/data/config.cache.inc.php');
if($cfg_cachetype=='redis'){include(sea_INC.'/common.redis.func.php');}else{include(sea_INC.'/common.file.func.php');}
?>