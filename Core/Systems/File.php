<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:File操作类
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

use Systems\Dir;

class File
{
    private static $FilePath = false;
    private function __construct($path)
    {
        try {
            if (file_exists($path)) {
                self::$FilePath = $path;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    /**初始化
     * @param $file
     * @return object
     */
    public static function init($file)
    {
        $obj = new self($file);
        return $obj;
    }
    /**检查文件是否存在
     * @return boolean
     */
    public static function exists($file)
    {
        if (file_exists($file)) {
            return true;
        } else {
            return false;
        }
    }
    /**创建文件
     * @param $file 文件
     * @param $content 文件内容
     * @param $reset 已存在是否替换
     * @return boolean
     */
    public static function CreateFile($dir,$content = false, $reset = true)
    {
        if (!$reset && self::exists($dir)) {
            return false;
        }
        $dir = strtr($dir, ['\\' => '/']);
        $file = explode('/', $dir);
        $dir2 = str_replace('/' . end($file), '', $dir);
        if (!Dir::createFile($dir2)) {
            return false;
        } else {
            if (fopen($dir, "w")) {
                if (!$content) {
                    return true;
                }else {
                    if (self::init($dir)->write($content)) {
                        return true;
                    }else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
    }
    /**删除文件
     * @return boolean
     */
    public function delete()
    {
        if (!self::$FilePath || !unlink(self::$FilePath)) {
            return false;
        } else {
            return true;
        }
    }
    /**读取文件内容
     * @return string
     */
    public function read()
    {
        if (!self::$FilePath) {
            return false;
        }
        $fp = fopen(self::$FilePath, "r");
        $str = fread($fp, filesize(self::$FilePath));
        fclose($fp);
        return $str;
    }
    /**写入文件内容
     * @param $content
     * @return boolean
     */
    public function write($content = "")
    {
        if (!self::$FilePath) {
            return false;
        }
        $fp = fopen(self::$FilePath, "a+");
        $res = fwrite($fp, $content);
        fclose($fp);
        return $res > 0;
    }
    /**覆盖文件内容
     * @param $content
     * @return boolean
     */
    public function overwrite($content = "")
    {
        if (!self::$FilePath) {
            return false;
        }
        $fp = fopen(self::$FilePath, "w");
        $res = fwrite($fp, $content);
        fclose($fp);
        return $res > 0;
    }
}
