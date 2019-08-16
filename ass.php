<?php
// search jsonp server v1.2  form seacms by  nohacks.cn

require_once("include/common.php");
require_once(sea_INC."/main.class.php");

//循环取参数，参数名同时赋值变量名。
$schwhere = '';
foreach($_GET as $k=>$v)
{
	$$k=_RunMagicQuotes(gbutf8(RemoveXSS($v)));
	$schwhere.= "&$k=".urlencode($$k);
}
$schwhere = ltrim($schwhere,'&');

//参数过滤
$wd = RemoveXSS(stripslashes($wd));
$wd = addslashes(cn_substr($wd,20));
$wd = trim($wd);

$cb = isset($cb) && $cb ? $cb:'seacms:search';
$cb = FilterSearch(stripslashes($cb));
$cb = RemoveXSS(stripslashes($cb));
$cb = trim($cb);

$sug=array('q'=>"",'p'=>false,s=>array(''));

if($cfg_notallowstr !='' && m_eregi($cfg_notallowstr,$wd))
{  
	$sug[p]=true;
	$sug[q]="非法字符！";
	echo $cb."(".json_encode($sug).");" ;  
	exit();
}
if($wd=='')
{ 
   $sug[p]=true;
   $sug[q]="关键字不能为空！";
   echo $cb."(".json_encode($sug).");" ;  
   exit();	
}

$myObj = new SetQuerybyseacms();
$sug[q]=$wd;
$sug[s]=$myObj->Querykey($wd,"v_name",10);

echo $myObj->out_json($cb,$sug);


//seacms 查询类 by nohacks.cn
class SetQuerybyseacms
{	

  public function Querykey($keyword,$key,$num)
  
   {

	global $dsql,$cfg_iscache;
	$rows=array();
	
	$this->dsql = $dsql;
	
    $sql = "SELECT * FROM `sea_data` WHERE `v_name` like '%".$keyword."%'";

    if (!is_null($num)) { $sql=$sql."LIMIT 0,".$num."";}
				
	  //执行查询
		 $this->dsql->SetQuery($sql);
         $this->dsql->Execute('zz');								
		 $aa=$this->dsql->GetTotalRow('zz');
                               
			if($aa>0){																    
									
				  while($row = $this->dsql->GetAssoc('zz'))
					    {
																	
						 $rows[]=$row[$key];
									
					    }	
								
						 unset($rowr);			
                     }
         		 
		   
	  return $rows;		 		 		 
  }
  
     public function out_json($Callback,$data)
  
   {
	   $str=$Callback."(".json_encode($data).");" ;
	    
	   return  $str;  
	   	    
	   
   }   
	   	   
  
}
				
?> 



