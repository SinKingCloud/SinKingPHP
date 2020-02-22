<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:框架配置
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

class Config
{
    private static $configs = null; //框架设置
    /** 
     * @param Title 获取设置
     */
    public static function get()
    {
        if (empty(self::$configs)) {
            self::$configs = require(__DIR__ . "//../Config/Config.php");
        }
        return self::$configs;
    }
    /** 
     * @param Title 更新设置
     * @param Array 设置
     */
    public static function set($value)
    {
        if (is_array($value)) {
            self::$configs = $value;
        }
    }
}
