<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="display:none;">
<?php 
if(!defined('sea_INC'))
{
	exit("Request Error!");
}
require_once(sea_INC."/collection.func.php");
require_once(sea_DATA."/mark/inc_photowatermark_config.php");
 
//开始采集列表
autogetlistsbyid($collectID);
//开始采集内容
autogetconbyid($collectID);
//清理缓存
autocache_clear(sea_ROOT.'/data/cache');



function autogetlistsbyid($id)
{
	@session_write_close();
	if($id==0) return false;
	global $dsql,$collectPageNum;
	$row = $dsql->GetOne("Select t.coding,t.sock,t.playfrom,t.autocls,t.classid,t.getherday,t.listconfig,c.cid,c.getlistnum from `sea_co_type` t left join `sea_co_config` c on c.cid=t.cid where t.tid='$id'");
	
	//print_r($row);die;
	
	$listconfig=$row['listconfig'];
	$coding=$row['coding'];
	$sock=$row['sock'];
	$playfrom=$row['playfrom'];
	$classid=$row['classid'];
	$autocls=$row['autocls'];
	$getherday=$row['getherday'];
	$cid=$row['cid'];
	$getlistnum=$row['getlistnum'];
	$labelRule = buildregx("{seacms:listrule(.*?)}(.*?){/seacms:listrule}","is");
	preg_match_all($labelRule,$listconfig,$ar);
	$attrStr = trim(preg_replace("/[ \r\n\t\f]{1,}/"," ",$ar[1][0]));
	$loopstr=$ar[2][0];
	$attrDictionary=parseAttr($attrStr);
	$lista=getrulevalue($loopstr,"lista");
	$listb=getrulevalue($loopstr,"listb");
	$mlinka=getrulevalue($loopstr,"mlinka");
	$mlinkb=getrulevalue($loopstr,"mlinkb");
	$picmode=getrulevalue($loopstr,"picmode");
	$pica=getrulevalue($loopstr,"pica");
	$picb=getrulevalue($loopstr,"picb");
	$pic_trim=getrulevalue($loopstr,"pic_trim");
	
	//处理页面链接
	$pageset=$attrDictionary["pageset"];
	if($pageset==0){
		$pageurl0=$attrDictionary["pageurl0"];
		$istart=0;
		$iend=0;
		$dourl[0][0]=$pageurl0;
	}else{
		$pageurl1=$attrDictionary["pageurl1"];
		$pageurl2=$attrDictionary["pageurl2"];
		$istart=$attrDictionary["istart"];
		$iend=$attrDictionary["iend"];
		$pageurlarr=GetUrlFromListRule($pageurl1,$pageurl2,$istart,$iend);
		$dourl=$pageurlarr;
	}
	$k=count($dourl);
	if (is_numeric($collectPageNum)>$k) $collectPageNum=$k;
	if (is_numeric($collectPageNum)<=0) $collectPageNum=$k;

	if ($collectPageNum>=0)
	{
		for ($i=0; $i<$collectPageNum; $i++)
		{
			$listurl =$dourl[$i][0];
			$html = cget($listurl,$sock);
			$html = ChangeCode($html,$coding);
			
			if($html=='')
			{
				//echo "读取网址： $listurl 时失败！\r\n";
			}
			if( trim($lista) !='' && trim($listb) != '' )
			{
				$areabody = $lista.'[var:区域]'.$listb;
				$html = GetHtmlArea('[var:区域]',$areabody,$html);
			}
			if( trim($mlinka) !='' && trim($mlinkb) != '' )
			{
				$linkrulex = $mlinka.'(.*)'.$mlinkb;
				$link = GetHtmlarray($html,$linkrulex);
				foreach($link as $s)
				{
					$links[][url] = FillUrl($listurl,$s);
				}
			}
			if(trim($picmode)==1 && trim($pica) !='' && trim($picb) != '' )
			{
				$picrulex = $pica.'(.*)'.$picb;
				$piclink = GetHtmlarray($html,$picrulex);
				foreach($piclink as $key=>$s)
				{
					if(!empty($pic_trim)) $s=Gettrimvalue($pic_trim,$s);
					$links[$key][pic] = FillUrl($listurl,$s);
					
				}
			}
			$per_count = !$per_count?count($links):$per_count;
			
			if (!empty($links))
			{
				for ($j=0;$j<count($links);$j++)
				{
					$url=$links[$j][url];
					$pic=$links[$j][pic];
					$rowt=$dsql->GetOne("Select uid from `sea_co_url` where tid='$id' and url='$url'");
					if(is_array($rowt)){
						$dsql->ExecuteNoneQuery("update `sea_co_url` set succ='0',err='0' where uid=".$rowt['uid']);
					}else{
						$sql="insert into `sea_co_url`(cid,tid,url,pic,cotype) values ('$cid','$id','$url','$pic','1')";
						$dsql->ExecuteNoneQuery($sql);
					}
				}//for
			}//if
			unset($links);
		}
	}
}


