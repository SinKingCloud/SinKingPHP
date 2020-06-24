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
     * 获取设置
     * @param String $key 下标
     * @return mixed 数据集
     */
    public static function get($key = "")
    {
        if (empty(self::$configs)) {
            self::$configs = require(__DIR__ . "//../Config/Config.php");
        }
        return $key == "" ? self::$configs : self::$configs[$key];
    }
    /**
     * 更新设置
     * @param String $value 值
     * @return mixed
     */
    public static function set($value)
    {
        if (is_array($value)) {
            self::$configs = $value;
        }
    }
}
