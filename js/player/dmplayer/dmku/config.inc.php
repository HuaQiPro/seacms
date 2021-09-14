<?php 
include_once('../../../../data/common.inc.php');
return [
    '后台密码' => '',
    'tips' => [
        'time' => '6',
        'color' => '#fb7299',
        'text' => '请大家遵守弹幕礼仪，文明发送弹幕',
    ],
    '防窥' => '0',
    '数据库' => [
        '类型' => 'mysql',
        '方式' => 'mysqli',
        '地址' => $cfg_dbhost,
        '用户名' => $cfg_dbuser,
        '密码' => $cfg_dbpwd,
        '名称' => $cfg_dbname,
		'端口' => $cfg_dbport,
    ],

    'is_cdn' => 0,  //是否用了cdn
    '限制时间' => 60, //单位s
    '限制次数' => 20, //在限制时间内可以发送多少条弹幕
    '允许url' => [],  //跨域  格式['https://abc.com','http://cba.com']   要加协议
    '安装' => 1
];
