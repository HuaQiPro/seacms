<?php 
header('Content-Type:text/html;charset=utf-8');
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
?>
<script src="js/jquery.js" type="text/javascript"></script>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
    var b=false;
    var iv_cj,iv_makeinfo,iv_makeindex,iv_maketype;
    var urln=0,typeids='';
    var cjUrl = "";

    $(function(){
        $("#btnGO").click(function(){
            var time = new Array();
            time['cj'] = Number($("#ds_cj").val());
            time['makeinfo'] = Number($("#ds_makeinfo").val());
            time['makeindex'] = Number($("#ds_makeindex").val());
            time['maketype'] = Number($("#ds_maketype").val());
            if(time['cj'] > 0){
                iv_cj = window.setInterval('cj()',1000*60* time['cj']);
            }
            if(time['makeinfo'] > 0){
                iv_makeinfo = window.setInterval('makeinfo()',1000*60* time['makeinfo']);
            }
            if(time['makeindex'] > 0){
                iv_makeindex = window.setInterval('makeindex()',1000*60* time['makeindex']);
            }
            if(time['maketype'] > 0){
                iv_maketype = window.setInterval('maketype()',1000*60* time['maketype']);
            }
            $("#dsinfo").css("display",'');
            $(this).val('执行中...');
            $("#btnGO").attr('disabled',true);
            $("#btnCancel").attr('disabled',false);
            $b=true;
        });
        $("#btnCancel").click(function(){
            window.clearInterval(iv_cj);
            window.clearInterval(iv_makeinfo);
            window.clearInterval(iv_makeindex);
            window.clearInterval(iv_maketype);
            $("#sp_cj").html('');
            $("#sp_makeinfo").html('');
            $("#sp_makeindex").html('');
            $("#sp_maketype").html('');
            $("#dsinfo").css("display",'none');
            $("#btnGO").val('执行任务');
            $("#btnGO").attr('disabled',false);
            $("#btnCancel").attr('disabled',true);
            $b=false;
        });
    });
    function cj()
    {
        var urlc=$("#ds_url option:selected").length;
        $("#ds_url option:selected").each(function(k,v) {
            if(urln < urlc){
                if(urln==k){
                    $("#sp_cj").html("<iframe width='100%' height='200' src='"+v.value+"' scrolling='auto'></iframe>");
                    urln++;
                    return false;
                }
            }
            else{
                urln=0;
            }
        });
    }
    function makeinfo()
    {
        $("#sp_makeinfo").html("<iframe width='100%' height='200' src='admin_makehtml2.php?action=daysview&password=<?php  echo $cfg_cookie_encode; ?>' scrolling='auto'></iframe>");
    }
    function makeindex()
    {
        $("#sp_makeindex").html("<iframe width='100%' height='200' src='admin_makehtml2.php?action=index&by=video&password=<?php  echo $cfg_cookie_encode; ?>' scrolling='auto'></iframe>");
    }
    function maketype()
    {
        
     $("#sp_maketype").html("<iframe width='100%' height='200' src='admin_makehtml2.php?action=allchannel&password=<?php  echo $cfg_cookie_encode; ?>' scrolling='auto'></iframe>");
        
    }
    function reflogin()
    {
        $("#sp_reflogin").html("<iframe width='100%' height='100' src='login.php' scrolling='auto' style='display:none'></iframe>");
    }

</script>

<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
<table border="0" cellpadding="0" cellspacing="0" align="center" class="tb" id="tips">
        <tr class="thead">
          <th>重要提示</th>
        </tr>
        <tr>
          <td class="tipsblock">
		  <div style="padding: 10px;border: 0;border-radius: 4px;font-size: 12px;background-color: #eef5f4;">
