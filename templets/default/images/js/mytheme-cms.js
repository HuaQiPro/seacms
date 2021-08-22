var Myui = {
	'Score':function(){
		var hadpingfen = 0;
		$("ul.rating li").each(function(i) {
			var $title = $(this).attr("title");
			var $lis = $("ul.rating li");
			var num = $(this).index();
			var n = num + 1;
			$(this).click(function () {
					if (hadpingfen > 0) {
						layer.msg('已经评分,请务重复评分');
					}
					else {
						$lis.removeClass("active");
						$("ul.rating li:lt(" + n + ")").find(".fa").addClass("fa-star").removeClass("fa-star-o");
						$("#ratewords").html($title);
						var score = $(this).attr("val");
						var nocache =  new Date().getTime();
						MyTheme.Ajax("/include/ajax.php?id="+$('#rating').attr('data-id')+"&action=score&score="+score+"&timestamp="+nocache,'get','text','',function(r){
							
							if((''+r.responseText).indexOf("havescore")!=-1){
								layer.msg("您已经评过分啦");
								hadpingfen = 1;
							}
							else {
								hadpingfen = 1;
								layer.msg("感谢参与！");
							}
						});
					}
				}
			).hover(function () {
				this.myTitle = this.title;
				this.title = "";
				$(this).nextAll().find(".fa").addClass("fa-star-o").removeClass("fa-star");
				$(this).prevAll().find(".fa").addClass("fa-star").removeClass("fa-star-o");
				$(this).find(".fa").addClass("fa-star").removeClass("fa-star-o");
				$("#ratewords").html($title);
			}, function () {
				this.title = this.myTitle;
				$("ul.rating li:lt(" + n + ")").removeClass("hover");
			});
		});
	},
	'Autocomplete': function() {
		$('#wd').keyup(function(){
			var keywords = $(this).val();
			if (keywords=='') { $('#word').hide(); return };
			$.ajax({
				url: '/ass.php?wd=' + keywords,
				dataType: 'jsonp',
				jsonp: 'cb', 
				beforeSend:function(){
					$('.wordlist').append('<li>正在加载。。。</li>');
				},
				success:function(data){
					$('#word').show();
					$('.wordlist').empty();
					if (data.s==''){
						$('.wordlist').append('<li>未找到与 "' + keywords + '"相关的结果</li>');
					}
					$.each(data.s, function(){
						$('.wordlist').append('<li>'+ this +'</li>');
					});
				},
				error:function(){
					$('#word').show();
					$('.wordlist').empty();
					$('#word').append('<li>查找"' + keywords + '"失败</li>');
				}
			});
		});
		$(document).on('click','.wordlist li',function(){
			var word = $(this).text();
			$('#wd').val(word);
			$('#word').hide();
			$('.submit').trigger('click');触发搜索事件
		})		
		var clear = function(){ $('#word').hide();}
		$("input").blur(function(){			    
			 setTimeout(clear,500); 		
		});
	},
	'Other': {
		'Topbg': function(high){				
			$(".myui-topbg").css({"height":high});
			$("#header-top").addClass("color");
			$(window).scroll(function(){
				if($(window).scrollTop()>10){
					$("#header-top").removeClass("color");
				}else if($(window).scrollTop()<110){
					$("#header-top").addClass("color");
				}
			});
		}	
	}
};

$(function(){	
	Myui.Autocomplete();
});