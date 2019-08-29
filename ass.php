<?php
// search jsonp server v1.2  form seacms by  nohacks.cn

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
  
  
   public function Queryurl($vid,$from,$part)
  
   {
   	
   	global $dsql;


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

      return  array('num'=>sizeof($urlArray),'part'=>$part,'url'=>$urlArray[$prat],'video'=>$urlArray);


     return '';

   }
  

     public function out_json($Callback,$data)
  
   {
	   $str=$Callback."(".json_encode($data).");" ;
	    
	   return  $str;  
	   	    
	   
   }   
	   	   
  
}
				
?> 