&nbsp;*&nbsp;&nbsp;本功能为定时任务挂机版，需要保持此页面常开，关闭此页面则无法使用。<br>
&nbsp;*&nbsp;&nbsp;请谨慎使用本功能，合理设置参数，以免系统崩溃。<br>
&nbsp;*&nbsp;&nbsp;不需要执行定时操作的模块请填写时间为0即可关闭该模块。<br>
&nbsp;*&nbsp;&nbsp;定时采集之前，需要在“资源库管理”功能中先添加资源库API接口。<br>
&nbsp;*&nbsp;&nbsp;在左边的选择框中选择要采集的资源库，选中状态才有效。<br>
&nbsp;*&nbsp;&nbsp;资源库采集范围是24小时内的更新内容。<br>
&nbsp;*&nbsp;&nbsp;内容生成范围是24小时内更新，且更新后未执行过生成操作的视频，当更新过多时可能造成服务器超时。<br>
&nbsp;*&nbsp;&nbsp;栏目生成范围是全站所有栏目的所有数据，需要生成的数据量巨大，请合理设置间隔时间。<br>
&nbsp;*&nbsp;&nbsp;如需采集多个资源库，请重复打开多个本页面，并将其他页面中除采集外的所有模块填写时间为0关闭。					
			</div></td>
        </tr>
</table>
    <div style="width:650px; height:350px;">

        <table style="">
            <tr>
                <td width="50%" rowspan="4"> <div style=""> 
				
				<select id="ds_url" name="ds_url" multiple="" style=" width:250px;height:300px">				
<?php 
$sqlStr="select * from `sea_zyk` WHERE ztype !=3 order by zid ASC";
$dsql->SetQuery($sqlStr);
$dsql->Execute('flink_list');
while($row=$dsql->GetObject('flink_list'))
{
$aid=$row->id;
?>

<option value="admin_reslib2.php?ac=day&rid=<?php  echo $row->zid; ?>&url=<?php  echo $row->zapi ?>&password=<?php  echo $cfg_cookie_encode; ?>">【<?php  echo $row->zid; ?>】【<?php  echo $row->zname ?>】</option>

<?php 
}
?>
</select>
				
				</div></td>
                <td>采集抓取频率(分钟/次)：</td>
                <td><input id="ds_cj" name="ds_cj" type="text" class="layui-input w50" value="60" /></td>
            </tr>
            <tr>
                <td>内容生成频率(分钟/次)： </td>
                <td><input id="ds_makeinfo" name="ds_makeinfo" class="layui-input w50" type="text"  value="60" /></td>
            </tr>
            <tr>
                <td>首页生成频率(分钟/次)： </td>
                <td><input id="ds_makeindex" name="ds_makeindex" class="layui-input w50" type="text"  value="60"/></td>
            </tr>
            <tr>
                <td>栏目生成频率(分钟/次)： </td>
                <td><input id="ds_maketype" name="ds_maketype" class="layui-input w50" type="text" value="180"/></td>
            </tr>
            <tr>
                <td colspan="2"  class="p10">
                    <input type="button" id="btnGO" class="layui-btn" value="执行任务"/>&nbsp;
                    <input type="button" id="btnCancel" class="layui-btn layui-btn-warm" value="停止执行" disabled=true/>
                </td>
            </tr>
        </table>

    </div>

    <div style="width:100%;height:5px;"></div>
    <div id="dsinfo" style="width:650px;display:none;">
        <table border='0' cellpadding='0' cellspacing='0' width='760' height='100%' align='center' style="border:1px solid #CCCCCC; font-size:12px">
            <tr><td valign='top' style="background:#ECF5FF; height:20px;">视频_定时采集</td></tr>
            <tr><td valign='top' id='sp_cj' height='150'>等侍中...</td></tr>
            <tr><td valign='top' height='1' style="background:#e8e8e8"></td></tr>
            <tr><td valign='top' style="background:#ECF5FF; height:20px;">视频_定时生成内容页</td></tr>
            <tr><td valign='top' id='sp_makeinfo' height='150'>等侍中...</td></tr>
            <tr><td valign='top' height='1' style="background:#e8e8e8"></td></tr>
            <tr><td valign='top' style="background:#ECF5FF; height:20px;">视频_定时生成首页</td></tr>
            <tr><td valign='top' id='sp_makeindex' height='150'>等侍中...</td></tr>
            <tr><td valign='top' height='1' style="background:#e8e8e8"></td></tr>
            <tr><td valign='top' style="background:#ECF5FF; height:20px;">视频_定时生成栏目页</td></tr>
            <tr><td valign='top' id='sp_maketype' height='150'>等侍中...</td></tr>
        </table>
        <span id="sp_reflogin"></span>
    </div>

		</div>
	</div>
</div>


</body>
</html>