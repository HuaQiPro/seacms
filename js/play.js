var playerw='100%';//电脑端播放器宽度
var playerh='100%';//电脑端播放器高度
var mplayerw='100%';//手机端播放器宽度
var mplayerh='100%';//手机端播放器高度
var adsPage="https://www.seacms.org/api/loading.html";
var adsTime=5;
var jxAname="云播放①";
var jxBname="云播放②";
var jxCname="云播放③";
var jxDname="云播放④";
var jxEname="云播放⑤";
var jxFname="云播放⑥";
var jxGname="云播放⑦";
var jxHname="云播放⑧";
var jxIname="云播放⑨";
var jxAapi="https://1";
var jxBapi="https://2";
var jxCapi="https://3";
var jxDapi="https://4";
var jxEapi="https://5";
var jxFapi="https://6";
var jxGapi="https://7";
var jxHapi="https://8";
var jxIapi="https://9";
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