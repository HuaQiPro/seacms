$(function(){
	
	$.getScript("//open.thunderurl.com/thunder-link.js", function() {
		
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

        $("input[name='checkall']").on("click",function(e){
            var checkboxs=$(this).parent().parent().parent().parent().find("input[name^='down_url_list_']");
            for(let i=checkboxs.length;i--;)
                checkboxs[i].checked=this.checked;
        });

        $(".thunder_down_all").on("click",function(){
        	checked=$(this).parents(".downlist").find("li input[type='checkbox']:checked");
    		
    		if(checked.length<1){
    				layer.msg("请选中要下载的文件");
    			}
    		else
    			{
	    			var tasks=[];
		            var links=$(this).parents(".downlist").find("li .down_url");
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
		                taskGroupName: '下载文件',
		                tasks: tasks,
		                excludePath: ''
		           });           	
    		}
            
        });
	
		//启动迅雷看看
		if($(".thunderkk").length){
	        $(".thunderkk").on("click",function(){
	            var link=$(this).parents(".downlist").find("li .down_url");
	            var url=link.eq(0).val();
	            kkPlay(url,"");
	        });        
        
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
	                        window.open('http://www.kankan.com/app/xmp.html','','');
	                    }
	                }
	            } catch(e) {
	                var r = confirm("\u8bf7\u5148\u4e0b\u8f7d\u5b89\u88c5\u8fc5\u96f7\u770b\u770b\uff0c\u70b9\u786e\u5b9a\u8fdb\u5165\u8fc5\u96f7\u770b\u770b\u5b98\u7f51\u4e0b\u8f7d");
	                if (r == true) {
	                    window.open('http://www.kankan.com/app/xmp.html','','');
	                }
	            }
	        }
	    }
	});   
	
	if($(".downlist").length){  
	    $(".downlist").find("li input[type='text']").each(function(){
			var downurl = $(this).val();		
			var clipboard = new Clipboard(this, {
				text: function() {									
					return downurl;
				}
			});
			clipboard.on('success', function(e) {
				layer.msg("地址复制成功");
			});
		});
	}
    
    if($(".Codyurl").length){
	    $(".Codyurl").each(function(){
			var downurl = $(this).attr("data-text");		
			var clipboard = new Clipboard(this, {
				text: function() {									
					return downurl;
				}
			});
			clipboard.on('success', function(e) {
				layer.msg("地址复制成功");
			});
		});
	}
    
});