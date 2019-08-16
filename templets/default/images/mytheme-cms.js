/*!
 * 版本：MYUI Copyright © 2019
 * 作者：QQ726662013版权所有
 * 官网：https://www.mytheme.cn
 */

var Myui = {
	'Comment': {
		'Init':function(){
			$('body').on('click', '.my_comment_submit', function(e){			        
		        if($(this).parent().parent().parent().find(".comment_data").val() == ''){
	                layer.msg("请输入评论内容");
	                return false;
	           }
		        Myui.Comment.Submit();
			});
			$('body').on('click', '.my_comment_report', function(e){
                var $that = $(this);
                if($(this).attr("data-id")){
                    MyTheme.Ajax(seacms.path + '/index.php/comment/report.html?id='+$that.attr("data-id"),'get','json','',function(r){
                        $that.addClass('disabled');                       
                        layer.msg(r.msg);
                    });
                }
            });
			$('body').on('click', '.my_comment_reply', function(e){
                var $that = $(this);
                if($that.attr("data-id")){
                    var str = $that.html();
                    $('.comment_reply_form').remove();
                    if (str == '取消回复') {
                        $that.html('回复');
                        return false;
                    }
                    if (str == '回复') {
                        $('.my_comment_reply').html('回复');
                    }
                    var html = $('.comment_form').prop("outerHTML");

                    var oo = $(html);
                    oo.addClass('comment_reply_form');
                    oo.find('input[name="comment_pid"]').val( $that.attr("data-id") );

                    $that.parent().after(oo);
                    $that.html('取消回复');
                }
            });
            $('body').on('click', '.my_comment_report', function(e){
                var $that = $(this);
                if($(this).attr("data-id")){
                    MyTheme.Ajax(seacms.path + '/index.php/comment/report.html?id='+$that.attr("data-id"),'get','json','',function(r){
                        $that.addClass('disabled');
                        layer.msg(r.msg);
                    });
                }
            });
		},
		'Show':function($page){
			MyTheme.Ajax(seacms.path + '/index.php/comment/ajax.html?rid='+$('.myui_comment').attr('data-id')+'&mid='+ $('.myui_comment').attr('data-mid') +'&page='+$page,'get','json','',function(r){
			    $(".myui_comment").html(r);
			},function(){
			    $(".myui_comment").html('<p class="text-center"><a href="javascript:void(0)" onclick="Myui.Comment.Show('+$page+');">评论加载失败，点击我刷新...</a></p>');
			});
        },
		'Submit':function(){		        
			MyTheme.Ajax(seacms.path + '/index.php/comment/saveData','post','json',$(".comment_form").serialize() + '&comment_mid='+ $('.myui_comment').attr('data-mid') + '&comment_rid=' + $('.myui_comment').attr('data-id'),function(r){
	            if(r.code==1){ 
	            	layer.msg("评论成功，正在刷新...",{anim:5},function(){
					    Myui.Comment.Show(1);
					});
		        } else {
		        	if(MAC.Gbook.Verify==1){
		           	 	$('#verify_img').click();
		            }
		            layer.msg(r.msg);
		        }
	        });
		}
	},
	'Gbook': {
		'Init':function(){
			$('body').on('click', '.gbook_submit', function(e){
				if($(".gbook_data").val() == ''){
		            layer.msg("请输入留言内容");
		            return false;
		        }
				Myui.Gbook.Submit();
			});
		},
		'Submit':function(){
			MyTheme.Ajax(seacms.path + '/index.php/gbook/saveData','post','json',$("#myform").serialize(),function(r){
	            if(r.code==1){ 
	            	layer.msg("留言成功，正在刷新...",{anim:5},function(){
					    location.reload();
					});	            
		        } else {
		        	if(MAC.Gbook.Verify==1){
		           	 	$('#verify_img').click();
		            }
		            layer.msg(r.msg);
		        }
	        });
		}
	},
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
						$.getJSON(seacms.path+'/index.php/ajax/score?mid='+$('#rating').attr('data-mid')+'&id='+$('#rating').attr('data-id')+'&score='+($(this).attr('val')*2), function (r) {
							if (parseInt(r.code) == 1) {
								layer.msg(r.msg);
								hadpingfen = 1;
							}
							else {
								hadpingfen = 1;
								layer.msg(r.msg);
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
		var searchWidth= $('#search').width();
		try {
			$('.search_wd').autocomplete(seacms.path + '/index.php/ajax/suggest?mid=1', {		
			    resultsClass: "autocomplete-suggestions",
			    width: searchWidth, scrollHeight: 410, minChars: 1, matchSubset: 0, cacheLength: 10, multiple: false, matchContains: true, autoFill: false,
			    dataType: "json",
			    parse: function (r) {
			        if (r.code == 1) {
			        	$(".head-dropdown").hide();
			            var parsed = [];
			            $.each(r['list'], function (index, row) {
			                row.url = r.url;
			                parsed[index] = {
			                    data: row
			                };
			            });
			            return parsed;
			        } else {
			            return {data: ''};
			        }
			    },
			    formatItem: function (row, i, max) {
			        return row.name;
			    },
			    formatResult: function (row, i, max) {
			        return row.text;
			    }
			}).result(function (event, data, formatted) {
			    $(this).val(data.name);
			    location.href = data.url.replace('mac_wd', encodeURIComponent(data.name));
			});
		}
		catch(e){}
	},
	'Favorite': function() {
		if($('.favorite').length>0){
			$('body').on('click', 'a.favorite', function(e){
		        //是否需要验证登录
		        if(MAC.User.IsLogin == 0){
		        	layer.msg("您还没有登录，正在跳转...",function(){
					    location.href = seacms.path + '/index.php/user/login';
					});
		            return;
		        }
		        var $that = $(this);
		        if($that.attr("data-id")){
		            $.ajax({
		                url: seacms.path+'/index.php/user/ajax_ulog/?ac=set&mid='+$that.attr("data-mid")+'&id='+$that.attr("data-id")+'&type='+$that.attr("data-type"),
		                cache: false,
		                dataType: 'json',
		                success: function($r){
		                	layer.msg($r.msg);
		                }
		            });
		        }
		    });
		}
	},
	'User': {
		'BuyPopedom':function(o){
            var $that = $(o);
            if($that.attr("data-id")){
                if (confirm('您确认购买此条数据访问权限吗？')) {
                    MyTheme.Ajax(seacms.path + '/index.php/user/ajax_buy_popedom.html?id=' + $that.attr("data-id") + '&mid=' + $that.attr("data-mid") + '&sid=' + $that.attr("data-sid") + '&nid=' + $that.attr("data-nid") + '&type=' + $that.attr("data-type"),'get','json','',function(r){
                        $that.addClass('disabled');
                        layer.msg($r.msg);
                        if (r.code == 1) {
                            top.location.reload();
                        }
                        $that.removeClass('disabled');
                    });
                }
            }
        }
	}
};

$(function(){	
	Myui.Comment.Init();
	Myui.Gbook.Init();
	Myui.Autocomplete();
	Myui.Favorite();
});