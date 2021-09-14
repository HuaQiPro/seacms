<?php
define('sea_ADMIN', preg_replace("|[/\\\]{1,}|",'/',dirname(__FILE__) ) );
require_once(sea_ADMIN."/../include/common.php");
require_once(sea_ADMIN."/coplugins/Snoopy.class.php");
header("Cache-Control:private");
error_reporting(0);



//检验用户登录状态
if(!isset($_REQUEST['pwd']) || trim($_REQUEST['pwd']) != '123456'){
	die('deny!');
}

//栏目分类列表
if(isset($_REQUEST["list"])){
	echo '<select name="">';
	echo makeTypeOptionSelected(0, '&nbsp;&nbsp;', '', '', 1);
	echo "</select>";
	die();
}


//文章分类
	if (isset($_REQUEST['v_type']) && intval($_REQUEST['v_type']) != 0) {
		$tid = intval($_REQUEST['v_type']);
	}else{
		die('所属分类不能为空！');
	}
	
//文章标题
	$n_title = $_REQUEST['n_title'];
	if (empty($n_title))
	{
		die('标题不能为空！');
	}


//图片地址
	if (isset($_REQUEST['n_pic']) && trim($_REQUEST['n_pic']) != '') {
		$n_pic=  $_REQUEST['n_pic'];
	}

	$n_hit = empty($n_hit) ? 0 : intval($n_hit);
	$n_addtime = time();
	$n_money = empty($n_money) ? 0 : intval($n_money);
	$n_rank = empty($n_rank) ? 0 : intval($n_rank);
	$n_title = htmlspecialchars(cn_substrR($n_title,60));
	$n_author = cn_substrR($n_author,200);
	$n_note = cn_substrR($n_note,30);
	$n_outline = cn_substrR($n_outline,200);
	$n_keyword = cn_substrR(strtolower(addslashes($n_keyword)),30);
	$n_keyword = str_replace('，', ',', $n_keyword);
	$n_keyword = str_replace(',,', ',', $n_keyword);
	$n_from = cn_substrR($n_from,10);
	$n_commend = empty($n_commend) ? 0 : intval($n_commend);
	if(empty($n_entitle))
	{
		$n_entitle = Pinyin($n_title); 
	}
	$n_letter = strtoupper(substr($n_entitle,0,1));
	if (substr($n_keyword, -1) == ',') {
		$n_keyword = substr($n_keyword, 0, strlen($n_keyword)-1);
	}
	$n_pic = cn_substrR($n_pic,100);


//写入数据库

$Sql = "insert into sea_news(tid,n_title,n_letter,n_hit,n_money,n_rank,n_author,n_color,n_pic,n_addtime,n_note,n_from,n_entitle,n_keyword,n_outline,n_content,n_commend) values ('$tid','$n_title','$n_letter','$n_hit','$n_money','$n_rank','$n_author','$n_color','$n_pic','$n_addtime','$n_note','$n_from','$n_entitle','$n_keyword','$n_outline','$n_content','$n_commend')";
			
if($dsql->ExecuteNoneQuery($Sql))
		{
			$n_id = $dsql->GetLastID();
			addtags($n_keyword,$n_id);
			echo '采集成功';
		}
		else
		{
		echo '写入数据库失败';
		}



			
function addtags($v_tags,$v_id)
{
	global $dsql;
	if($v_tags)
	{
		if(strpos($v_tags,',')>0)
		{
			$tagdb = explode(',', $v_tags);
		}else{
			$tagdb = explode(' ', $v_tags);
		}
		$tagnum = count($tagdb);
		for($i=0; $i<$tagnum; $i++)
		{
			$tagdb[$i] = trim($tagdb[$i]);
			if ($tagdb[$i]) 
			{
				$tag = $dsql->GetOne("SELECT tagid,vids FROM sea_tags WHERE tag='$tagdb[$i]'");
				if(!$tag) {
					$dsql->ExecuteNoneQuery("INSERT INTO sea_tags (tag,usenum,vids) VALUES ('$tagdb[$i]', '1', '$v_id')");
				}else{
					$vids = $tag['vids'].','.$v_id;
					$dsql->ExecuteNoneQuery("UPDATE sea_tags SET usenum=usenum+1, vids='$vids' WHERE tag='$tagdb[$i]'");
				}
			}
			unset($vids);
		}
	}
}

function makeTypeOptionSelected($topId,$separateStr,$span="",$compareValue,$tptype=1)
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

?>