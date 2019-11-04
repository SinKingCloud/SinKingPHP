<?php
/*
* Title:沉沦云MVC开发框架
* Project:框架配置
* Author:流逝中沉沦
* QQ：1178710004
*/
return [

	/*------控制器配置------*/
	'controller' => [
		'default_module' => 'index', //默认模块名称
		'default_controller' => 'Index', //默认控制器
		'default_action' => 'index' //默认方法名
	],
	'application_dir' => __DIR__ . '/../../Application/', //应用目录
	'default_namespace' => 'app', //默认命名空间
	'default_controller_name' => 'controller', //默认c层目录名称
	'default_view_name' => 'view', //默认v层目录名称

	/*------DEBUG配置------*/
	'default_debug' => true, //开启DEBUG模式

	/*------加载文件配置------*/
	'default_loadfile' => [
		'common.php'
	], //自动加载文件,以应用目录为基准(不需要则不填写)

	/*------数据库配置------*/
	'database' => [
		'database_host' => '127.0.0.1', //数据库地址
		'database_port' => '3306', //端口
		'database_user' => 'root', //数据库账号
		'database_pwd' => 'root', //数据库密码
		'database_name' => 'oauth', //数据库名称
		'database_prefix' => 'cly_' //数据库前缀
	],

	/*------路由配置------*/
	'default_route' => [
		'open' => false, // 路由功能：开启/关闭
		'force' => false, //强制路由： 开启/关闭(开启后未定义路由的url不可访问)
		'route' => [
			
		]
	]
];
