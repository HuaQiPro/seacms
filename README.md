===更新日志===

------------------------------------- 

# 更新日志
① 新增PHP木马扫描功能
② 百度推送模块增加批量推送视频或文章
③ 修复文章子目录循环输出无效的问题
④ 修复后台文章自定义采集关键词无法入库问题
⑤ 修复后台文章自定义采集无法分页问题
⑥ 修复后台文章自定义采集文字只第一页生效的问题
⑦新增多个标签：
 新增{channelpage:upid} 视频列表页上级分类id标签
 新增{newspagelist:upid} 文章列表页上级分类id标签
 新增{newspagelist:typeid} 文章列表页分类id标签
 新增[newspagelist:keyword]文章列表页关键词标签
 新增[newslist:keyword]文章单层循环关键词标签



# 升级方法
【第一步】后台 - 工具 - SQL高级助手，执行：
ALTER TABLE `sea_data` ADD `v_push` INT( 1 ) NOT NULL DEFAULT '0'
【第二步】后台 - 工具 - SQL高级助手，执行：
ALTER TABLE `sea_news` ADD `n_push` INT( 1 ) NOT NULL DEFAULT '0'
【第三步】修改admin为你的实际后台目录名称，覆盖上传升级文件
【第四步】更新缓存

# 重要提示
① 如第一步及第二部执行失败，尝试使用PhpMyadmin执行
② 本升级包仅支持v10.2版本升级到v10.3，其它版本请勿使用！




目录介绍
01. │─admin //后台管理目录
02. │ │─coplugins //已停用目录
03. │ │─ebak //帝国备份王数据备份
04. │ │─editor //编辑器
05. │ │─img //后台静态文件
06. │ │─js //后台js文件
07. │ │─templets //后台模板文件
08. │─article //文章内容页
09. │─articlelist //文章列表页
10. │─comment //评论
11. │ │─api //评论接口文件
12. │ │─images //评论静态文件
13. │ │─js //评论js文件
14. │─data //配置数据及缓存文件
15. │ │─admin //后台配置保存
16. │ │─cache //缓存
17. │ │─mark //水印
18. │ │─sessions //sessions文件
19. │─detail //视频内容页
20. │─include //核心文件
21. │ │─crons //定时任务配置
22. │ │─data //静态文件
23. │ │─inc //扩展文件
24. │ │─webscan //360安全监测模块
25. │─install //安装模块
26. │ │─images //安装模块静态文件
27. │ │─templates //安装模块模板
28. │─js //js文件
29. │ │─ads //默认广告目录
30. │ │─player //播放器目录
31. │─list //视频列表页
32. │─news //文章首页
33. │─pic //静态文件
34. │ │─faces //表情图像
35. │ │─member //会员模块界面
36. │ │─slide //旧版Flash幻灯片
37. │ │─zt //专题静态文件
38. │─templets //模板目录
39. │─topic //专题内容页
40. │─topiclist //专题列表页
41. │─uploads //上传文件目录
42. │─video //视频播放页
43. │─weixin //微信接口目录
44. └─index.php //首页文件