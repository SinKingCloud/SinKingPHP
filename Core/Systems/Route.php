<?php
/*
* Title:沉沦云MVC开发框架
* Project:路由配置
* Author:流逝中沉沦
* QQ：1178710004
*/

namespace Systems;
use Systems\Errors;
class Route
{
	private static  $Config;
	private static  $Module; //模块变量
	private static  $Controller; //控制器变量
	private static  $Action; //方法变量
	private static  $Value; //方法参数
	//初始化操作(获取完整url)
	public static function Init($config)
	{
		self::$Config = $config;
		if (self::$Config['default_route']['open']) {
			$url = self::GetRoutes(self::$Config['default_route']['route']);
		} else {
			$url = self::GetUrls();
		}
		$url = self::ReSet($url);
		self::$Module = explode(".", $url[0])[0];
		self::$Controller = explode(".", ucfirst($url[1]))[0];
		self::$Action = explode(".", $url[2])[0];
		self::$Value = array();
		foreach ($url as $key => $value) {
			if ($key >= 3) {
				self::$Value[] = $value;
			}
		}
	}
	private static function ReSet($url)
	{
		array_shift($url);
		if (empty($url[0])) {
			$url[0] = self::$Config["controller"]["default_module"];
		}
		if (empty($url[1])) {
			$url[1] = self::$Config["controller"]["default_controller"];
		}
		if (empty($url[2])) {
			$url[2] = self::$Config["controller"]["default_action"];
		}
		return $url;
	}
	private static function GetRoutes($routes = array())
	{
		$url = explode("?", $_SERVER['REQUEST_URI'])[0];
		if ($url == "/") {
			return explode('/', $url);
		}
		$url = explode('.', $url)[0];
		if (array_key_exists($url, $routes)) {
			$urls = $routes[$url];
			return explode('/', $urls);
		} else {
			if (self::$Config['default_route']['force']) {
				Errors::show("路由未定义");
			} else {
				return self::GetUrls();
			}
		}
	}
	private static function GetUrls()
	{
		return explode("/", explode("?", $_SERVER['REQUEST_URI'])[0]);
	}
	public static function GetModule()
	{
		return self::$Module;
	}
	public static function GetController()
	{
		return self::$Controller;
	}
	public static function GetAction()
	{
		return self::$Action;
	}
	public static function GetValue()
	{
		return self::$Value;
	}
}
