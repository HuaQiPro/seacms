<?php include('login.php') ?>
<?php include('data.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>弹幕播放器后台管理</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./js/layui/css/layui.css" />
	<script type="text/javascript" src="./js/layui/layui.js" type="text/javascript" charset="utf-8"></script>
	<script src="./js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="./js/config.js" type="text/javascript" charset="utf-8"></script>

	<style>
		.layui-elem-field {
			border-color: #00bcd4;
		}

		.width {
			width: 120px !important;
			text-align: center;
		}

		.long {
			width: 300px !important;
			text-align: center;
		}

		.smt {
			width: 75px !important;
			text-align: center;
		}

		.sm {
			width: 50px !important;
			text-align: center;
		}

		.layui-textarea {
			min-height: 60px;
			height: 30px;
		}

		#configSave {
			margin-bottom: 0;
			background-color: #00BCD4;
			color: #ffffff;
			height: 39px;
			border-radius: 2px 2px 0 0;
			width: 80px;
			border-width: 1px;
			border-style: solid;
			border-color: #00BCD4;
		}

		.layui-form-pane .layui-form-label {
			padding: 8px 5px;
		}

button{padding: 0px 5px;outline: 0;border:1px solid #ccc; border-radius:2px;vertical-align:middle;background-color:#ccc;height: 28px;cursor: pointer;color:#333;}
button:hover{outline: 0;  border: 1px solid #09c;box-shadow: 0px 0px 2px 0px #09c;background-color:#ccc; }
.layui-input{outline: 0;  border: 1px solid #ccc;}
.layui-input:hover{outline: 0;  border: 1px solid #09c;box-shadow: 0px 0px 2px 0px #09c; }
.layui-btn-danger{background-color:#ccc;color:#333;border:1px solid #ccc;}
.layui-btn-warn{background-color:#ccc;color:#333;border:1px solid #ccc;}
.layui-btn{background-color:#ccc;color:#333;border:1px solid #ccc;}
.layui-btn:hover{outline: 0; color:#333; border: 1px solid #09c;box-shadow: 0px 0px 2px 0px #09c;background-color:#ccc; }
.layui-laypage-em {background-color: #b0d2e2;}
.bottom{ width:99%;height:15px;border-top:4px solid #c6dbe7; background:#FBFEFF;line-height:15px;margin:0 auto; font-size:12px;text-align:center;padding-top:6px;}

.layui-tab-title {
    position: relative;
    left: 0;
    background: #e6f2fb;
    display: table;
    width: 99%;
    white-space: nowrap;
    font-size: 0;
    transition: all .2s;
    -webkit-transition: all .2s;
    margin-left: 10px;
	color:#337ab7;
	height: auto;
	border: 0; 
	
}
.layui-tab-title li{line-height:32px;min-width:auto;font-size: 12px;}
.layui-tab-title .layui-this {
    color: #fff;
    background: #7b9cb8;
	font-size: 12px;
}
.layui-tab-title .layui-this:after {
    position: absolute;
    left: 0;
    top: 0;
    content: '';
    width: 100%;
    height: auto;
    border: 0px;
    border-radius:0px;
    box-sizing: border-box;
    pointer-events: none;
	font-size: 12px;
}
.layui-tab {
    margin: 6px 0;

}
	</style>
</head>

<body>

	<form class="layui-form layui-form-pane" name="configform" id="configform">
		<div class="layui-tab" overflow>
		
			<ul class="layui-tab-title">
				<li class="layui-this">基本设置</li>
				<li class="">广告设置</li>
				<li class="">弹幕列表</li>
				<li class="">举报列表</li>
			</ul>
			<div class="layui-tab-content">
				<div class="layui-tab-item layui-show" name="基本设置">
				<button type="button" class="layui-btn layui-btn-disabled" style="margin-bottom:5px;color: #546c71;background: #e6eaec;border: 0;"><i class="layui-icon layui-icon-rss" style="color: #1E9FFF;"></i> 弹幕功能仅支持弹幕播放器，可以在播放器设置里强制使用弹幕播放器，也可以指定播放来源为弹幕播放器</button>
					<div class="layui-form-item">
						<label class="layui-form-label">弹幕开关</label>
						<div class="layui-input-block">
						<input type="checkbox" name="yzm[danmuon]" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF" <?php $t = $yzm['danmuon'];if($t=="on") {echo "checked";} ?>>
							<div class="layui-unselect layui-form-switch" lay-skin="_switch"><em>Off</em><i></i></div>
						</div>
					</div>
					<div class="layui-form-item" style="">
						<label class="layui-form-label">弹幕权限</label>
						<div class="layui-input-inline">
							<select name="yzm[ads][set][group]" lay-verify="required">
								<option value="1" <?php $t = $yzm['ads']['set']['group'];
														if ($t == "1") {
															echo "selected";
														} ?>>无限制</option>
								<option value="2" <?php $t = $yzm['ads']['set']['group'];
													if ($t == "2") {
														echo "selected";
													} ?>>注册会员</option>
		
							</select>
						</div>
						<div class="layui-form-mid layui-word-aux">限制是否只有注册会员才可以发弹幕</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">主题颜色</label>
						<div class="layui-input-inline">
							<input type="text" name="yzm[color]" value="<?php echo $yzm['color'] ?>" size="30" class="layui-input upload-input" placeholder="颜色代码">
						</div>
						<div class="layui-form-mid layui-word-aux">颜色代码 例如：#00a1d6</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">LOGO</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[logo]" value="<?php echo $yzm['logo'] ?>" size="30" class="layui-input upload-input" placeholder="图片地址">
						</div>
						<div class="layui-form-mid layui-word-aux">例如：http://www.seacms.net/logo.png</div>
					</div>
					<div class="layui-form-item" style="display:none;">
						<label class="layui-form-label"></label>
						<div class="layui-input-inline">
							<input type="text" name="yzm[trytime]" value="<?php echo $yzm['trytime'] ?>" size="30" class="layui-input upload-input" placeholder="">
						</div>
						<div class="layui-form-mid layui-word-aux"></div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">加载等待时间</label>
						<div class="layui-input-inline">
							<input type="text" name="yzm[waittime]" value="<?php echo $yzm['waittime'] ?>" size="30" class="layui-input upload-input" placeholder="单位/秒">
						</div>
						<div class="layui-form-mid layui-word-aux">加载画面等待时间</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">弹幕发送间隔</label>
						<div class="layui-input-inline">
							<input type="text" name="yzm[sendtime]" value="<?php echo $yzm['sendtime'] ?>" size="30" class="layui-input upload-input" placeholder="单位/秒">
						</div>
						<div class="layui-form-mid layui-word-aux">防止恶意发布弹幕</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">弹幕礼仪链接</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[dmrule]" value="<?php echo $yzm['dmrule'] ?>" size="30" class="layui-input upload-input" placeholder="链接地址">
						</div>
						<div class="layui-form-mid layui-word-aux">弹幕框右边弹幕礼仪按钮的链接地址</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">禁用关键词</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[pbgjz]" value="<?php echo $yzm['pbgjz'] ?>" size="30" class="layui-input upload-input" placeholder="输入关键字以" ,"隔开">
						</div>
						<div class="layui-form-mid layui-word-aux">输入关键字以","隔开</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">禁发布用户</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[jzuser]" value="<?php echo $yzm['jzuser'] ?>" size="30" class="layui-input upload-input" placeholder="输入用户名以" ,"隔开">
						</div>
						<div class="layui-form-mid layui-word-aux">输入关键字以","隔开</div>
					</div>
					<div class="layui-form-item">
						<div>
							<input name="edit" type="hidden" value="1" />
							<button type="button" onclick="text()">保 存</button>
						</div>
					</div>
				</div>
				<div class="layui-tab-item" name="广告设置">
					<div class="layui-form-item">
						<label class="layui-form-label">广告开关</label>
						<div class="layui-input-block">
							<input type="checkbox" name="yzm[ads][state]" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF" <?php $t = $yzm['ads']['state'];if ($t == "on") {echo "checked";} ?>>
							<div class="layui-unselect layui-form-switch" lay-skin="_switch"><em>Off</em><i></i></div>
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">广告类型</label>
						<div class="layui-input-inline">
							<input type="radio" name="yzm[ads][set][state]" value="1" title="视频" <?php $t = $yzm['ads']['set']['state'];
																									if ($t == "1") {
																										echo "checked";
																									} ?>>
							<input type="radio" name="yzm[ads][set][state]" value="2" title="图片" <?php $t = $yzm['ads']['set']['state'];
																									if ($t == "2") {
																										echo "checked";
																									} ?>>
						</div>
					</div>

					<div class="layui-form-item">
						<label class="layui-form-label">图片广告时间</label>
						<div class="layui-input-inline">
							<input type="text" name="yzm[ads][set][pic][time]" value="<?php echo $yzm['ads']['set']['pic']['time'] ?>" size="30" class="layui-input upload-input" placeholder="单位/秒">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">图片广告内容</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[ads][set][pic][img]" value="<?php echo $yzm['ads']['set']['pic']['img'] ?>" size="30" class="layui-input upload-input" placeholder="图片地址">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">图片广告链接</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[ads][set][pic][link]" value="<?php echo $yzm['ads']['set']['pic']['link'] ?>" size="30" class="layui-input upload-input" placeholder="点击链接地址">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">视频广告内容</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[ads][set][vod][url]" value="<?php echo $yzm['ads']['set']['vod']['url'] ?>" size="30" class="layui-input upload-input" placeholder="视频地址">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">视频广告链接</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[ads][set][vod][link]" value="<?php echo $yzm['ads']['set']['vod']['link'] ?>" size="30" class="layui-input upload-input" placeholder="点击链接地址">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">暂停广告开关</label>
						<div class="layui-input-block">
							<input type="checkbox" name="yzm[ads][pause][state]" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF" <?php $t = $yzm['ads']['pause']['state'];
																																				if ($t == "on") {
																																					echo "checked";
																																				} ?>>
							<div class="layui-unselect layui-form-switch" lay-skin="_switch"><em>Off</em><i></i></div>
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">暂停图片</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[ads][pause][pic]" value="<?php echo $yzm['ads']['pause']['pic'] ?>" size="30" class="layui-input upload-input" placeholder="图片地址">
						</div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">暂停图片链接</label>
						<div class="layui-input-inline long">
							<input type="text" name="yzm[ads][pause][link]" value="<?php echo $yzm['ads']['pause']['link'] ?>" size="30" class="layui-input upload-input" placeholder="点击链接地址">
						</div>
					</div>
					<div class="layui-form-item center">
						<div>
							<input name="edit" type="hidden" value="1" />
							<button  type="button" onclick="text()">保 存</button>
							
						</div>
					</div>
				</div>

					

						
							<div class="layui-tab-item" name="弹幕列表">
								<div class="chu" style="margin-top:2px">
									<div class="demoTable layui-form-item">
										<div class="layui-inline">
											<label class="layui-form-label">搜索</label>
											<div class="layui-input-inline">
												<input class="layui-input" id="textdemo" placeholder="请输入视频ID或弹幕关键字">
											</div>
											<button  style="background-color:#ccc;color:#000;height:28px; margin-top:1px;padding-left:10px;padding-right:10px;" lay-submit="search_submits" type="button" lay-filter="reloadlst_submit">搜索</button>
										</div>
									</div>
								</div>
								<table class="layui-hide" id="dmlist" lay-filter="dmlist">
								</table>
							</div>

							<div class="layui-tab-item" name="举报列表">
								<table class="layui-hide" id="dmreport" lay-filter="report">
								</table>
							</div>

						
					
				
			</div>
		</div>
		</div>
	</form>
	<script type="text/html" id="listbar">
		<a class="layui-btn layui-btn-xs" lay-event="dmedit">编辑</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
	</script>
	<script type="text/html" id="reportbar">
		<a class="layui-btn layui-btn-xs" lay-event="edit">误报</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
	</script>

	<script type="text/template" id="bondTemplateList">
		<div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" lay-filter="component-form-group" id="submits" onsubmit="return false">
            <div class="layui-row layui-col-space10 layui-form-item">
                <input type="hidden" name="cid" value="{{ d[4] }}">
                <div class="layui-col-lg5">
                    <label class="layui-form-label">弹幕ID：</label>
                    <div class="layui-input-block">
                        <input type="text" name="id" placeholder="请输入名称" autocomplete="off"
                               lay-verify="required" class="layui-input"
                               value="{{ d[0]?d[0]:'' }}" {{# if(d[0]){ }}disabled{{# } }}>
                    </div>
                </div>
                <div class="layui-col-lg5">
                    <label class="layui-form-label">颜色：</label>
  						<div class="layui-input-block">
							<div class="layui-input-inline" style="width: 120px;">
								<input type="text" name="color" placeholder="请选择颜色" class="layui-input" id="test-form-input" value="{{ d[3]?d[3]:'' }}">
							</div>
						<div class="layui-inline" style="left: -11px;">
						<div id="test-form"></div>
					</div>
				</div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">弹幕内容</label>
                    <div class="layui-input-block">
                    <textarea name="text" placeholder="请输入内容" class="layui-textarea"
                              lay-verify="required">
                        {{ d[5] ? d[5]:'' }}
                    </textarea>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="bond_sumbit">提交</button>
                </div>
            </div>
        </form>
    </div>
</script>

<div class="bottom"><table width="100%" cellspacing="5"><tr><td align="center"><font style="color:#666;">本页面用时0.0123秒,共执行3次数据查询</font></td></tr><tr><td align="center"><a target="_blank" href="//www.seacms.net/"><font style="font-size:10px;color: #3F628C;line-height: 28px;">POWER BY SEACMS</font></a></td></tr></table></div>
</body>

</html>