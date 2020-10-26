<?php 
if(!defined('sea_INC'))
{
    exit("Request Error!");
}
if(isset($_FILES['GLOBALS']))
{
    exit('Request not allow!');
}
//为了防止用户通过注入的可能性改动了数据库 
//这里强制限定的某些文件类型禁止上传
$cfg_not_allowall = "php|pl|cgi|asp|asa|cer|aspx|jsp|php3|shtm|shtml|htm|html";
$imtypes = array("image/pjpeg", "image/jpeg", "image/gif", "image/png", "image/xpng", "image/wbmp", "image/bmp");
$keyarr = array('name','type','tmp_name','size');
$fskey=array('(',')','<','>','%','0x','|',';','{','}','$','&','*','#','@','[',']');
foreach($_FILES as $_key=>$_value)
{
    foreach($keyarr as $k)
    {
        if(!isset($_FILES[$_key][$k]))
        {
            exit('Request Error!');
        }
    }
if(!filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)){$_SERVER['HTTP_CLIENT_IP']='0.0.0.0';}
if(!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){$_SERVER['REMOTE_ADDR']='0.0.0.0';}  
if(!filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){$_SERVER['HTTP_X_FORWARDED_FOR']='0.0.0.0';}    
//安全过滤  
$_FILES[$_key]['name']=str_ireplace($fskey,'',$_FILES[$_key]['name']);
$_FILES[$_key]['tmp_name']=str_ireplace($fskey,'',$_FILES[$_key]['tmp_name']);
$_SERVER['HTTP_COOKIE']=str_ireplace($fskey,'',$_SERVER['HTTP_COOKIE']);   
    $$_key = $_FILES[$_key]['tmp_name'] = str_replace("\\\\","\\",$_FILES[$_key]['tmp_name']);
    ${$_key.'_name'} = $_FILES[$_key]['name'];
    ${$_key.'_type'} = $_FILES[$_key]['type'] = m_eregi_replace('[^0-9a-z\./]','',$_FILES[$_key]['type']);
    ${$_key.'_size'} = $_FILES[$_key]['size'] = m_ereg_replace('[^0-9]','',$_FILES[$_key]['size']);
    //过滤类型
    if(!empty(${$_key.'_name'}) && (m_eregi("\.(".$cfg_not_allowall.")$",${$_key.'_name'}) || !m_ereg("\.",${$_key.'_name'})) )
    {
            exit;
    }
    //检测图片
     if(in_array(strtolower(trim(${$_key.'_type'})), $imtypes))
    {
     $image_dd = @getimagesize($$_key); if($image_dd == false){continue;}
     if (!is_array($image_dd)) {exit;}
    }
    if(empty(${$_key.'_size'})){${$_key.'_size'} = @filesize($$_key);}
}
?>