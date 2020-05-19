<?php

/**
 * Title:SinKingCloud安全阻拦
 * Author：流逝中沉沦
 * Date: 2019/02/02 12:00
 */

namespace Systems;

class Safe
{
    public static $Statu = true;

    public static $Cookie = true;

    public static $Post = true;

    public static $Get = true;

    public static $HTML = "";

    public static $WhiteDIr = null; //后台白名单

    public static $WhiteUrl = array(); //网址白名单

    public static function start()
    {
        $safe = new self();
        return $safe->run();
    }
    public function run()
    {
        if (!self::$Statu) {
            return false;
        }
        if (!empty(self::$WhiteDIr) && !empty(self::$WhiteUrl)) {
            if (!$this->CheckWhite(self::$WhiteDIr, self::$WhiteUrl)) {
                return;
            }
        }
        if (self::$Get) {
            $this->GetFitter();
        }
        if (self::$Post) {
            $this->PostFitter();
        }
        if (self::$Cookie) {
            $this->CookieFitter();
        }
    }
    private function CheckWhite($webscan_white_name, $webscan_white_url = array())
    {
        $url_path = $_SERVER['SCRIPT_NAME'];
        $url_var = $_SERVER['QUERY_STRING'];
        if (preg_match("/" . $webscan_white_name . "/is", $url_path) == 1 && !empty($webscan_white_name)) {
            return false;
        }
        foreach ($webscan_white_url as $key => $value) {
            if (!empty($url_var) && !empty($value)) {
                if (stristr($url_path, $key) && stristr($url_var, $value)) {
                    return false;
                }
            } elseif (empty($url_var) && empty($value)) {
                if (stristr($url_path, $key)) {
                    return false;
                }
            }
        }
        return true;
    }
    private function ArrayForeach($arr)
    {
        static $str;
        static $keystr;
        if (!is_array($arr)) {
            return $arr;
        }
        foreach ($arr as $key => $val) {
            $keystr = $keystr . $key;
            if (is_array($val)) {

                $this->ArrayForeach($val);
            } else {

                $str[] = $val . $keystr;
            }
        }
        return implode($str);
    }
    private function Fitter($StrFiltKey, $StrFiltValue, $ArrFiltReq)
    {
        $StrFiltValue = $this->ArrayForeach($StrFiltValue);
        if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
            return $this->WebExit();
        }
        if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltKey) == 1) {
            return $this->WebExit();
        }
    }
    public function WebExit($html = "")
    {
        $html = empty($html) ? self::$HTML : $html;
        exit($html);
    }
    private function CookieFitter()
    {
        $cookiefilter = "benchmark\s*?\(.*\)|sleep\s*?\(.*\)|load_file\s*?\\(|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)@{0,2}(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|OR|TRUNCATE)\\s+(TABLE|DATABASE)";
        foreach ($_COOKIE as $key => $value) {
            $key = $this->daddslashes($key);
            $value = $this->daddslashes($value);
            $_COOKIE[$key] = $value;
            $this->Fitter($key, $value, $cookiefilter);
        }
    }
    private function GetFitter()
    {
        $getfilter = "\\<.+javascript:window\\[.{1}\\\\x|<.*=(&#\\d+?;?)+?>|<.*(data|src)=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[a-z]+?\\b[^>]*?\\bon([a-z]{4,})\s*?=|^\\+\\/v(8|9)|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)@{0,2}(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|OR|TRUNCATE)\\s+(TABLE|DATABASE)|<.*(iframe|frame|style|embed|object|frameset|meta|xml|a|img)|hacker";
        foreach ($_GET as $key => $value) {
            $key = $this->daddslashes($key);
            $value = $this->daddslashes($value);
            $_GET[$key] = $value;
            $this->Fitter($key, $value, $getfilter);
        }
    }
    private function PostFitter()
    {
        $postfilter = "<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|OR|TRUNCATE)\\s+(TABLE|DATABASE)|<.*(iframe|frame|style|embed|object|frameset|meta|xml|a|img)|hacker";
        foreach ($_POST as $key => $value) {
            $key = $this->daddslashes($key);
            $value = $this->daddslashes($value);
            $_POST[$key] = $value;
            $this->Fitter($key, $value, $postfilter);
        }
    }
    private function daddslashes($string, $force = 0, $strip = FALSE)
    {
        !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
        if (!MAGIC_QUOTES_GPC || $force) {
            if (is_array($string)) {
                foreach ($string as $key => $val) {
                    $string[$key] = daddslashes($val, $force, $strip);
                }
            } else {
                $string = addslashes($strip ? stripslashes($string) : $string);
            }
        }
        return $string;
    }
    public static function GetRequestInfo()
    {
        return array(
            'get' => $_GET,
            'post' => $_POST,
            'cookie' => $_COOKIE,
            'request' => $_SERVER
        );
    }
}
