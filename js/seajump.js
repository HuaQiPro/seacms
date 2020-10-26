var mskin='0';
var host='http://www.ceshi.cn';
var mhost='http://m.seacms.net';

var SEAURL;

function GetUrlRelativePath()
	{
		var url = document.location.toString();
		var arrUrl = url.split("//");

		var start = arrUrl[1].indexOf("/");
		var relUrl = arrUrl[1].substring(start);
		return relUrl;
	}
var cs=GetUrlRelativePath();  

if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)) {
	if(mskin=='3'){window.location = mhost+cs;}
	if(mskin=='4'){window.location = mhost;}
	if(mskin=='2'){	
		switch(seatype){
				
				case 'index':
				SEAURL=host+'/index.php';
				break;
				
				case 'newsindex':
				SEAURL=host+'/news/index.php';
				break;
				
				case 'list':
				SEAURL=host+'/list/index.php?'+seaid+'-'+seapage+'.html';
				break;
				
				case 'newslist':
				SEAURL=host+'/articlelist/index.php?'+seaid+'-'+seapage+'.html';
				break;
				
				case 'video':
				SEAURL=host+'/detail/index.php?'+seaid+'.html';
				break;
				
				case 'news':
				SEAURL=host+'/article/index.php?'+seaid+'-'+seapage+'.html';
				break;
				
				case 'play':
				seaplaylink=seaplaylink.replace('.html','');
				seaplaylink=seaplaylink.replace('.htm','');
				seaplaylink=seaplaylink.replace('.shtm','');
				seaplaylink=seaplaylink.replace('.shtml','');
				var strs= new Array(); 
				strs=seaplaylink.split("-"); 
				var p1=strs['1'];
				var p2=strs['2'];
				SEAURL=host+'/video/index.php?'+seaid+'-'+p1+'-'+p2+'.html';
				break;
				
				case 'topiclist':
				SEAURL=host+'/topic/index.php?'+seapage+'.html';
				break;
				
				case 'topic':
				SEAURL=host+'/topiclist/index.php?'+seaid+'-'+seapage+'.html';
				break;

				default:
				SEAURL=SEAURL;
		
		
		}
		window.location = SEAURL;

	}
}