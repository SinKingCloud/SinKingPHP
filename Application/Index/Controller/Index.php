<?php
/*
* Title:沉沦云MVC开发框架
* Project:首页控制器
* Author:流逝中沉沦
* QQ：1178710004
*/

namespace app\Index\Controller;
use Systems\Console;
class Index extends Console
{
	public function index()
	{
		$this->assign("userinfo",array('name'=>'朋友','role'=>'开发者'));
		return $this->fetch();
	}
}
