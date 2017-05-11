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
define("APPS_PATH", __DIR__ . "/../apps/");
define("BIND_APP", 'index');
//define("BIND_CTRL", 'index');
//define("BIND_ACT", 'index');
//载入框架核心
require_once __DIR__ . "/../likephp/boot.php";