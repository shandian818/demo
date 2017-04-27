<?php
/**
 * 项目入口文件
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/26
 * Time: 11:32
 */

//定义应用目录
define("APP_PATH", __DIR__ . "/../apps/");
//载入框架核心
require_once __DIR__ . "/../likephp/start.php";

//
$route = new \likephp\core\Route();
$route->test();

$a = [
	["a" => "大叔都", "b" => "a安杀毒"],
	["a" => "大叔as都", "b" => "a安杀毒"],
	["a" => "大ds叔都", "b" => "a安ds杀毒"],
];
dump($a);

clog($a);


clog('Hello console!');