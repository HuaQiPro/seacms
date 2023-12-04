<?php 
error_reporting(0);
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Cache-Control" "no-transform " />
<link rel="alternate" type="application/vnd.wap.xhtml+xml" media="handheld" href="target"/>
<meta http-equiv="Cache-Control" content="no-transform" /> 
<meta http-equiv="Cache-Control" content="no-siteapp" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="full-screen" content="yes">
<meta name="browsermode" content="application">
<meta name="x5-fullscreen" content="true">
<meta name="x5-page-mode" content="app">
<meta charset="UTF-8">
    <title>弹幕播放器</title>
    <link rel="stylesheet" href="css/yzmplayer.css">
    <style>
        .yzmplayer-full-icon span svg,
        .yzmplayer-fulloff-icon span svg {
            display: none;
        }

        .yzmplayer-full-icon span,
        .yzmplayer-fulloff-icon span {
            background-size: contain !important;
            float: left;
            width: 22px;
            height: 22px;
        }

        .yzmplayer-full-icon span {
            background: url(img/5eca627664041.png) center no-repeat;
        }

        .yzmplayer-fulloff-icon span {
            background: url(img/5eca6278b7137.webp) center no-repeat;
        }

        #loading-box {
            background: #<?php echo ($_GET['color']); ?> !important;
        }

        #vod-title {
            overflow: unset;
            width: 285px;
            white-space: normal;
            color: #fb7299;
        }

        #vod-title e {
            padding: 2px;
        }

        .layui-layer-dialog {
            text-align: center;
            font-size: 16px;
            padding-bottom: 10px;
        }

        .layui-layer-btn.layui-layer-btn- {
            padding: 15px 5px !important;
            text-align: center;
        }

        .layui-layer-btn a {
            font-size: 12px;
            padding: 0 11px !important;
        }

        .layui-layer-btn a:hover {
            border-color: #00a1d6 !important;
            background-color: #00a1d6 !important;
            color: #fff !important;
        }

        .yzmplayer-controller .yzmplayer-icons .yzmplayer-toggle input+label.checked:after {
            left: 17px;
        }

        .yzmplayer-setting-jlast:hover #jumptime,
        .yzmplayer-setting-jfrist:hover #fristtime {
            display: block;
            outline-style: none
        }

        #player_pause .tip {
            color: #f4f4f4;
            position: absolute;
            font-size: 14px;
            background-color: hsla(0, 0%, 0%, 0.42);
            padding: 2px 4px;
            margin: 4px;
            border-radius: 3px;
            right: 0;
        }

        #player_pause {
            position: absolute;
            z-index: 9999;
            top: 50%;
            left: 50%;
            border-radius: 5px;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            max-width: 80%;
            max-height: 80%;
        }

        #player_pause img {
            width: 100%;
            height: 100%;
        }

        #jumptime::-webkit-input-placeholder,
        #fristtime::-webkit-input-placeholder {
            color: #ddd;
            opacity: .5;
            border: 0;
        }

        #jumptime::-moz-placeholder {
            color: #ddd;
        }

        #jumptime:-ms-input-placeholder {
            color: #ddd;
        }

        #jumptime,
        #fristtime {
            width: 50px;
            border: 0;
            background-color: #414141;
            font-size: 12px;
            padding: 3px 3px 3px 3px;
            margin: 2px;
            border-radius: 12px;
            color: #fff;
            position: absolute;
            left: 5px;
            top: 3px;
            display: none;
            text-align: center;
        }

        #link {
            display: inline-block;
            width: 100px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            font-size: 14px;
            border-radius: 22px;
            margin: 0px 10px;
            color: #fff;
            overflow: hidden;
            box-shadow: 0px 2px 3px rgba(0, 0, 0, .3);
            background: rgb(0, 161, 214);
            position: absolute;
            z-index: 9999;
            top: 20px;
            right: 35px;
        }

        #close c {
            float: left;
            display: none;
        }

        .dmlink,
        .playlink,
        .palycon {
            float: left;
            width: 400px;
        }

        #link3-error {
            display: none;
        }
    </style>
	<script>
	var zurl="<?php echo ($_GET['vid']); ?>";
	var za=zurl.split("-");
	var zvid=za[0];
	var zvfrom=za[1];
	var zvpart=za[2];
	</script>
    <script src="js/yzmplayer.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/setting.js"></script>
    <?php
	$urlarr=explode(',',$_GET['url']);
    if (strpos($urlarr[0], 'm3u8')) {
        echo '<script src="js/hls.min.js"></script>';
    } elseif (strpos($urlarr[0], 'flv')) {
        echo '<script src="js/flv.min.js"></script>';
    }
	$gid=$_SESSION['sea_user_group'];
	$user=$_SESSION['sea_user_name'];
	if($gid=="" OR empty($gid)){$gid=1;}
	if($user=="" OR empty($user)){$user='游客';}
	$next=$_GET['next'];
	if($_GET['nextdz']==""){$next='';}
    ?>
    <script src="js/layer.js"></script>

    <script>
        var css = '<style type="text/css">';
        var d, s;
        d = new Date();
        s = d.getHours();
        if (s < 17 || s > 23) {
            css += '#loading-box{background: #fff;}'; //白天
        } else {
            css += '#loading-box{background: #000;}'; //晚上
        }
        css += '</style>';
    </script>
</head>

<body>
    <div id="player"></div>
    <div id="ADplayer"></div>
    <div id="ADtip"></div>
    <script>
        var up = {
            "usernum": "<?php include("tj.php"); ?>", //在线人数
            "mylink": "", //播放器路径，用于下一集
            "diyid": [0, 'err', 1] //自定义弹幕id
        }
        var config = {
            "api": '/js/player/dmplayer/dmku/', //弹幕接口
            "av": "<?php echo ($urlarr[1]); ?>", //B站弹幕id 45520296
            "url": "<?php echo ($urlarr[0]); ?>", //视频链接
            "id": "<?php echo ($_GET['vid']); ?>", //视频id
            "sid": "", //集数id
            "pic": "", //视频封面
            "title": "", //视频标题
            "next": "<?php echo ($next); ?>", //下一集链接
            "user": '<?php echo ($user); ?>', //用户名
            "group": '<?php echo ($gid); ?>' //用户组
        }
        YZM.start()
    </script>
</body>

</html>