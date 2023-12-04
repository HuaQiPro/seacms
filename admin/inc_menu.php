<?php 
if(!defined('sea_ADMIN'))
{
	exit("Request Error!");
}
$defaultIcoFile = sea_ROOT.'/data/admin/quickmenu.txt';
$myIcoFile = sea_ROOT.'/data/admin/quickmenu-'.$cuserLogin->getUserID().'.txt';
if(!file_exists($myIcoFile)) {
	$myIcoFile = $defaultIcoFile;
}
$add = array();
$fp = fopen($myIcoFile,'r');
$dtp = trim(fread($fp,filesize($myIcoFile)+1));
fclose($fp);
$dtp=str_replace(chr(13).chr(10),"#",$dtp);
$menu_temp=explode("#", $dtp);
foreach ($menu_temp as $i=>$value) {
	if($value<>""){
	$qmenu=explode(",", $value);
	$add[$i]="<a href=". $qmenu[1]. " target=I2>".$qmenu[0]."</a>";
	}else
	{
	$add[$i]="";
	}
}


$menu=array (
	'common'=>array(
		'link'=>"index_body.php",
		0=>"首页",
		1=>"<a target='I2' href='index_body.php'>后台首页</a>",
		2=>"<a target='I2' href='admin_info.php'>数据统计</a>",	
		3=>"<a target='I2' href='admin_searchwords.php'>搜索统计</a>",	
		4=>"<a target='I2' href='admin_menu.php'>快捷菜单</a>",	
		
		5=>"",
	),
	'content'=>array (
		'link'=>"admin_video.php?action=else",
		0=>"数据",
		1=>"<a target='I2' href='admin_tempvideo.php'>临时表管理</a>",
		2=>"",
		3=>"<a target='I2' href='admin_type.php'>分类管理</a>",
		4=>"<a target='I2' href='admin_jqtype.php'>剧情分类</a>",
		5=>"",	
		6=>"<a target='I2' href='admin_video.php?action=else'>数据管理</a>",
		7=>"<a target='I2' href='admin_video.php?action=add'>添加数据</a>",
		8=>"<a target='I2' href='admin_video.php?v_state=ok'>连载数据</a>",
		9=>"<a target='I2' href='admin_video.php?v_commend=ok'>推荐数据</a>",
		10=>"<a target='I2' href='admin_video.php?v_isunion=ok'>锁定数据</a>",
		11=>"<a target='I2' href='admin_video.php?v_recycled=ok'>隐藏数据</a>",
		12=>"<a target='I2' href='admin_video.php?v_ispsd=ok'>有密码数据</a>",
		13=>"<a target='I2' href='admin_video.php?v_ismoney=ok'>消费点数据</a>",	
		14=>"",			
		15=>"<a target='I2' href='admin_pseudo.php'>伪原创设置</a>",
		16=>"<a target='I2' href='admin_plus.php'>增强管理</a>",
		17=>"",	
		18=>"<a target='I2' href='admin_topic.php'>专题管理</a>",
		19=>"<a target='I2' href='admin_topic.php?action=ztadd'>专题添加</a>",
		20=>"",
		21=>"<a target='I2' href='admin_type_news.php'>新闻分类</a>",
		22=>"<a target='I2' href='admin_news.php'>新闻管理</a>",
		23=>"<a target='I2' href='admin_news.php?action=add'>新闻添加</a>",
		24=>"<a target='I2' href='admin_news.php?n_recycled=ok'>隐藏数据</a>",
		25=>"<a target='I2' href='admin_news.php?n_commend=ok'>推荐数据</a>",
		
	),
	'template'=>array (
		'link'=>"admin_template.php?action=main",
		0=>"模板",
		1=>"<a target='I2' href='admin_template.php?action=main'>模板管理</a>",
		2=>"<a target='I2' href='admin_template.php?action=custom'>管理自定义模版</a>",
		3=>"",
		4=>"<a target='I2' href='admin_selflabel.php'>自定义标签</a>",
		5=>"",
		6=>"<a target='I2' href='admin_labelguide.php'>标签向导</a>",
	),
	'make'=>array (
		'link'=>"admin_makehtml.php?action=main",
		0=>"生成",
		1=>"<a target='I2' href='admin_makehtml.php?action=main'>生成选项</a>",
		2=>"",
		3=>"<a target='I2' href='admin_makehtml.php?action=baidu'>生成视频地图</a>",
		4=>"<a target='I2' href='admin_makehtml.php?action=baidun'>生成新闻地图</a>",
		5=>"<a target='I2' href='admin_makehtml.php?action=google'>生成视频谷歌地图</a>",
		6=>"<a target='I2' href='admin_makehtml.php?action=googlen'>生成新闻谷歌地图</a>",
		7=>"<a target='I2' href='admin_makehtml.php?action=rss'>生成视频RSS</a>",
		8=>"<a target='I2' href='admin_makehtml.php?action=rssn'>生成新闻RSS</a>",
		9=>"<a target='I2' href='admin_makehtml.php?action=baidux'>生成视频百度站内</a>",
		10=>"<a target='I2' href='admin_makehtml.php?action=baiduxn'>生成新闻百度站内</a>",
	),
	'user'=>array (
		'link'=>"admin_members.php",
		0=>"用户",
		1=>"<a target='I2' href='admin_members.php'>用户搜索</a>",
		2=>"<a target='I2' href='admin_memberslist.php'>用户列表</a>",
		3=>"<a target='I2' href='admin_members_group.php'>用户组管理</a>",
		4=>"<a target='I2' href='admin_pay.php'>充值卡管理</a>",
		5=>"<a target='I2' href='admin_paylog.php'>用户充值记录</a>",
		6=>"<a target='I2' href='admin_vpaylog.php'>视频购买记录</a>",
		7=>"<a target='I2' href='admin_hyzlog.php'>会员购买记录</a>",
		8=>"<a target='I2' href='admin_notify.php'>会员消息通知</a>",
	),
	'tool'=>array (
		'link'=>"admin_datarelate.php?action=repeat",
		0=>"工具",
		1=>"<a target='I2' href='admin_datarelate.php?action=repeat'>影片数据检测</a>",
		2=>"<a target='I2' href='admin_datarelate.php?action=batch'>数据批量替换</a>",
		3=>"<a target='I2' href='admin_datarelate.php?action=delvideoform'>删除指定来源</a>",
		4=>"<a target='I2' href='admin_datarelate.php?action=repairplaydata'>修复数据格式</a>",		
		5=>"<a target='I2' href='admin_datarelate.php?action=randomsetscore'>批量设置评分</a>",
		6=>"<a target='I2' href='admin_datarelate.php?action=randomset'>批量设置点击量</a>",
		7=>"",
		8=>"<a target='I2' href='admin_datarelate.php?action=checkpic'>图片管理</a>",
		9=>"<a target='I2' href='admin_files.php'>上传文件管理</a>",
		10=>"<a target='I2' href='admin_datarelate.php?action=fileperms'>文件权限检查</a>",
		11=>"<a target='I2' href='admin_safe.php'>PHP木马扫描</a>",
		12=>"",
		13=>"<a target='I2' href='admin_datarelate.php?action=sql'>SQL高级助手</a>",
		14=>"<a target='I2' href='ebak/ChangeDb.php?act=b'>数据库备份</a>",
		15=>"<a target='I2' href='ebak/ReData.php'>数据库还原</a>",
		16=>"<a target='I2' href='ebak/ChangePath.php'>备份文件管理</a>",
		17=>"<a target='I2' href='ebak/ChangeDb.php?act=y'>数据库修复优化</a>",
	),
	'gathersoft'=>array (
		'link'=>"api.php",
		0=>"采集",
		1=>"<a target='I2' href='api.php'>资源库列表</a>",
		2=>"<a target='I2' href='admin_zyk.php'>资源库管理</a>",
		3=>"<a target='I2' href='admin_delunionid.php'>清除分类绑定</a>",
		4=>"<a target='I2' href='admin_cron.php'>定时任务自动版</a>",
		5=>"<a target='I2' href='admin_auto.php'>定时任务挂机版</a>",		
		6=>"",
		7=>"<a target='I2' href='admin_collect.php?action=main'>视频项目列表</a>",
		8=>"<a target='I2' href='admin_collect.php?action=add'>添加视频项目</a>",
		9=>"<a target='I2' href='admin_collect.php?action=customercls'>视频分类转换</a>",
		10=>"<a target='I2' href='admin_collect.php?action=filters'>视频信息过滤</a>",
		11=>"<a target='I2' href='admin_collect.php?action=tempdatabase'>视频采集数据库</a>",
		12=>"<a target='I2' href='admin_collect.php?action=importrule'>导入采集规则</a>",
		13=>"",
		14=>"<a target='I2' href='admin_collect_news.php?action=main'>新闻项目列表</a>",
		15=>"<a target='I2' href='admin_collect_news.php?action=add'>添加新闻项目</a>",
		16=>"<a target='I2' href='admin_collect_news.php?action=customercls'>新闻分类转换</a>",
		17=>"<a target='I2' href='admin_collect_news.php?action=filters'>新闻信息过滤</a>",
		18=>"<a target='I2' href='admin_collect_news.php?action=tempdatabase'>新闻采集数据库</a>",
		19=>"<a target='I2' href='admin_collect_news.php?action=importrule'>导入新闻规则</a>",
				
	),
	'webhelper'=>array (
		'link'=>"admin_comment.php?action=gbook",
		0=>"扩展",
		1=>"<a target='I2' href='admin_comment.php?action=gbook'>留言管理</a>",
		2=>"<a target='I2' href='admin_comment.php?action=reporterror'>报错数据</a>",
		3=>"<a target='I2' href='admin_comment.php'>视频评论管理</a>",
		4=>"<a target='I2' href='admin_comment_news.php'>新闻评论管理</a>",
		5=>"<a target='I2' href='admin_expand.php'>幻灯片管理</a>",
		6=>"",
		7=>"<a target='I2' href='admin_ads.php?action=main'>广告管理</a>",
		8=>"<a target='I2' href='admin_ads.php?action=add'>添加广告</a>",
		9=>"",
		10=>"<a target='I2' href='admin_ping.php'>百度推送</a>",
		11=>"",
		12=>"<a target='I2' href='admin_link.php'>友情链接</a>",
	),
	'system'=>array (
		'link'=>"admin_config.php",
		0=>"系统",
		1=>"<a target='I2' href='admin_config.php'>网站设置</a>",
		2=>"",
		3=>"<a target='I2' href='admin_config_mark.php'>图片水印设置</a>",
		4=>"<a target='I2' href='admin_datarelate.php?action=ftppic'>远程图片设置</a>",
		5=>"",
		6=>"<a target='I2' href='admin_player.php'>播放器设置</a>",
		7=>"<a target='I2' href='../js/player/dmplayer/admin/'>弹幕播放管理</a>",
		8=>"<a target='I2' href='admin_player.php?action=boardsource'>播放来源管理</a>",
		9=>"<a target='I2' href='admin_playerdown.php?action=boardsource'>下载来源管理</a>",
		10=>"",
		11=>"<a target='I2' href='admin_manager.php'>系统账号管理</a>",
		12=>"<a target='I2' href='admin_vcode.php'>后台登陆验证码</a>",
		13=>"<a target='I2' href='admin_isapi.php'>资源库API设置</a>",
		14=>"<a target='I2' href='admin_ip.php'>后台IP安全设置</a>",
		15=>"<a target='I2' href='admin_weixin.php'>微信公众号设置</a>",
		16=>"<a target='I2' href='admin_smtp.php'>邮件服务器设置</a>",
	),
);
$menuedit=array(
	'editor'=>array(
		0=>"<a target='I2' href='index_body.php'>后台首页</a>",
		1=>"<a target='I2' href='admin_menu.php'>快捷菜单</a>",
		2=>"<a target='I2' href='admin_video.php?action=else'>数据管理</a>",
		3=>"<a target='I2' href='admin_video.php?action=add'>添加数据</a>",
		4=>"<a target='I2' href='admin_makehtml.php?action=main'>生成选项</a>",
		5=>"",
	),
);

$menu['common']= array_merge($menu['common'],$add);


?>