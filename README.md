> 由于SEACMS.NET被盗,随时可能会出现安全问题,特更换域名SEACMS.COM。

# 近期更新

增加webp图片格式的全面支持

增加同步图片时的图片大小剪裁功能，后台设置剪裁大小

增加资源库类型 可以自定义新增数据或者只更新数据

增加后台用户管理增加头像管理

增加上传头像自动压缩图片大小功能

修复专题图片无法上传的问题

修复上传图片是否添加水印尺寸控制无效的问题

修复模板if标签安全过滤导致的系统异常



# 升级步骤

【第①步】后台 » 工具 » SQL高级助手，执行如下sql语句：

ALTER TABLE sea_zyk ADD ztype VARCHAR(1) NOT NULL DEFAULT '1' AFTER zinfo;

如执行失败，尝试在phpmyadmin里执行。

【第②步】修改admin目录为你的实际后台目录，覆盖上传升级文件

【第③步】后台 » 系统 » 网站设置 » 性能效率设置，修改“图片尺寸压缩”宽高尺寸参数为0  0 ，或者根据实际需求设置尺寸

【第④步】更新缓存



# 重要提示

① 本升级包仅支持v11.7版本升级到v11.8，其它版本请勿使用！



[最新版 https://github.com/ciweiin/seacms_down](https://github.com/ciweiin/seacms_down)
