<?php
/*
* Title:沉沦云MVC开发框架
* Project:回调模型
* Author:流逝中沉沦
* QQ：1178710004
*/
namespace app\Index\Model;

class User
{
    /**
     * @param Title 测试模型
     * @param String $name 用户名称
     */
    public static function Info($name){
        return array(
            'name'=>$name,
            'role'=>'开发者'
        );
    }
}
