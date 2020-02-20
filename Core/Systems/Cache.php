<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:缓存功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

use Systems\Errors;
use Systems\File;
use Systems\Dir;
use Systems\Session;

class Cache
{
    protected static $config = null;
    /**
     * 构造参数
     * @param array $conf 系统配置
     */
    public static function init($conf = null)
    {
        if ($conf == null) {
            self::$config = require(__DIR__ . "//../Config/Config.php");
        } else {
            self::$config = $conf;
        }
    }
    /**
     * 获取缓存
     * @param String $key 键名
     * @return Mixed 数据集
     */
    public static function get($key)
    {
        if (is_null(self::$config)) {
            self::init(); //初始化参数
        }
        if (is_null(self::$config['DB_Cache']['Cache_Type'])) {
            return false;
        }
        try {
            $res = false;
            switch (self::$config['DB_Cache']['Cache_Type']) {
                case 'file':
                    $res = self::FileGet($key);
                    break;
                case 'session':
                    $res = Session::get($key);
                    break;
            }
            if ($res) {
                $res = unserialize($res);
                if ($res['expiretime']>=time()) {
                    return $res['data'];
                }
                return false;
            }
            return false;
        } catch (\Throwable $th) {
            Errors::show($th);
        }
    }
    /**
     * 设置缓存内容
     * @param String $key 键名
     * @param Mixed $value 键值
     * @return Mixed 数据集
     */
    public static function set($key = null, $value = null)
    {
        if (is_null(self::$config)) {
            self::init();
        }
        if (is_null($key) || is_null($value)) {
            return false;
        }
        if (is_null(self::$config['DB_Cache']['Cache_Type'])) {
            return false;
        }
        $res = false;
        $value = serialize(
            array(
                'time'=>time(),
                'expiretime'=>time()+self::$config['DB_Cache']['Cache_Time'],
                'data'=>$value
            )
        );
        switch (self::$config['DB_Cache']['Cache_Type']) {
            case 'file':
                $res = self::FilePut($key, $value);
                break;
            case 'session':
                $res = Session::set($key,$value,self::$config['DB_Cache']['Cache_Time']);
                break;
        }
        if ($res) {
            return true;
        }
        return false;
    }
    /**
     * 清理缓存
     * @param String $key 键名
     * @return Mixed 数据集
     */
    public static function clear($key = null)
    {
        if (is_null(self::$config)) {
            self::init();
        }
        if (is_null(self::$config['DB_Cache']['Cache_Type'])) {
            return false;
        }
        switch (self::$config['DB_Cache']['Cache_Type']) {
            case 'file':
                return self::FileDelete($key);
                break;
        }
        return false;
    }
    /**
     * 获取缓存内容 file形式
     * @param String $key 键名
     * @return Mixed 数据集
     */
    private static function FileGet($key = null)
    {
        if (is_null($key)) {
            return false;
        }
        try {
            $path = self::$config['DB_Cache']['Cache_Path'];
            if (Dir::createFile($path)) {
                $file = $path . $key;
                if (File::exists($file)) {
                    return File::init($file)->read();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Errors::show($th);
        }
    }
    /**
     * 设置缓存内容 file形式
     * @param String $key 键名
     * @param Mixed $value 键值
     * @return Mixed 数据集
     */
    private static function FilePut($key = null, $value = null)
    {
        if (is_null($key) || is_null($value)) {
            return false;
        }
        try {
            $path = self::$config['DB_Cache']['Cache_Path'];
            if (Dir::createFile($path)) {
                $file = $path . $key;
                if (File::CreateFile($file, $value)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            Errors::show($th);
        }
    }
    /**
     * 清理缓存 file形式
     * @param String $key 键名
     * @return Mixed 数据集
     */
    private static function FileDelete($key = null)
    {
        try {
            $path = self::$config['DB_Cache']['Cache_Path'];
            if (is_null($key)) {
                return Dir::init($path)->removeAll();
            } else {
                $file = $path . $key;
                return File::init($file)->delete();
            }
        } catch (\Throwable $th) {
            Errors::show($th);
        }
    }
}
