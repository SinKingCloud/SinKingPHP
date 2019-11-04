<?php
/*
* Title:沉沦云MVC开发框架
* Project:框架入口
* Author:流逝中沉沦
* QQ：1178710004
*/

use Systems\Route;
use Systems\Errors;

class Run
{
    function __construct()
    {
        spl_autoload_register('self::autoload');
        $this->config = require_once("Config/Config.php");
        Errors::Init($this->config);
        Route::Init($this->config);
        $this->module = Route::GetModule();
        $this->controller = Route::GetController();
        $this->action = Route::GetAction();
        $this->app_path = defined('APP_PATH') ? APP_PATH : $this->config['application_dir'];
        $this->value = Route::GetValue();
    }
    //自动加载
    private function autoload($class)
    {
        $dir = str_replace('\\', '/', $class) . '.php';
        if (file_exists(__DIR__ . '/' . $dir)) {
            require_once $dir;
        } else {
            $dir = str_replace($this->config['default_namespace'] . '/', $this->app_path, $dir);
            if (file_exists($dir)) {
                require_once $dir;
            } else {
                Errors::show("控制器不存在</br>" . $dir);
            }
        }
        unset($dir);
    }
    //框架运行
    public function run()
    {

        try {
            $this->removeMagicQuotes();
            $this->LoadFile();
            $class = $this->config['default_namespace'] . '\\' . $this->module . '\\' . $this->config['default_controller_name'] . '\\' . $this->controller;
            $controller = new $class();
            if (method_exists($controller, $this->action)) {
                call_user_func_array(array($controller, $this->action), $this->value);
            } else {
                Errors::show("方法不存在</br>" . $this->action);
            }
        } catch (\Throwable $th) {
            Errors::show($th->getMessage());
        }
    }
    //加载文件
    private function LoadFile()
    {
        if (!empty($this->config['default_loadfile'])) {
            if (is_array($this->config['default_loadfile'])) {
                foreach ($this->config['default_loadfile'] as $key) {
                    if (file_exists($this->app_path . $key)) {
                        require_once($this->app_path . $key);
                    }
                }
            } else {
                if (file_exists($this->app_path . $this->config['default_loadfile'])) {
                    require_once($this->app_path . $this->config['default_loadfile']);
                }
            }
        }
    }
    // 删除敏感字符
    private function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }
    // 检测敏感字符并删除
    private function removeMagicQuotes()
    {
        $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : '';
        $_POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : '';
        $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
        $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
    }
}
$start = new Run();
$start->run();
