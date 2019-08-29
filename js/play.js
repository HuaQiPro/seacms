var playerw='100%';//电脑端播放器宽度
var playerh='100%';//电脑端播放器高度
var mplayerw='100%';//手机端播放器宽度
var mplayerh='100%';//手机端播放器高度
var adsPage="https://www.seacms.net/api/loading.html";//视频播放前广告页路径
var adsTime=3;//视频播放前广告时间，单位秒
var jxAname="云播放①";
var jxBname="云播放②";
var jxCname="云播放③";
var jxDname="云播放④";
var jxEname="云播放⑤";
var jxAapi="https://";
var jxBapi="https://";
var jxCapi="https://";
var jxDapi="https://";
var jxEapi="https://";
var forcejx="no";
var unforcejx="yunpan#swf#iframe#url#xigua#ffhd#jjvod";
var unforcejxARR = unforcejx.split('#');


function contains(arr, obj) {  
    var i = arr.length;  
    while (i--) {  
        if (arr[i] === obj) {  
            return true;  
        }  
    }  
    return false;  
}

function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = ["Android", "iPhone",
                "SymbianOS", "Windows Phone",
                "iPad", "iPod"];
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}
 
var flag = IsPC(); //true为PC端，false为手机端
if(flag==false)
{
	playerw=mplayerw;
	playerh=mplayerh;
}