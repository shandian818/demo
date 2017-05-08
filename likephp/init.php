<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 10:27
 */

namespace likephp;

define('LIKE_VER', '0.0.1');
define('DS', DIRECTORY_SEPARATOR);
defined('APP_PATH') or define('APP_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . DS);
define("ROOT_PATH", realpath(dirname(APP_PATH)) . DS);
defined('LIKE_PATH') or define("LIKE_PATH", realpath(__DIR__) . DS);
defined('RUNTIME_PATH') or define("RUNTIME_PATH", realpath(ROOT_PATH . 'runtime') . DS);
require_once LIKE_PATH.'vendor/autoload.php';
require_once 'func.php';
require_once LIKE_PATH . '/core/App.php';
