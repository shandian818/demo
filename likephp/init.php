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

date_default_timezone_set('Asia/Shanghai');
define('LIKE_VER', '0.0.1');
define('DS', DIRECTORY_SEPARATOR);
defined('APP_DEBUG') or define("APP_DEBUG", false);//默认调试模式关闭
defined('APPS_PATH') or define('APPS_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . DS);
define("ROOT_PATH", realpath(dirname(APPS_PATH)) . DS);
defined('LIKE_PATH') or define("LIKE_PATH", realpath(__DIR__) . DS);
defined('RUNTIME_PATH') or define("RUNTIME_PATH", realpath(ROOT_PATH . 'runtime') . DS);
require_once LIKE_PATH . '/core/App.php';
