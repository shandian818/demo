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
		$_options = [
			'debug' => APP_DEBUG,
			'path' => $view_config['path'],
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
		$content = $this->_view->make($tpl_name);
		echo $content;
	}

}