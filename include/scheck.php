<?php 
if($scheckAC=="check"){	
	$front='front';
	$hashstr=md5($cfg_dbpwd.$cfg_dbname.$cfg_dbuser.$front);
	$svali = $_SESSION['sea_ckstr'];
	$validate = empty($validate) ? '' : strtolower(trim($validate));
	$acurl="?page=".$page."&searchtype=".$searchtype."&order=".$order."&tid=".$tid."&area=".$area."&year=".$year."&letter=".$letter."&yuyan=".$yuyan."&state=".$state."&money=".$money."&ver=".$ver."&jq=".$jq;
	if($validate=='' || $validate != $svali)
	{
		ResetVdValue();
		ShowMsg('验证码不正确!',$acurl."&searchword=".$searchword);
		exit();
	}
	$_SESSION['scheck']=time();
	if($searchtype==5){
		echo '<script>window.location.href="'.$acurl.'";</script>';
	}
}

if($cfg_check_time=="" OR empty($cfg_check_time)){$cfg_check_time=300;}
$scheck_time=time() - $_SESSION['scheck'];
if($scheck_time>$cfg_check_time){
	echo '
	<head><title>系统安全验证</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <style>
	.container{ padding:9px 20px 20px; text-align:left; width: 90%;max-width: 420px;margin: auto;}
    .infobox{ clear:both; margin-bottom:10px; padding:30px; text-align:center; border-top:1px solid #0099CC; border-bottom:1px solid #0099CC; background:#F2F9FD; zoom:1; }
    .infotitle1{ margin-bottom:10px; color:#09C; font-size:14px; font-weight:700;color:#ff0000; }
    h3{ margin-bottom:10px; font-size:14px; color:#09C; }
	body{background:#F9FAFD;color:#818181;}
    input{
        border: 1px solid #ccc;
        padding: 7px 0px;
        border-radius: 3px;
        padding-left:5px;
		width:80px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s
    }
	input:hover, input:hover, button:hover {
    outline: 0;
    border: 1px solid #09c;
    box-shadow: 0px 0px 2px 0px #09c;
	}
	input:foucs, input:foucs, button:foucs {
    outline: 0;
    border: 1px solid #09c;
    box-shadow: 0px 0px 2px 0px #09c;
	}
	input:blur, input:blur, button:blur {
    outline: 0;
    border: 1px solid #09c;
    box-shadow: 0px 0px 2px 0px #09c;
	}
	.title{margin-bottom:10px;color:#6a6a6a;}
    </style>

</head><body>	<script>
	 function check() {
            var form = document.getElementById("f_login"); 
            var yzm = form.vdcode.value.replace(/(^\s*)|(\s*$)/g, "");
            if (yzm.length == 0 ) {
                alert("验证码不能为空！");
                return false; 
            }else{
                return true; 
            }
        }
	</script>';

	$acurl="&page=".$page."&searchtype=".$searchtype."&order=".$order."&tid=".$tid."&area=".$area."&year=".$year."&letter=".$letter."&yuyan=".$yuyan."&state=".$state."&money=".$money."&ver=".$ver."&jq=".$jq;
	echo '<form id="f_login"  onSubmit="return check()"  action="?scheckAC=check';echo $acurl;echo '" method="post">';
	echo '<div class="container" id="cpcontainer">
    <h3>安全验证...</h3>
    <div class="infobox">
        <div class="title">请输入正确的验证码继续访问</div>
        <div class="text">
           <img id="vdimgck" src="include/vdimgck.php" alt="看不清？点击更换" align="absmiddle" style="cursor:pointer;height: 30px;" onclick="this.src=this.src+\'?get=\' + new Date()"> <input name="validate" type="text" placeholder="验证码" class="sea_main_9" id="vdcode" style="text-transform:uppercase;" onclick="document.getElementById(\'vdimgck\').style.display=\'inline\';" tabindex="3"><input type="hidden" name="searchword" value="'.$searchword.'">&nbsp;<input type="submit" value="提交" style="width:60px;"> 
        </div>
    </div>
</div></form></body>';
	exit;
}

?>