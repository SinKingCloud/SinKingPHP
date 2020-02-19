<?php
/*
* Title:沉沦云MVC开发框架
* Project:入口文件
* Author:流逝中沉沦
* QQ：1178710004
*/
//设置时区
date_default_timezone_set('Asia/Shanghai');
// 定义应用目录
define('APP_PATH', __DIR__ . '/Application/');
// 定义缓存目录
define('CACHE_PATH', __DIR__ . '/Cache/');
// 加载框架引导文件
require __DIR__ . '/Core/App.php';
App::start();