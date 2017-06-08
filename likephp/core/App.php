<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/26
 * Time: 15:19
 */

namespace likephp\core;


class App
{
	static public function run()
	{
		require_once 'Loader.php';
		$loader = new Loader();
		$loader->register();
		$loader->addNamespace('likephp', LIKE_PATH);//载入核心命名空间
		require_once LIKE_PATH . 'func.php';//载入系统公共函数
		$default_config = require_once LIKE_PATH . 'conf.php';
		Config::set($default_config);//载入系统默认配置
		Error::register();//载入异常处理
		$apps_namespace = Config::get('sys.apps_namespace');//获取配置的app命名空间
		$loader->addNamespace($apps_namespace, APPS_PATH);//载入应用命名空间
		Route::init();
		$app = Request::getApp();
		$ctrl = Request::getCtrl();
		$action = Request::getAct();
		$class_name = "\\$apps_namespace\\{$app}\\ctrl\\{$ctrl}";
		try {
			$ref = new \ReflectionClass($class_name);
			if ($ref->hasMethod($action)) {
				$class = new $class_name;
				$class->$action();
			} else {
				echo('操作不存在:' . $class_name . '->' . $action);//待完善
			}
		} catch (\ReflectionException $e) {
			echo('类文件不存在:' . $class_name . '->' . $action);//待完善
		}
	}
}