function autogetconbyid($id)
{
	@session_write_close();
	if($id==0) return false;
	global $dsql,$col,$getconnum,$cfg_gatherset;
	$row = $dsql->GetOne("Select t.coding,t.sock,t.playfrom,t.autocls,t.classid,t.getherday,t.listconfig,t.itemconfig,c.cid,c.getconnum from `sea_co_type` t left join `sea_co_config` c on c.cid=t.cid where t.tid='$id'");
	
	//print_r($row);die;
	
	$listconfig=$row['listconfig'];
	$itemconfig=$row['itemconfig'];
	$coding=$row['coding'];
	$sock=$row['sock'];
	$playfrom=$row['playfrom'];
	$classid=$row['classid'];
	$autocls=$row['autocls'];
	$getherday=$row['getherday'];
	$cid=$row['cid'];
	//列表规则
	$listlabelRule = buildregx("{seacms:listrule(.*?)}(.*?){/seacms:listrule}","is");
	preg_match_all($listlabelRule,$listconfig,$listar);
	
	preg_match_all($listlabelRule,$listconfig,$listar);
	$listattrStr = trim(preg_replace("/[ \r\n\t\f]{1,}/"," ",$listar[1][0]));
	$listloopstr=$listar[2][0];
	$listattrDictionary=parseAttr($listattrStr);
	$inithit=$listattrDictionary["inithit"];
	$reverse=$listattrDictionary["reverse"];
	$intodatabase=$listattrDictionary["intodatabase"];
	$removecode=$listattrDictionary["removecode"];
	//页面规则
	$labelRule = buildregx("{seacms:itemconfig(.*?)}(.*?){/seacms:itemconfig}","is");
	preg_match_all($labelRule,$itemconfig,$ar);
	$attrStr = trim(preg_replace("/[ \r\n\t\f]{1,}/"," ",$ar[1][0]));
	$loopstr=$ar[2][0];
	$attrDictionary=parseAttr($attrStr);
	//读出列表url
	$wheresql=" where err<3 and succ='0' and tid='$id'";
	$csql="select count(*) as dd from `sea_co_url` $wheresql";
	$rowd = $dsql->GetOne($csql);
	if(is_array($rowd)){
	$TotalResult = $rowd['dd'];
	}else{
	$TotalResult = 0;
	}
	
	if (is_numeric($getconnum)&&$getconnum>$TotalResult) $getconnum=$TotalResult;
	if (is_numeric($getconnum)&&$getconnum<=0) $getconnum=$TotalResult;
	$sqlStr="select * from `sea_co_url` $wheresql order by uid asc limit 0,$getconnum ";
      
	if($TotalResult!=0){
		$dsql->SetQuery($sqlStr);
		$dsql->Execute('url_list');
		$rowt=$dsql->GetAssoc('url_list');
		//print_r($rowt);die;
		while($rowt=$dsql->GetAssoc('url_list'))
		{
			$url=$rowt['url'];
			$pic=$rowt['pic'];			
			$html = cget($url,$sock);
			if($html){
				$html = ChangeCode($html,$coding);
				//判断时间处理
				 if(trim($pic)!=''){
					 $n_pic=$pic;
				 }else{
					 $n_pic=FillUrl($url,getAreaValue($loopstr,"pic",$html));
				 }
				 if($autocls){
					$tname=getAreaValue($loopstr,"cls",$html);
				 	$tid=getTidFromCls($tname);	
				 }else{
					$tid=$classid;
				 }
				
				 $n_title=getAreaValue($loopstr,"name",$html);
				 $n_title=filterWord($n_title,0);
				 $n_entitle=Pinyin($n_title);
				 $n_letter=strtoupper(substr($n_entitle,0,1));
				 $n_keywords=getAreaValue($loopstr,"state",$html);
				 $n_author=getAreaValue($loopstr,"author",$html);
				 $n_outline=getAreaValue($loopstr,"note",$html);
				 $n_content=getAreaValue($loopstr,"des",$html);				 
				 $n_from=getAreaValue($loopstr,"parea",$html);
				 $n_addtime=time();
				 //echo $n_title; echo "<br>";
				 if(!empty($n_title))
					{
						
						$rs=$dsql->GetOne("Select n_id from `sea_co_news` where n_title like '%".$n_title."%'");
						$ndata = array('tid'=>$tid,'n_title'=>$n_title,'n_keyword'=>$n_keywords,'n_pic'=>$n_pic,'n_hit'=>$n_hit,'n_author'=>$n_author,'n_addtime'=>$n_addtime,'n_letter'=>$n_letter,'n_content'=>$n_content,'n_outline'=>$n_outline,'tname'=>$tname,'n_from'=>$n_from,'n_inbase'=>0,'n_entitle'=>$n_entitle);
						if(is_array($rs)){
							$ret=true;
							//$ret = update_record('sea_co_news',"where n_id=".$rs['n_id'],$ndata);
							//if($intodatabase==1){$idsArray=array($rs['n_id']);import2Base($idsArray,$vtype);}
						}
						else{
						$ret = insert_record('sea_co_news',$ndata);
						$rsid=$dsql->GetOne("SELECT LAST_INSERT_ID();");//print_r($rsid[0]);
							if($intodatabase==1){$idsArray=array($rsid[0]);import2Base($idsArray,$vtype);}
						}
						
						if($ret){
							$sql = "update `sea_co_url` set succ='1' where uid=".$rowt['uid'];
							
						}else{
							$sql = "update `sea_co_url` set err=err+1 where uid=".$rowt['uid'];
							
						}
						
						$dsql->ExecuteNoneQuery($sql);
					}
					else
					{
						
						$sql = "update `sea_co_url` set err=err+1 where uid=".$rowt['uid'];
						$dsql->ExecuteNoneQuery($sql);
					}
				}
			else
			{
				$sql = "update `sea_co_url` set err=err+1 where uid=".$rowt['uid'];
				$dsql->ExecuteNoneQuery($sql);
				echo "{$echo_id}. {$url}\t<font color=red>远程读取失败</font>.<br>";
			}				

		}
		//$dsql->ExecuteNoneQuery("delete from sea_co_url where tid='$id'");
	}
	unset($attrDictionary);
	unset($listattrDictionary);
	$dsql->ExecuteNoneQuery("update sea_co_type set cjtime='".time()."' where tid='$id'");
}

