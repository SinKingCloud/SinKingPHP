<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:信息输出
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;
use Systems\Config;

class Errors
{
    private static $configs = null;
    public static function show($message, $url=null,$time = 3)
    {
        header("Content-Type:text/html");
        if (!empty($url)) {
            header("Refresh:".$time.";url=".$url);
        }
        if (empty(self::$configs)) {
            self::$configs = Config::get();
        }
        self::shows($message);
    }
    private static function shows($error)
    {
        if (!self::$configs['default_debug']) {
            $error = '<h2>框架运行错误</h2>';
        }
        if (file_exists(__DIR__ . "//../Error/Error.html")) {
            exit(require(__DIR__ . "//../Error/Error.html"));
        }
    }
}
