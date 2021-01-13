===更新日志===

------------------------------------- 

# 更新内容
专题新增幻灯图片、背景图片、时间、seo关键词、seo描述、内容介绍等多个标签
专题新增相关文章功能
视频列表新增根据导演、演员获取数据,director=导演 actor=演员
优化专题生成为分批生成
优化新闻栏目生成为分批生成
新闻模块增加幻灯图片、背景图片
后台专题列表页面支持直接生成静态
图片缩略图功能增加开关选项
图片水印功能修改为只对视频和新闻海报有效
修改图片上传文件大小限制为5M


#新增标签
新增以下标签，请对照官网-技术文档-模板标签 使用
{seacms:topicspic}幻灯图片
{seacms:topicgpic}背景图片
{seacms:topicaddtime}添加时间
{seacms:topiccontent}专题介绍
{seacms:topicdes} 专题描述

[topicindexlist:spic]幻灯图片
[topicindexlist:gpic]背景图片
[topicindexlist:keyword]关键词
[topicindexlist:addtime]添加时间
[topicindexlist:content]专题介绍
[topicindexlist:des]专题描述

[topiclist:spic]幻灯图片
[topiclist:gpic]背景图片
[topiclist:addtime]添加时间
[topiclist:keyword]专题关键词
[topiclist:content]专题介绍
[topicindexlist:des]专题描述

[newssearchlist:spic]幻灯图片
[newssearchlist:gpic]背景图片

[newspagelist:spic]幻灯图片
[newspagelist:gpic]背景图片

[newslist:spic]幻灯图片
[newslist:gpic]背景图片

{news:spic}幻灯图片
{news:gpic}背景图片

{seacms:topictopicnewspagelist size=15}
[topicnewspagelist:typelink]分类链接
[topicnewspagelist:title文章标题
.......................................... 更多标签请对照官网-技术文档 -模板标签 使用
{/seacms:topictopicnewspagelist}


#升级方法
【第一步】后台 - 工具 - SQL高级助手，执行：

ALTER TABLE `sea_topic` CHANGE `pic` `pic` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `sea_topic` ADD `spic` CHAR(255) NOT NULL DEFAULT '' AFTER `pic`;
ALTER TABLE `sea_topic` ADD `gpic` CHAR(255) NOT NULL DEFAULT '' AFTER `spic`;
ALTER TABLE `sea_topic` ADD `news` TEXT NOT NULL AFTER `vod`;
ALTER TABLE `sea_topic` ADD `content` TEXT NOT NULL AFTER `keyword`;
ALTER TABLE `sea_news` ADD `n_spic` CHAR(255) NOT NULL AFTER `n_pic`;
ALTER TABLE `sea_news` ADD `n_gpic` CHAR(255) NOT NULL AFTER `n_spic`;

【第二步】修改admin为你的实际后台目录名称，覆盖上传升级文件
【第三步】更新缓存

# 重要提示
② 本升级包仅支持v10.4版本升级到v10.5，其它版本请勿使用！





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