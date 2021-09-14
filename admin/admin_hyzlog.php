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
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;用户&nbsp;&raquo;&nbsp;会员记录';</script>
<?php

function get_gname($gid){
	global $dsql;
	$gname= $dsql->GetOne("SELECT gname FROM sea_member_group where gid=".$gid);
	return $gname['gname'];
}



	$w="";
    $numPerPage=20;
    $page = isset($page) ? intval($page) : 1;
    if($page==0) $page=1;
    
	
	if ($uname !="") {$w.=" and uname='$uname'";}

	$sqlyesu="SELECT SUM(paypoints) AS hyzpayallu FROM sea_hyzbuy where 1=1" .$w;
	$rowyesu = $dsql->GetOne($sqlyesu);
    if($rowyesu['hyzpayallu']>0){
        $vpayallu = $rowyesu['hyzpayallu'];
    }else{
        $vpayallu = 0;
    }
	
	$sqlyes="SELECT SUM(paypoints) AS hyzpayall FROM sea_hyzbuy";
	$rowyes = $dsql->GetOne($sqlyes);
    if($rowyes['hyzpayall']>0){
        $vpayall = $rowyes['hyzpayall'];
    }else{
        $vpayall = 0;
    }
	
	
	//计算有多少条数据
    $csqlStr="select count(*) as dd from sea_hyzbuy where 1=1 ".$w;
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
    
   $sqlStr="select * from sea_hyzbuy where 1=1 ".$w." ORDER BY id DESC limit $limitstart,$numPerPage";

?>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
      <table class="tb_style" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="15" class="td_title">&nbsp;会员组购买记录&nbsp;&nbsp;【购买总额<font color=red><?php echo $vpayall ?></font>】
		  <?php
		  if($uname !=""){echo '【<font color=red>'.$uname.'</font>花费<font color=red>'.$vpayallu.'</font>】';} ?>
            </td>
        </tr>
        <tr>
          
            <td height="40" align="left" colspan="10">
		   <form action="?action=search" method="post" >
          用户：<input  name="uname" type="text" id="uname" size="12">
			  <input type="submit" name="selectBtn" value="搜 索" class="btn"  />
		   </form>
         </td>
		</tr>
        <?php
if($TotalResult==0){
    echo "<tr><td colspan='10'><br>&nbsp;<font color=red>无购买信息</font><br><br></td></tr>";}
?>
        <tr bgcolor="#f5fafe" >
          <td width="10" height="30" bgcolor="#FFFFFF" class="td_btop3">用户</td>
          <td width="20" bgcolor="#FFFFFF" class="td_btop3">时长</td>
		  <td width="20" bgcolor="#FFFFFF" class="td_btop3">积分</td>
		  <td width="20" bgcolor="#FFFFFF" class="td_btop3">会员组</td>
          <td width="80" bgcolor="#FFFFFF" class="td_btop3">购买时间</td>
        </tr>
        <form method="post" name="videolistform">
          <?php

$dsql->SetQuery($sqlStr);
$dsql->Execute('key_list');
while($row=$dsql->GetObject('key_list'))
{

?>


          <tr bgcolor="#FFF" style="background-color:#FFF" onmouseover="style.backgroundColor='#E6F2FB'" onmouseout="style.backgroundColor='#FFF'">
           
            <td height="30" class="td_border"><?php echo $row->uname;?></td>
			<td class="td_border"><?php echo $row->mon;?>个月</td>
			<td class="td_border"><?php echo $row->paypoints;?></td>			
            <td class="td_border"><?php echo get_gname($row->gid);?></td>
			<td class="td_border"><?php echo date('Y-m-d h:m:s',$row->paytime);?></td>
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
