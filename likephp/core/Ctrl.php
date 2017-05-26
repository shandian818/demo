<?php
/**
 * 控制器基类
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/26
 * Time: 15:22
 */

namespace likephp\core;


class Ctrl
{
	private $_view;

	public function __construct()
	{
		$view_config = \likephp\core\Config::get('view');
		$app = \likephp\core\Route::getApp();
		$view_path = empty($view_config['path']) ? APPS_PATH . $app . '/view/' : $view_config['path'];
		$_options = [
			'debug' => APP_DEBUG,
			'path' => $view_path,
			'suffix' => $view_config['suffix'],
			'cache_suffix' => '.php',
			'cache_path' => RUNTIME_PATH . 'cache/',
			'directive_prefix' => 'like-',
		];
		$this->_view = new View($_options);
	}

	public function assign($name, $value)
	{
		$this->_view->assign($name, $value);
	}

	public function Render($tpl_name = null)
	{
		if (is_null($tpl_name)) {
			$ctrl = \likephp\core\Route::getCtrl();
			$action = \likephp\core\Route::getAct();
			$tpl_name = $ctrl . '/' . $action;
		}
		$content = $this->_view->make($tpl_name);
		echo $content;
	}

}