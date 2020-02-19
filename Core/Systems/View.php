<?php
namespace Systems;
use Systems\Errors;
class View
{
    public $array;
    public $key;
    public $val;
	/*
	* @param 变量渲染
	* @key:变量名
	* @value:变量值
	*/
    public function assign($key, $val)
    {
        if (array($val)) {
            $this->array["{$key}"] = $val;
        } else {
            $this->array["{$key}"] = compact($val);
        }
    }
	/*
	* @param 模板渲染
	* @param $page:模板文件
	*/
    public function fetch($page = null)
    {
		if (file_exists($page)) {
			$this->assign($this->key, $this->val);
			extract($this->array);
            return include_once $page;
        }
        if (empty($page)) {
			$page = explode("/", $this->module ."/".$this->controller ."/".$this->action);
			$page = end($page);
		}
		if (strpos($page, '.html')) {
			$return = $page;
		} else {
			$return = $page . ".html";
		}
		$return = strtolower($return);
		$this->assign($this->key, $this->val);
        extract($this->array);
		if(substr($return,0,1)=='/'){
			$return = $this->app_path .ucwords(strtolower($this->module)) .'/'. $this->config['default_view_name']. '/' . $return;
		}else{
			$return = $this->app_path .ucwords(strtolower($this->module)) .'/'. $this->config['default_view_name']. '/' . strtolower($this->controller)  . '/' . $return;
		}
        if (file_exists($return)) {
            return include_once $return;
        }else{
			Errors::show("模板文件不存在</br>".$return);
		}
    }
}
