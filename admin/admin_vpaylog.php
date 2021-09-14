<?php 
require_once(dirname(__FILE__)."/config.php");
require_once(sea_DATA."/config.user.inc.php");
CheckPurview();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta name="robots" content="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<title>消费记录</title>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
<style type="text/css">
select {
	font-size:12px;
}
form{float:left;}
</style>
</head>
<body>
<!--当前导航-->
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;用户&nbsp;&raquo;&nbsp;消费记录';</script>
<?php

function get_uname($uid){
	global $dsql;
	$uname= $dsql->GetOne("SELECT username FROM sea_member where id=".$uid);
	return $uname['username'];
}

function get_vname($vid){
	global $dsql;
	$vname= $dsql->GetOne("SELECT v_name FROM sea_data where v_id=".$vid);
	return $vname['v_name'];
}

function get_uid($uname){
	global $dsql;
	$uid= $dsql->GetOne("SELECT id FROM sea_member where username='$uname'");
	return $uid['id'];
}

function get_vid($vname){
	global $dsql;
	$vid= $dsql->GetOne("SELECT v_id FROM sea_data where v_name='$vname'");
	return $vid['v_id'];
}
//$uid='2'; $vid='100'; echo get_uname($uid); echo get_vname($vid);

	$w="";
    $numPerPage=20;
    $page = isset($page) ? intval($page) : 1;
    if($page==0) $page=1;
    
	
	if ($uname !="") {$uid=get_uid($uname);$w.=" and uid='$uid'";}
	if ($vname !="") {$vid=get_vid($vname);$w.=" and vid='$vid'";}

	$sqlyesu="SELECT SUM(vpaypoints) AS vpayallu FROM sea_buy where 1=1" .$w;
	$rowyesu = $dsql->GetOne($sqlyesu);
    if($rowyesu['vpayallu']>0){
        $vpayallu = $rowyesu['vpayallu'];
    }else{
        $vpayallu = 0;
    }
	
	$sqlyes="SELECT SUM(vpaypoints) AS vpayall FROM sea_buy";
	$rowyes = $dsql->GetOne($sqlyes);
    if($rowyes['vpayall']>0){
        $vpayall = $rowyes['vpayall'];
    }else{
        $vpayall = 0;
    }
	
	
	//计算有多少条数据
    $csqlStr="select count(*) as dd from sea_buy where 1=1 ".$w;
	$rowTotal = $dsql->GetOne($csqlStr);
    if(is_array($rowTotal)){
        $TotalResult = $rowTotal['dd'];
    }else{
        $TotalResult = 0;
    }
    $TotalPage = ceil($TotalResult/$numPerPage);
    if ($page>$TotalPage) $page=$TotalPage;
    $limitstart = ($page-1) * $numPerPage;
    if($limitstart<0) $limitstart=0;
    
   $sqlStr="select * from sea_buy where 1=1 ".$w." ORDER BY id DESC limit $limitstart,$numPerPage";

?>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
      <table class="tb_style" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="15" class="td_title">&nbsp;视频消费记录&nbsp;&nbsp;【消费总额<font color=red><?php echo $vpayall ?></font>】
		  <?php
		  if($uname !="" AND $vname !=""){$uname =$uname.'+';}
		  if($uname !="" OR $vname !=""){echo '【<font color=red>'.$uname.$vname.'</font>消费<font color=red>'.$vpayallu.'</font>】';} ?>
            </td>
        </tr>
        <tr>
          
            <td height="40" align="left" colspan="10">
		   <form action="?action=search" method="post" >
          用户：<input  name="uname" type="text" id="uname" size="12">
			  &nbsp;&nbsp;&nbsp;视频：<input  name="vname" type="text" id="vname" size="12">
			  <input type="submit" name="selectBtn" value="搜 索" class="btn"  />&nbsp;&nbsp;&nbsp;用户和视频可以单独，也可以组合搜索
		   </form>
         </td>
		</tr>
        <?php
if($TotalResult==0){
    echo "<tr><td colspan='10'><br>&nbsp;<font color=red>无消费信息</font><br><br></td></tr>";}
?>
        <tr bgcolor="#f5fafe" >
          <td width="10" height="30" bgcolor="#FFFFFF" class="td_btop3">用户</td>
          <td width="20" bgcolor="#FFFFFF" class="td_btop3">视频</td>
		  <td width="20" bgcolor="#FFFFFF" class="td_btop3">分集</td>
		  <td width="20" bgcolor="#FFFFFF" class="td_btop3">积分</td>
          <td width="80" bgcolor="#FFFFFF" class="td_btop3">消费时间</td>
        </tr>
        <form method="post" name="videolistform">
          <?php

$dsql->SetQuery($sqlStr);
$dsql->Execute('key_list');
while($row=$dsql->GetObject('key_list'))
{

?>


          <tr bgcolor="#FFF" style="background-color:#FFF" onmouseover="style.backgroundColor='#E6F2FB'" onmouseout="style.backgroundColor='#FFF'">
           
            <td height="30" class="td_border"><?php echo get_uname($row->uid);?></td>
			<td class="td_border"><?php echo get_vname($row->vid);?></td>
			<td class="td_border"><?php echo $row->vfrom+1;?></td>			
            <td class="td_border"><?php echo $row->vpaypoints;?></td>
			<td class="td_border"><?php echo date('Y-m-d h:m:s',$row->kptime);?></td>
          </tr>
          <?php }?>
  
        </form>
		
        <tr>
          <td height="30" colspan="11" class="td_border">
            <div class="cuspages">
              <div class="pages"> &nbsp;页次：<?php echo $page;?>/<?php echo $TotalPage;?> 每页<?php echo $numPerPage;?> 总收录数据<?php echo $TotalResult;?>条 <a href="?page=1&vname=<?php echo $vname;?>&uname=<?php echo $uname;?>">首页</a> <a href="?page=<?php echo ($page-1);?>&vname=<?php echo $vname;?>&uname=<?php echo $uname;?>">上一页</a> <a href="?page=<?php echo ($page+1);?>&vname=<?php echo $vname;?>&uname=<?php echo $uname;?>">下一页</a> <a href="?page=<?php echo $TotalPage;?>&vname=<?php echo $vname;?>&uname=<?php echo $uname;?>">尾页</a>&nbsp;&nbsp;跳转
                <input type="text" id="skip" value="" onkeyup="this.value=this.value.replace(/[^\d]+/,'')" style="width:40px"/>
                &nbsp;&nbsp;
                <input type="button" value="确 定" class="btn" onclick="location.href='?page='+ document.getElementById('skip').value +'&vname=<?php echo $vname;?>&uname=<?php echo $uname;?>';"/>
              </div>
            </div>
 </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div id="copy" name="copy" style="display:none;"><textarea style="width:400px;height:500px;"><?php echo $copy; ?></textarea></div>
<?php
viewFoot();
?>
</body>
</html>
