<?php
/*
 * Title:沉沦云快捷开发框架
 * Project:Session功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/
namespace Systems;

class Session
{
	/*
	 * Session Start
	 */
	 private static function start(){
		if (session_status() !==  PHP_SESSION_ACTIVE) {
			session_start();
		}
	 }
    /** 
     * 设置session 
     * @param String $name   session name 
     * @param Mixed  $data   session data 
     * @param Int    $expire 超时时间(秒) 
     */
    public static function set($name, $data, $expire = 6000)
    {
		self::start();
        $session_data = array();
        $session_data['data'] = $data;
        $session_data['expire'] = time() + $expire;
        $_SESSION[$name] = $session_data;
    }
    /** 
     * 读取session 
     * @param  String $name  session name 
     * @return Mixed 
     */
    public static function get($name)
    {
        self::start();
        if (isset($_SESSION[$name])) {
            if ($_SESSION[$name]['expire'] > time()) {
                return $_SESSION[$name]['data'];
            } else {
                self::clear($name);
                return false;
            }
        }
        return false;
    }
    /** 
     * 清除session 
     * @param  String  $name  session name 
     */
    public static function clear($name = null)
    {
        self::start();
        if (empty($name)) {
            foreach($_SESSION as $key => $value){
                $_SESSION[$key]=null;
                unset($_SESSION[$key]);
            }
        }else {
            $_SESSION[$name]=null;
            unset($_SESSION[$name]);
        }
		
    }
}