<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:基础功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/
namespace Systems;
use Systems\View;
use Systems\Route;
class Console extends View
{
	public function __construct()
    {
        $this->config = require (__DIR__ . "//../Config/Config.php");
        $this->module = Route::GetModule();
        $this->controller = Route::GetController();
        $this->action = Route::GetAction();
		$this->app_path = defined('APP_PATH') ? APP_PATH : $this->config['application_dir'];
    }
	/*
	* @param Json信息输出
	* @arr:需要转换的数组
	*/
	public function json($arr = array(),$code = JSON_UNESCAPED_UNICODE){
		header("Content-Type:text/json");
		return json_encode($arr,$code);
	}
}