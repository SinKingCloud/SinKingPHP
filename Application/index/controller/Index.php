<?php
/*
* Title:沉沦云MVC开发框架
* Project:首页控制器
* Author:流逝中沉沦
* QQ：1178710004
*/

namespace app\Index\Controller;
use Systems\Console;
use app\Index\Model\User;
class Index extends Console
{
    public function index(){
		$this->assign("userinfo",User::info("朋友"));
		return $this->fetch();
	}
}
