<?php
/*
 * Title:沉沦云快捷开发框架
 * Project:请求功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/
namespace Systems;
class Request
{
	/**
	 * 判断是否POST请求
	 */
	public static function isPost(){
		return ($_SERVER['REQUEST_METHOD'] == 'POST' &&(empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? true : false;
	}
	/**
	 * 判断是否GET请求
	 */
	public static function isGet(){
		return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
	}
	/**
	 * 判断是否AJAX请求
	 */
	public static function isAjax(){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 获取GET POST表单数据
	 */
	public static function GetData($key=""){
		if(empty($key)){
			return $_REQUEST;
		}
		if (isset($_REQUEST[$key])) {
			return $_REQUEST[$key];
		}else{
			return null;
		}
	}
	/**
	 * 获取POST数据
	 */
	public static function GetPostData($key=""){
		if(empty($key)){
			return $_POST;
		}
		if(isset($_POST[$key])){
			return $_POST[$key];
		}else{
			return null;
		}
	}
	/**
	 * 获取GET数据
	 */
	public static function GetGetData($key = ""){
		if(empty($key)){
			return $_GET;
		}
		if(isset($_GET[$key])){
			return $_GET[$key];
		}else{
			return null;
		}
	}
	/**
	 * 获取请求头
	 */
	public static function GetHeaders($key = null){
		$headers = array();
		foreach ($_SERVER as $key => $value) {
			if ('HTTP_' == substr($key, 0, 5)) {
				$headers[str_replace('_', '-', substr($key, 5))] = $value;
			}
			if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
				$header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
			} elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
				$header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
			}
			if (isset($_SERVER['CONTENT_LENGTH'])) {
				$header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH'];
			}
			if (isset($_SERVER['CONTENT_TYPE'])) {
				$header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE'];
			}
		}
		if(isset($key)){
			return $headers[$key];
		}else {
			return $headers;
		}
	}
	
}