<?php 
session_start();

require_once("include/common.php");
require_once(sea_INC."/main.class.php");

//参数过滤
$cb=filter_input(INPUT_GET, 'cb',FILTER_SANITIZE_STRING);
$wd =filter_input(INPUT_GET, 'wd',FILTER_SANITIZE_STRING);
//$url=filter_input(INPUT_GET, 'url',FILTER_SANITIZE_URL);
$url=dp;
$vid=intval($_GET['vid']);
$vfrom=$_GET['vfrom'];
$vpart=$_GET['vpart']+1;

$sug=array('q'=>"",'p'=>false,s=>array(''));

if($wd=='' && $url=='')
{ 
   $sug[p]=true;
   $sug[q]="input error！";
   echo $cb."(".json_encode($sug).");" ;  
   exit();	
}


$myObj = new SetQuerybyseacms();


if($wd!=''){
	
	$sug[q]=$wd;
	$sug[s]=$myObj->Querykey($wd,"v_name",10);	
	
	
}elseif($url!=''){
	$sug[q]=$url;
	$sug[s]=$myObj->Queryurl($vid,$vfrom,$vpart);	
	
}

echo $myObj->out_json($cb,$sug);


//seacms 查询类 by nohacks.cn
class SetQuerybyseacms
{	

  public function Querykey($keyword,$key,$num)
  
   {

	global $dsql,$cfg_iscache,$cfg_search_type;
	
	if($cfg_search_type =='0'){return '搜索系统已关闭！';	}

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
  
  
   public function Queryurl($vid,$from,$part)
  
   {
   	
   	global $dsql,$cfg_pointsname;


		//echo $url.$vid.$vfrom,$vpart;
   		$row=$dsql->GetOne("SELECT * FROM `sea_playdata` WHERE `v_id` ='$vid'");
		
       //来源分组
        $fromArray1=explode("$$$",$row['body']); $fromArray2=explode("$$",$fromArray1[$from]);
       
       //剧集分组
        $pratArray=explode("#",$fromArray2[1]);
        
       //取所有地址
       foreach ($pratArray as $key=>$val){
      	
      	   $Array=explode("$",$val);
      	 
      	   $urlArray[$key]=$Array[1];
      	 
        }
	  $houz=getfileSuffix();
	  
	$row2=$dsql->GetOne("SELECT tid,v_vip,v_try,v_money FROM sea_data where v_id=".$vid);
	$vip=$row2['v_vip'];
	$try=$row2['v_try'];
	$vType=$row2['tid'];
	$jifen=$row2['v_money'];
	if($jifen=="" OR empty($jifen)){$jifen=0;}
	if($starget!=""){
		$target=" target=\"".$starget."\"";
	}else{
		$target=" target=\"_blank\"";
	}
	$urlArray2=$pratArray;
	$urlCount=count($urlArray2);
	
	//检测授权
	if(getUserAuth($vType, "play")){$isauth='y';}else{$isauth='n';}
	
	if(strpos($vip,'s')!==false)
	{
		$vips=str_ireplace('s', "", $vip);
		$viparr=array_flip(array_slice($urlArray2,0,$vips,true));
	}
	elseif(strpos($vip,'e')!==false)
	{
		$vipe=str_ireplace('e', "", $vip);
		$vipes=$urlCount - $vipe;
		$viparr=array_flip(array_slice($urlArray2,$vipes,$vipe,true));		
	}
	elseif(strpos($vip,'a')!==false)
	{
			$viparr=array_flip(array_slice($urlArray2,0,$urlCount,true));		
	}
	elseif(strpos($vip,'f')!==false)
	{
		$vips=str_ireplace('f', "", $vip);
		$viparr=array_flip(array_slice($urlArray2,$vips,NULL,true));
	}
	else
	{
		$viparr2=explode(',',$vip);
		foreach ($viparr2 as $value) 
		{
		  $viparr[]=$value-1;
		}
	}
	
$uid=0;
$uid=$_SESSION['sea_user_id'];
$uid = intval($uid);

	$dsql->SetQuery("SELECT vfrom FROM sea_buy where vid='$vid' and uid='$uid'");
	$dsql->Execute('vipdel');
	while($rowvipdel=$dsql->GetObject('vipdel'))
            {
                   $vipdelarr[] = $rowvipdel->vfrom;
            }
	  
	  if(empty($viparr)){$viparr=array(-1,-2);}
	  if(empty($vipdelarr)){$vipdelarr=array(-1);}
	  $viparr2=array_diff($viparr,$vipdelarr);
      return  array('num'=>sizeof($urlArray),'part'=>$part,'url'=>$urlArray[$prat],'video'=>$urlArray,'houz'=>$houz,'vipp'=>$viparr2,'try'=>$try,'jifen'=>$jifen,'jifenname'=>$cfg_pointsname,'isauth'=>$isauth);


     return '';

   }
  

     public function out_json($Callback,$data)
  
   {
	   $str=$Callback."(".json_encode($data).");" ;
	    
	   return  $str;  
	   	    
	   
   }   
	   	   
  
}
				
?> 