function filterWord($string,$rCol)
{
	global $dsql;
	if($string=='')
	return $string;
	$sql = "SELECT rColumn,uesMode,sFind,sReplace,sStart,sEnd FROM sea_co_filters WHERE Flag=1 and cotype=1";
	$dsql->SetQuery($sql);
	$dsql->Execute('filterWord');
	while ($row =$dsql->GetArray('filterWord'))
	{
		if($row['rColumn']==$rCol)
		{
			if($row['uesMode']==1)
			$string=preg_replace("/".addslashes($row['sStart'])."([\s\S]+?)".addslashes($row['sEnd'])."/ig", $row['sReplace'], $string);
			else
			$string=str_replace($row['sFind'], $row['sReplace'], $string);
		}
	}
	return $string;
}

function getTidFromCls($name)
{
	global $dsql;
	$trow = $dsql->GetOne("select sysclsid from sea_co_cls where clsname='$name'");
	if(is_array($trow)) return $trow['sysclsid'];
	else return 0;
}

function import2Base($idsArray,$vtype)
{
	global $dsql,$cfg_gatherset;
	if(count($idsArray)>0)
	{
		$ids = implode(',',$idsArray);
		$sql="SELECT * FROM sea_co_news WHERE n_id IN (".$ids.")";
		$dsql->SetQuery($sql);
		$dsql->Execute('import_list');
		while($row=$dsql->GetObject('import_list'))
		{
			$v_where="";$sql="";$title=$row->n_title;$titleArray=explode("/",$title);
			$tid=($row->tid>0) ? $row->tid : $vtype;
			if($tid!=''){
				foreach($titleArray as $v_title){
					if(!empty($v_title)) $v_where.=" or concat('/',n_title,'/') like '%/".$v_title."/%' ";
				}
				$v_where=ltrim($v_where," or");
				if($v_where<>''){
				$v_where = " and ".$v_where;
				}
				$v_sql="select n_id from sea_news where 1=1 ".$v_where." order by n_id desc";
				$rs = $dsql->GetOne($v_sql);
				if(!is_array($rs)){
				$sql="INSERT INTO `sea_news` (`n_id`, `tid`, `n_title`, `n_pic`, `n_hit`, `n_money`, `n_rank`, `n_digg`, `n_tread`, `n_commend`, `n_author`, `n_color`, `n_addtime`, `n_note`, `n_letter`, `n_isunion`, `n_recycled`, `n_entitle`, `n_outline`, `n_keyword`, `n_from`, `n_score`, `n_content`) VALUES (NULL, '".$tid."', '".addslashes($row->n_title)."', '".addslashes($row->n_pic)."', '".addslashes($row->n_hit)."', '0', '0', '0', '0', '0', '".addslashes($row->n_author)."', '', '".addslashes($row->n_addtime)."', '0', '".addslashes($row->n_letter)."', '0', '0',  '".addslashes($row->n_entitle)."', '".addslashes($row->n_outline)."', '".addslashes($row->n_keyword)."', '".addslashes($row->n_from)."', '0', '".addslashes($row->n_content)."')";
				$dsql->ExecuteNoneQuery($sql);
				}
				else
				{
				$sql="update `sea_news`  set  n_pic='".addslashes($row->n_pic)."', n_hit='".addslashes($row->n_hit)."',n_author= '".addslashes($row->n_author)."', n_addtime='".addslashes($row->n_addtime)."', n_outline='".addslashes($row->n_outline)."', n_keyword='".addslashes($row->n_keyword)."', n_from='".addslashes($row->n_from)."', n_content='".addslashes($row->n_content)."' where n_id=".$rs['n_id'];
				$dsql->ExecuteNoneQuery($sql);
				}
				$dsql->ExecuteNoneQuery("update `sea_co_news` set n_inbase='1',tid='$tid' where n_id=".$row->n_id);
				
			}else{
				
			}//if $tid
		}//while
	}//if count
}

function autocache_clear($dir) {
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
?>
</body>
</html>