<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:Hook功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

class Hook
{
    private static $hooks = array(); //监听钩子列表
    /** 
     * @param Title 添加Hook
     * @param String $name 名称
     * @param String $path 安装目录
     */
    public static function add($name, $path)
    {
        $file = $path . '/' . $name . '.php';
        if (empty($name )|| !file_exists($file)) {
            return false;
        }
        require_once($file);
        $class = new $name();
        self::$hooks[$name] = array(
            'path' => $path,
            'class' => $class
        );
    }
    /** 
     * @param Title 触发Hook
     * @param String $name 名称
     * @param Array $values 参数
     */
    public static function trigger($name, $values = array())
    {
        if (isset(self::$hooks[$name])) {
            if (method_exists(self::$hooks[$name]['class'], 'Run')) {
                return call_user_func_array(array(self::$hooks[$name]['class'], 'Run'), $values);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /** 
     * @param Title 获取Hook信息
     * @param String $name 名称
     */
    public static function get($name)
    {
        if (isset(self::$hooks[$name])) {
            return self::$hooks[$name];
        }
    }
}
