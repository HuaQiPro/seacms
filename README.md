# 更新内容

新增：采集时标题替换

新增：后台影片购买记录

新增：后台会员组购买记录

新增：前台充值记录记录

新增：前台会员组购买记录

优化：发送用户消息逻辑

优化：部分界面

修复：后台充值记录只能显示1条记录

修复：当前播放和vip集数css冲突的问题

修复：默认模板播放器侧栏收缩功能

# 升级步骤

【第一步】进入后台 - 工具 - SQL高级助手，执行如下SQL语句：
```mysql

ALTER TABLE `sea_buy` ADD `vpaypoints` INT(10) NOT NULL DEFAULT '0' AFTER `vfrom`;

DROP TABLE IF EXISTS `sea_hyzbuy`;

CREATE TABLE `sea_hyzbuy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL,
  `gid` int(6) NOT NULL,
  `paypoints` int(10) NOT NULL,
  `mon` int(6) NOT NULL,
  `paytime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

【第二步】修改admin为你的实际后台目录名称，覆盖上传升级文件

【第三步】更新缓存

# 重要提示

① 先修改admin为你的实际后台目录名称，再覆盖上传升级文件

② 如果使用的不是官方默认模板，请勿替换模板目录templets

③ 本升级包仅支持v12.5版本升级到v12.6，其它版本请勿使用！



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