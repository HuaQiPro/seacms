<?php 
session_start();
require_once("include/common.php");
//前置跳转start
$cs=$_SERVER["REQUEST_URI"];
if($GLOBALS['cfg_mskin']==3 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost$cs");}
if($GLOBALS['cfg_mskin']==4 AND $GLOBALS['isMobile']==1){header("location:$cfg_mhost");}
//前置跳转end
require_once(sea_INC."/filter.inc.php");
require_once(sea_INC.'/main.class.php');

if($cfg_feedbackstart=='0'){
	showMsg('对不起，留言暂时关闭','-1');
	exit();
}
?>

<style>
.sea_page{text-align:center; display:block;margin-top:20px;}
.sea_page_box{display:inline-block;zoom:1;*display:inline;margin:auto;}
.sea_page a{float:left;font-family:Tahoma;height:22px;line-height:22px;padding:0 8px;margin-left:3px;background-color:#ddd;border-radius: 4px;font-size: 12px;color: #405884;list-style:none;}
.sea_page a:link {color:#405884;text-decoration:none;}
.sea_page a:visited {color:#405884;text-decoration:none;}
.sea_page a:hover {color:#405884;text-decoration:none;background:#6394c8;}
.sea_page a:active {color:#405884;text-decoration:none;}
.sea_page .sea_num{float:left;font-family:Tahoma;height:22px;line-height:22px;padding:0 8px;margin-left:3px;background-color:#6394c8;border-radius: 4px;font-size: 12px;color: #fff;list-style:none;}

.sea_title{font-size: 16px;margin-bottom:8px; font-weight:bold;}
.sea_main_6{list-style:none;}
.sea_main_8{width:100%;height:60px;border:0;background-color:#F5F5F5;font-size:14px; line-height:22px;font-family:Microsoft YaHei;border: 1px solid #ddd;border-radius:4px; margin-bottom:6px;}
.sea_main_8 input,textarea{
                -webkit-transition: all 0.30s ease-in-out;
                -moz-transition: all 0.30s ease-in-out;
                -ms-transition: all 0.30s ease-in-out;
                -o-transition: all 0.30s ease-in-out;
                outline: none;
                border: #ddd 1px solid;
            }
 
.sea_main_8 input:focus,textarea:focus {
                box-shadow: 0 0 5px #6bb8ee;
                border: #ddd 1px solid;
            }
.sea_postsub{float:right;margin-right:2px;}
.sea_postsub img{align:middle;height:28px; line-height:30px;border:0;border-radius: 4px;}
.sea_main_9{text-transform: uppercase;width: 60px;height:30px;text-align: center;border-radius: 4px;background-color: #F5F5F5;border: 1px solid #ddd;align:middle;}
.sea_main_11{align:middle;cursor:pointer;color: #fff;width: 80px;cursor:pointer;background-color: #3383bc;background: linear-gradient(to right,#247ebe 0,#6bb8ee 100%);height: 28px;border:0; border-radius:4px;}
.sea_listul{display:block;margin-top:10px;}
.sea_pannel{width:100%;overflow:hidden;list-style:none;border-bottom:1px solid #efefef;overflow:hidden;margin:20px 2px;}
.sea_pannel-box{padding:0px;}
.sea_face{float: left;border-radius:50%; width:40px; height:40px;}
.sea_text-name{margin-left: 5px;font-size: 14px;font-weight: bold;line-height:30px;}
.sea_text-muted{margin-left: 5px;font-size: 12px; color:#BBB;}
.sea_text-red{float: right;color: #3295d1;}
.sea_top-line{padding-left:45px;padding-top:3px;line-height:25px;}
.sea_top-line span{color:#ab1010;}
</style>
<?php
if($cfg_feedbackcheck=='1') $needCheck = 0;
else $needCheck = 1;

if(empty($action)) $action = '';
if($action=='add')
{
	$ip = GetIP();
	$dtime = time();
	
	//检查验证码是否正确
if($cfg_feedback_ck=='1')
{	
	$validate = empty($validate) ? '' : strtolower(trim($validate));
	$svali = $_SESSION['sea_ckstr'];
	if($validate=='' || $validate != $svali)
	{
		ResetVdValue();
		ShowMsg('验证码不正确!','-1');
		exit();
	}
}	
	//检查留言间隔时间；
	if(!empty($cfg_feedback_times))
	{
		$row = $dsql->GetOne("SELECT dtime FROM `sea_guestbook` WHERE `ip` = '$ip' ORDER BY `id` DESC ");
		if($dtime - $row['dtime'] < $cfg_feedback_times)
		{
			ShowMsg("发布频繁，请稍后再操作！","-1");
			exit();
		}
	}
	$userid = !empty($userid)?intval($userid):0;
	$uname = trimMsg($m_author);
	$uname =  _Replace_Badword($uname);
	$msg = trimMsg(cn_substrR($m_content, 1024), 1);
	
	if(!preg_match("/[".chr(0xa1)."-".chr(0xff)."]/",$msg)){
		showMsg('你必需输入中文才能发表!','-1');
		exit();
	}
	
	$reid = empty($reid) ? 0 : intval($reid);

	if(!empty($cfg_banwords))
	{
		$myarr = explode ('|',$cfg_banwords);
		for($i=0;$i<count($myarr);$i++)
		{
			$userisok = strpos($uname, $myarr[$i]);
			$msgisok = strpos($msg, $myarr[$i]);
			if(is_int($userisok)||is_int($msgisok))
			{
				showMsg('您发表的评论中有禁用词语!','-1');
				exit();
			}
		}
	}
	
	if($msg=='' || $uname=='') {
		showMsg('你的姓名和留言内容不能为空!','-1');
		exit();
	}
	$title = HtmlReplace( cn_substrR($title,60), 1 );
	if($title=='') $title = '无标题';
		$title = _Replace_Badword($title);

	if($reid != 0)
	{
		$row = $dsql->GetOne("Select msg From `sea_guestbook` where id='$reid' ");
		$msg = "<div class=\\'rebox\\'>".addslashes($row['msg'])."</div>\n".$msg;
	}
	$msg = _Replace_Badword($msg);
	$query = "INSERT INTO `sea_guestbook`(title,mid,uname,uid,msg,ip,dtime,ischeck)
                  VALUES ('$title','{$g_mid}','$uname','$userid','$msg','$ip','$dtime','$needCheck'); ";
	$dsql->ExecuteNoneQuery($query);
	if($needCheck==1)
	{
		ShowMsg('感谢您的留言，我们会尽快回复您！','gbook.php',0,3000);
		exit();	
	}
	else
	{
		ShowMsg('成功发送一则留言，但需审核后才能显示！','gbook.php',0,3000);
		exit();
	}
}
//显示所有留言
else
{
	if($key!=''){
	$key="您好，我想看".HtmlReplace($key).",多谢了";
	$title="求片";
	}else{
	$key='';
	$title='';
	}
	$page=empty($page) ? 1 : intval($page);
	if($page==0) $page=1;
	$tempfile = sea_ROOT."/templets/".$GLOBALS['cfg_df_style']."/".$GLOBALS['cfg_df_html']."/gbook.html";
	if($GLOBALS['cfg_mskin']!=0 AND $GLOBALS['cfg_mskin']!=3 AND $GLOBALS['cfg_mskin']!=4  AND $GLOBALS['isMobile']==1)
	{$tempfile = sea_ROOT."/templets/".$GLOBALS['cfg_df_mstyle']."/".$GLOBALS['cfg_df_html']."/gbook.html";}
	$content=loadFile($tempfile);
	$t=$content;
	$t=$mainClassObj->parseTopAndFoot($t);
	$t=$mainClassObj->parseHistory($t);
	$t=$mainClassObj->parseSelf($t);
	$t=$mainClassObj->parseGlobal($t);
	$t=$mainClassObj->parseAreaList($t);
	$t=$mainClassObj->parseMenuList($t,"");
	$t=$mainClassObj->parseVideoList($t,-444,'','');
	$t=$mainClassObj->parseNewsList($t,-444,'','');
	$t=$mainClassObj->parseTopicList($t);
	$t=replaceCurrentTypeId($t,-444);
	$t=$mainClassObj->parseIf($t);
	if($cfg_feedback_ck=='1')
	{$t=str_replace("{gbook:viewLeaveWord}",viewLeaveWord2(),$t);}
	else
	{$t=str_replace("{gbook:viewLeaveWord}",viewLeaveWord(),$t);}
	$t=str_replace("{gbook:main}",viewMain(),$t);
	$t=str_replace("{seacms:runinfo}",getRunTime($t1),$t);
	$t=str_replace("{seacms:member}",front_member(),$t);
	echo $t;
	exit();
}

function viewMain(){
	$main="<div class='leaveNavInfo'><h3><span id='adminleaveword'></span>".$GLOBALS['cfg_webname']."留言板</h3></div>";
	return $main;
}

function viewLeaveWord(){
	if(!empty($_SESSION['sea_user_name']))
	{
		$uname=$_SESSION['sea_user_name'];
		$userid =$_SESSION['sea_user_id'];
	}
	
	$mystr=
	
	"<div class=\"sea_main\"><div class=\"sea_main_1\"><div class=\"sea_main_2\">".
	"<div class=\"sea_main_3\"><div class=\"sea_main_4\"><div class=\"sea_title\" id=\"sea_title\">我要留言</div></div></div>".
	"<form id=\"f_leaveword\"   action=\"/".$GLOBALS['cfg_cmspath']."gbook.php?action=add\" method=\"post\" onsubmit=\"return leaveWordgbook2()\">".
	"<input type=\"hidden\" value=\"$userid\" name=\"userid\" />".
	"<input type=\"hidden\" value=\"$uname\" name=\"m_author\" />".
	"<div class=\"sea_main_5\"><div class=\"sea_main_6\">". 
	"<div style=\"display:none;\">".
	"".(isset($uname)?$uname:'<input class="sea_main_7" type="input"  value="匿名网友" name="m_author" id="m_author" size="20" />').
	"</div>".
	  
	  "<div>".
		 	"<textarea class=\"sea_main_8\" placeholder=\"请输入留言内容...\"  name=\"m_content\" id=\"m_content\"></textarea>".
	  "</div>".
	  
	  "<div class=\"sea_postsub\">".
		"<input type=\"submit\"  value=\"提交留言\" class=\"sea_main_11\"/></div>".
	"</div></div></form>".
	"</div></div></div>".
	"<div class=\"sea_main_12\">".leaveWordList($_GET['page'])."</div><script type=\"text/javascript\" src=\"js/base.js\"></script>";
	return $mystr;
}


//开启验证码
function viewLeaveWord2(){
	if(!empty($_SESSION['sea_user_name']))
	{
		$uname=$_SESSION['sea_user_name'];
		$userid =$_SESSION['sea_user_id'];
	}
	
	$mystr=
	
	"<div class=\"sea_main\"><div class=\"sea_main_1\"><div class=\"sea_main_2\">".
	"<div class=\"sea_main_3\"><div class=\"sea_main_4\"><div class=\"sea_title\" id=\"sea_title\">我要留言</div></div></div>".
	"<form id=\"f_leaveword\"   action=\"/".$GLOBALS['cfg_cmspath']."gbook.php?action=add\" method=\"post\" onsubmit=\"return leaveWordgbook()\">".
	"<input type=\"hidden\" value=\"$userid\" name=\"userid\" />".
	"<input type=\"hidden\" value=\"$uname\" name=\"m_author\" />".
	"<div class=\"sea_main_5\"><div class=\"sea_main_6\">". 
	"<div style=\"display:none;\">".
	"".(isset($uname)?$uname:'<input class="sea_main_7" type="input"  value="匿名网友" name="m_author" id="m_author" size="20" />').
	"</div>".
	  
	  "<div class=\"sea_posttxt\">".
		 	"<textarea class=\"sea_main_8\" placeholder=\"请输入留言内容...\"  name=\"m_content\" id=\"m_content\"></textarea>".
	  "</div>".
	  
	  "<div class=\"sea_postsub\">".
		 	"<input name=\"validate\" type=\"text\" placeholder=\"验证码\" class=\"sea_main_9\" id=\"vdcode\" style=\"text-transform:uppercase;\" onClick=\"document.getElementById('vdimgck').style.display='inline';\" tabindex=\"3\"/>&nbsp;<img id=\"vdimgck\" src=\"include/vdimgck.php\" alt=\"看不清？点击更换\"  align=\"absmiddle\"  style=\"cursor:pointer;display:none;\" onClick=\"this.src=this.src+'?get=' + new Date()\"/><input type=\"submit\"  value=\"提交留言\" class=\"sea_main_11\"/> </div>".
	"</div></div></form>".
	"</div></div></div>".
	"<div class=\"sea_main_12\">".leaveWordList($_GET['page'])."</div><script type=\"text/javascript\" src=\"js/base.js\"></script>";
	return $mystr;
}
function leaveWordList($currentPage){
	global $dsql,$cfg_gb_size;
	$vsize=20;
	$cfg_gb_size=intval($cfg_gb_size);
	if($cfg_gb_size !="" and $cfg_gb_size !=0){$vsize=$cfg_gb_size;}
	if($currentPage<=1)
	{
		$currentPage=1;
	}
	$limitstart = ($currentPage-1) * $vsize;
	$sql="select * from `sea_guestbook` where ischeck='1' ORDER BY id DESC limit $limitstart,$vsize";	
	$cquery = "Select count(*) as dd From `sea_guestbook` where ischeck='1'";
	$row = $dsql->GetOne($cquery);
	if(is_array($row))
	{
		$TotalResult = $row['dd'];
	}
	else
	{
		$TotalResult = 0;
		$txt="<ul><li class=\"words\">当前没有留言</li></ul>";
	}
	$TotalPage = ceil($TotalResult/$vsize);
	$dsql->SetQuery($sql);
	$dsql->Execute('leaveWordList');
	$ii=$limitstart+1;
	while($row=$dsql->GetObject('leaveWordList')){
	
	$i=$ii++;
	$iii=$TotalResult-$i;
	$iiii=$iii+1;
	$picsql = "Select pic From `sea_member` where username='$row->uname'";
	$picrow = $dsql->GetOne($picsql);
	if($picrow['pic']==""){$pic='uploads/user/a.png';}else{$pic=$picrow['pic'];}
	$txt.="<li class=\"sea_pannel\"><div class=\"sea_pannel-box\"><div class=\"sea_c-pd\"><div class=\"sea_topwords\"><span class=\"sea_text-name\"><img class=\"sea_face\" src=\"/".$pic."\">".$row->uname."</span><span class=\"sea_text-muted\">发表于 ".MyDate('',$row->dtime)."</span><span class=\"sea_text-red\">#".$iiii."</span></div><div class=\"sea_top-line\">".showFace($row->msg)."</div></div></div></li>";
	//$i--;
	}
	unset($i);
	$txt.="<div class=\"sea_page\"><div class=\"sea_page_box\">";
	if($currentPage==1)$txt.="<a>‹‹</a><a>‹</a>";
	else $txt.="<a href=\"/".$GLOBALS['cfg_cmspath']."gbook.php?page=1\">‹‹</a><a href=\"/".$GLOBALS['cfg_cmspath']."gbook.php?page=".($currentPage-1)."\">‹</a>";
	if($TotalPage==1)
	{
		$txt.="<span class=\"sea_num\">1</span>";
	}else{
	$x=$currentPage-1;
	$y=$currentPage+1;
	if($x<1)$x=1;
	if($y>$TotalPage)$y=$TotalPage;
	for($i=$x;$i<=$y;$i++)
	{
		if($i == $currentPage)
			{$txt.="<span class=\"sea_num\">".$i."</span>";}
		else
			{$txt.="<a href=\"/".$GLOBALS['cfg_cmspath']."gbook.php?page=".$i."\">".$i."</a>";}
	}	
	}
	if($currentPage==$TotalPage)$txt.="<a>›</a><a>››</a>";
	else $txt.="<a href=\"/".$GLOBALS['cfg_cmspath']."gbook.php?page=".($currentPage+1)."\">›</a><a href=\"/".$GLOBALS['cfg_cmspath']."gbook.php?page=".$TotalPage."\">››</a>";
	return $txt."</div></div>";

}


?>