/*!
 * Copyright http://v.shoutu.cn
 * Email 726662013@qq.com
 */
jQuery(function(){
	$.getScript("//open.thunderurl.com/thunder-link.js", function() {
		
		// 单文件下载
        $(".common_down").on("click",function(){
            var link=$(this).parents("li").find("input[type='text']");
            var url=link.eq(0).val();
            var filename=$(this).parents("li").find(".text").eq(0).text();
            thunderLink.newTask({
                downloadDir: '下载目录',
                tasks: [{
                    name: filename,
                    url: url,
                    size: 0
                }]
            });
        });

		// 多文件下载
        $("input[name='checkall']").on("click",function(e){
            var checkboxs=$(this).parent().parent().parent().parent().find("input[name^='down_url_list_']");
            for(let i=checkboxs.length;i--;)
                checkboxs[i].checked=this.checked;
        })

        $(".thunder_down_all").on("click",function(){
        	checked=$(this).parents(".downlist").find("li input[type='checkbox']:checked");
    		
    		if(checked.length<1){
    				alert("请选中您要下载的文件！") 
    			}
    		else
    			{
	    			var tasks=[];
		            var links=$(this).parents(".downlist").find("li input[type='text']");
		            var selectbox=$(this).parents(".downlist").find("li input[type='checkbox']");
		
		    		for(let i=0;i<links.length;++i){
		                if(selectbox.eq(i).is(':checked')){
		                    var task={
		                    	url:links.eq(i).val(),
		                    	size:0
		                    };
		                    tasks.push(task);
		                }
		            }
		    		 		
		    		thunderLink.newTask({
		                downloadDir: '下载目录',
		                installFile: '',
		                taskGroupName: '下载',
		                tasks: tasks,
		                excludePath: ''
		           });           	
    		}
            
        });
	
		//启动迅雷看看
        $(".thunderkk").on("click",function(){
            var link=$(this).parents(".downlist").find("li input[type='text']");
            var url=link.eq(0).val();
            kkPlay(url,"");
        })
        
        var kkDapCtrl = null;
        
        function kkGetDapCtrl() {
            if (null == kkDapCtrl) {
                try {
                    if (window.ActiveXObject) {
                        kkDapCtrl = new ActiveXObject("DapCtrl.DapCtrl");
                    } else {
                        var browserPlugins = navigator.plugins;
                        for (var bpi = 0; bpi < browserPlugins.length; bpi++) {
                            try {
                                if (browserPlugins[bpi].name.indexOf('Thunder DapCtrl') != -1) {
                                    var e = document.createElement("object");
                                    e.id = "dapctrl_history";
                                    e.type = "application/x-thunder-dapctrl";
                                    e.width = 0;
                                    e.height = 0;
                                    document.body.appendChild(e);
                                    break;
                                }
                            } catch(e) {}
                        }
                        kkDapCtrl = document.getElementById('dapctrl_history');
                    }
                } catch(e) {}
            }
            return kkDapCtrl;
        }
        
        function kkPlay(url, moviename) {
            var dapCtrl = kkGetDapCtrl();
            try {
                var ver = dapCtrl.GetThunderVer("KANKAN", "INSTALL");
                var type = dapCtrl.Get("IXMPPACKAGETYPE");
                if (ver && type && ver >= 672 && type >= 2401) {
                    dapCtrl.Put("SXMP4ARG", '"' + url + '" /title "' + moviename + '" /startfrom "_web_xunbo" /openfrom "_web_xunbo"');
                } else {
                    var r = confirm("\u8bf7\u5148\u4e0b\u8f7d\u5b89\u88c5\u8fc5\u96f7\u770b\u770b\uff0c\u70b9\u786e\u5b9a\u8fdb\u5165\u8fc5\u96f7\u770b\u770b\u5b98\u7f51\u4e0b\u8f7d");
                    if (r == true) {
                        window.open('http://www.kankan.com/app/xmp.html','','')
                    }
                }
            } catch(e) {
                var r = confirm("\u8bf7\u5148\u4e0b\u8f7d\u5b89\u88c5\u8fc5\u96f7\u770b\u770b\uff0c\u70b9\u786e\u5b9a\u8fdb\u5165\u8fc5\u96f7\u770b\u770b\u5b98\u7f51\u4e0b\u8f7d");
                if (r == true) {
                    window.open('http://www.kankan.com/app/xmp.html','','')
                }
            }
        }
    });   
    
    $(".downlist").find("li input[type='text']").each(function(){
		var downurl = $(this).val();		
		var clipboard = new Clipboard(this, {
			text: function() {									
				return downurl;
			}
		});
		clipboard.on('success', function(e) {
			alert("地址复制成功");
		});
	});
    
    $(".Codyurl").each(function(){
		var downurl = $(this).attr("data-text");		
		var clipboard = new Clipboard(this, {
			text: function() {									
				return downurl;
			}
		});
		clipboard.on('success', function(e) {
			alert("地址复制成功");
		});
	});
	
	function strUnicode2Ansi(a) {
	    var b = a.length,
	        c = "";
	    for (var d = 0; d < b; d++) {
	        var e = a.charCodeAt(d);
	        e < 0 && (e += 65536), e > 127 && (e = UnicodeToAnsi(e));
	        if (e > 255) {
	            var f = e & 65280;
	            f >>= 8;
	            var g = e & 255;
	            c += String.fromCharCode(f) + String.fromCharCode(g)
	        } else c += String.fromCharCode(e)
	    }
	    return c
	}

	function strAnsi2Unicode(a) {
	    var b = a.length,
	        c = "",
	        d;
	    for (var e = 0; e < b; e++) {
	        var f = a.charCodeAt(e);
	        f > 127 ? d = AnsiToUnicode((f << 8) + a.charCodeAt(++e)) : d = f, c += String.fromCharCode(d)
	    }
	    return c
	}
	function encode64(a) {
	    var b = "",
	        c, d, e = "",
	        f, g, h, i = "",
	        j = 0;
	    do c = a.charCodeAt(j++), d = a.charCodeAt(j++), e = a.charCodeAt(j++), f = c >> 2, g = (c & 3) << 4 | d >> 4, h = (d & 15) << 2 | e >> 6, i = e & 63, isNaN(d) ? h = i = 64 : isNaN(e) && (i = 64), b = b + keyStr.charAt(f) + keyStr.charAt(g) + keyStr.charAt(h) + keyStr.charAt(i), c = d = e = "", f = g = h = i = "";
	    while (j < a.length);
	    return b
	}

    keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	
    var BeyondDecode=function(str,from){
		var url,decodeURL
		url=str.replace('I0JleW9uZCMj','');
		if(from=='qqdl'){
			decodeURL=strAnsi2Unicode(decode64(url.replace("thunder://","")).replace("AA","").replace("ZZ",""));
			url=decodeURL.indexOf('magnet:')!=-1?decodeURL:"qqdl://"+strAnsi2Unicode(encode64(strUnicode2Ansi(decodeURL)));
		}
		return url;
	}
    
	$(".xiaomi").each(function(){
		$(this).attr('href','https://d.miwifi.com/d2r/?url='+strAnsi2Unicode(encode64(strUnicode2Ansi(BeyondDecode($(this).parent().parent().find(".down_url").val()))))+'');
	});

});
