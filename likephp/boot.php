<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/26
 * Time: 11:57
 */

namespace likephp;

use likephp\core\Config;

header("Content-type: text/html; charset=utf-8");
require_once 'init.php';
require_once LIKE_PATH . 'vendor/autoload.php';
\likephp\core\App::run();
