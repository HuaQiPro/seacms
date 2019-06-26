<?php

require_once(dirname(__FILE__)."/config.php");

require_once(dirname(__FILE__)."/../data/config.plus.inc.php"); 

CheckPurview();
if(empty($action)){$action='';}
if($action==="HideVideo"){
     $hidebody=trim(filter_input(INPUT_POST, "hidebody"));  $body=preg_split('/[\r\n]+/s',$hidebody);
     $hideinfo=trim(filter_input(INPUT_POST, "hideinfo")); 
	 $off=trim(filter_input(INPUT_POST, "hideoff"));
  
	 $PLUS["HideVideo"]=array(off=>$off,data=>$body,info=> $hideinfo,);
	
     if(Main_db::save()){
		if(function_exists("opcache_reset")){opcache_reset();} 
        ShowMsg("保存成功！","admin_plus.php");
     }else{
        ShowMsg("保存失败！请检查文件权限","admin_plus.php");
     }
  
}elseif($action==="JmpVideo"){ 
   $off=trim(filter_input(INPUT_POST, "jmpoff"));
   $jmpbody=trim(filter_input(INPUT_POST, "jmpbody"));  $body=preg_split('/[\r\n]+/s',$jmpbody);
   $array=array(); foreach($body as $key){$val=explode("=>",$key); if($val[0]!=""&&$val[1]!=""){$array[trim($val[0])]=trim($val[1]);}}
   $PLUS["JmpVideo"]=array(off=>$off,data=>$array);

    if(Main_db::save()){
		if(function_exists("opcache_reset")){opcache_reset();} 
        ShowMsg("保存成功！","admin_plus.php");
     }else{
        ShowMsg("保存失败！请检查文件权限","admin_plus.php");
     }

}elseif($action==="Other"){ 
  
     $numPerPage=trim(filter_input(INPUT_POST, "numPerPage")); 
     $PLUS["Other"]['numPerPage']=$numPerPage;
  
     if(Main_db::save()){
		if(function_exists("opcache_reset")){opcache_reset();} 
        ShowMsg("保存成功！","admin_plus.php");
     }else{
        ShowMsg("保存失败！请检查文件权限","admin_plus.php");
     }
  
  
  
}else{
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
        $word ="<?php\r\n";
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