<?php
/*
 * Title:沉沦云快捷开发框架
 * Project:Cookie功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/
namespace Systems;

class Cookie
{
    /*
     * 设置cookie
     */
    public static function set($name, $value, array $options = [])
    {
		if(empty($options['expire'])){
			$options['expire']=time()+7200;
		}
		if(empty($options['path'])){
			$options['path']='/';
		}
		if(empty($options['domain'])){
			$options['domain']=$_SERVER['HTTP_HOST'];
		}
		if(empty($options['secure'])){
			$options['secure']=false;
		}
		if(empty($options['httponly'])){
			$options['httponly']=true;
		}
		$res = setcookie($name, $value,$options['expire'],$options['path'], $options['domain'],$options['secure'],$options['httponly']);
		return $res;
    }
	/*
     * 读取cookie值
	 */
    public static function get($name)
    {
        $value = isset($_COOKIE[$name])?$_COOKIE[$name]:null;
        if (is_array($value)) {
            $arr = [];
            foreach ($value as $k => $v) {
                # code...
                $arr[$k] = substr($v, 0, 1) == '{' ? json_decode($value) : $v;
            }
            return $arr;
        } else {
            $cookie = substr($value, 0, 1) == '{' ? json_decode($value) : $value;
			if(empty($cookie)){
				return null;
			}else{
				return $cookie;
			}
        }
    }
	/*
	 *删除cookie
	 */
	public static function clear($name = null){
		if($name==null){
			foreach ($_COOKIE as $key => $value){
				setcookie($key, null,time()-3600);
			}
		}else if(is_array($name)){
			foreach ($name as $key){
				setcookie($key, null,time()-3600);
			}
		}
		if(!empty($name)){
			setcookie($name,null,time()-3600);
		}
	}
}
