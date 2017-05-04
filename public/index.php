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
require_once __DIR__ . "/../likephp/boot.php";

//

$a = \likephp\core\Request::isGet();


dump($_SERVER);
dump($a);

clog($_SERVER);
clog($a);
$req = \likephp\core\Request::getInstance();
clog($req::domain(false,true));

$index = new \apps\index\ctrl\Index();
$index->index();