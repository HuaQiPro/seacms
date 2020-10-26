<?php 

require_once(dirname(__FILE__)."/config.php");

require_once(dirname(__FILE__)."/../data/config.plus.inc.php"); 


CheckPurview();


if(empty($action)){
	 
	   $action='';

  }else{
	
	//设置提交间隔,防止重复提交造成数据丢失,单位：秒
	
    $post_timeout=5; 

	session_start();
	
	if(isset($_SESSION['lock_time'])){$time=(int)$_SESSION['lock_time']-(int)time(); if($time>0){ ShowMsg("请勿频繁提交，请".$time."秒后再试！","admin_plus.php?active=".$action);exit;}}
	
    $_SESSION['lock_time']= time() + $post_timeout;

}




if($action==="HideVideo"){
	
	$filters = array(
           "hideoff_url" => FILTER_SANITIZE_STRING,	
           "hidebody_url" => FILTER_SANITIZE_STRING,
           "hideinfo_url"=>FILTER_UNSAFE_RAW ,
           
            "hideoff_name" => FILTER_SANITIZE_STRING,	
           "hidebody_name" => FILTER_SANITIZE_STRING,
           "hideinfo_name"=>FILTER_UNSAFE_RAW ,
           
            "hideoff_type" => FILTER_SANITIZE_STRING,	
           "hidebody_type" => FILTER_SANITIZE_STRING,
           "hideinfo_type"=>FILTER_UNSAFE_RAW ,

   );
   
	$POST=filter_input_array(INPUT_POST, $filters);
	
	$PLUS["HideVideo"]=array(off=>trim($POST["hideoff_url"]),data=>preg_split('/[\r\n]+/s',trim($POST["hidebody_url"])),info=> trim($POST["hideinfo_url"]));
	
	$PLUS["HideName"]=array(off=>trim($POST["hideoff_name"]),data=>preg_split('/[\r\n]+/s',trim($POST["hidebody_name"])),info=> trim($POST["hideinfo_name"]));
		
	$PLUS["HideType"]=array(off=>trim($POST["hideoff_type"]),data=>preg_split('/[\r\n]+/s',trim($POST["hidebody_type"])),info=> trim($POST["hideinfo_type"]));
		

     if(Main_db::save()){
		if(function_exists("opcache_reset")){opcache_reset();} 
        ShowMsg("保存成功！","admin_plus.php?active=HideVideo");
     }else{
        ShowMsg("保存失败！请检查文件权限","admin_plus.php?active=HideVideo");
     }
  

}elseif($action==="JmpVideo"){ 
	
   $off=trim(filter_input(INPUT_POST, "jmpoff",FILTER_SANITIZE_STRING));
   $jmpbody=trim(filter_input(INPUT_POST, "jmpbody",FILTER_SANITIZE_STRING)); $body=preg_split('/[\r\n]+/s',$jmpbody);
   $array=array(); foreach($body as $key){$val=explode("=>",$key); if($val[0]!=""&&$val[1]!=""){$array[trim($val[0])]=trim($val[1]);}}
   $PLUS["JmpVideo"]=array(off=>$off,data=>$array);

    if(Main_db::save()){
		if(function_exists("opcache_reset")){opcache_reset();} 
        ShowMsg("保存成功！","admin_plus.php?active=JmpVideo");
     }else{
        ShowMsg("<font style='color:red'>保存失败,请检查文件权限！</font>","admin_plus.php?active=JmpVideo");
     }

}elseif($action==="Other"){ 
	

	
		if(!filter_has_var(INPUT_POST, 'numPerPage')){ ShowMsg("保存失败,参数异常！","admin_plus.php?active=Other");exit;}
 
	$PLUS["Other"]['numPerPage']=filter_input(INPUT_POST, "numPerPage",FILTER_SANITIZE_NUMBER_INT);
     if(Main_db::save()){
		if(function_exists("opcache_reset")){opcache_reset();} 
        ShowMsg("保存成功！","admin_plus.php?active=Other");
     }else{
        ShowMsg("<font style='color:red'>保存失败！请检查文件权限</font>","admin_plus.php?active=Other");
     }
  
  
  
}else{
	$php_self=filter_input(INPUT_SERVER, 'QUERY_STRING',FILTER_SANITIZE_STRING);
	
	 include(sea_ADMIN.'/templets/admin_plus.htm'); 
}

class Main_db
{
     //变量转文本
     public static function word($name) {global $$name; $key=var_export($$name,true);return "$$name=$key;\r\n";}
     //保存配置
     public static function save($file="../data/config.plus.inc.php")
     {     
        //排除注释和php标记
        $data = preg_replace('!\/\/.*?[\r\n]|\/\*[\S\s]*?\*\/!', '', preg_replace('/(?:\<\?php|\?\>)/', '', file_get_contents($file)));       
        //按php语句分组
        $lines = preg_split('/[;]+/s', $data,-1,PREG_SPLIT_NO_EMPTY);	 
        $word ="<?php \r\n";
       //更新变量
        foreach ($lines as $value){
          $value= trim($value);
          //检测是否PHP变量声明
          if($value!==''&&substr($value,0,1)==='$'){
              //分离 变量名和值
              $line=explode('=',$value,2);
              //取变量名
              $name = str_replace('$', '', trim($line[0]));
              //变量转文本 重新赋值
              $word.=self::word($name); }       
          }   
        return file_put_contents($file,$word);  	  
     }
}

?>