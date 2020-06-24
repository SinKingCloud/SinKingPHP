<?php
/*
* Title:沉沦云MVC开发框架
* Project:框架入口
* Author:流逝中沉沦
* QQ：1178710004
*/

use Systems\Route;
use Systems\Errors;
use Systems\Cache;
use Systems\Config;
class App
{
    protected $config;
    protected $module;
    protected $controller;
    protected $action;
    protected $app_path;
    protected $value;
    /**
     * 运行
     * @return Object 框架核心
     */
    public static function start()
    {
        $app = new self();
        return $app->Init()->run();
    }
    /**
     * 构造参数
     */
    private function Init()
    {
        try {
            $this->autoload_register();
            $this->removeMagicQuotes();
            $this->config = Config::get();
            Route::Init($this->config);
            if ($this->config['DB_Cache']['open']) {
                Cache::init($this->config);
            }
            $this->module = Route::GetModule();
            $this->controller = Route::GetController();
            $this->action = Route::GetAction();
            $this->app_path = defined('APP_PATH') ? APP_PATH : $this->config['application_dir'];
            if (!defined('APP_PATH')) {
                define('APP_PATH', $this->app_path);
            }
            $this->value = Route::GetValue();
            return $this;
        } catch (\Throwable $th) {
            Errors::show($th);
        }
    }
    /**
     * 自动加载
     * @param String $class 类名
     */
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
    /**
     * 方法注册
     */
    private function autoload_register()
    {
        spl_autoload_register('self::autoload');
    }
    /**
     * 框架运行
     */
    public function run()
    {
        try {
            $this->LoadFile();
            $class = $this->config['default_namespace'] . '\\' . ucwords(strtolower($this->module)) . '\\' . $this->config['default_controller_name'] . '\\' . ucwords(strtolower($this->controller));
            $controller = new $class();
            if (method_exists($controller, $this->action)) {
                $return = call_user_func_array(array($controller, $this->action), $this->value);
                if (is_array($return)) {
                    header("Content-Type:text/json");
                    echo json_encode($return,JSON_UNESCAPED_UNICODE);
                }else if(is_string($return)){
                    echo $return;
                }
            } else {
                Errors::show("方法不存在</br>" . $this->action);
            }
        } catch (\Throwable $th) {
            Errors::show($th);
        }
    }
    /**
     * 加载文件
     */
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
    /**
     * 构造参数
     * @param String $value 字符串
     */
    private function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }
    /**
     * 删除敏感字符
     */
    private function removeMagicQuotes()
    {
        $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : '';
        $_POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : '';
        $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
        $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
    }
}
