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
			'tpl_path' => $view_config['tpl_path'],
			'tpl_suffix' => $view_config['tpl_suffix'],
			'compile_suffix' => $view_config['compile_suffix'],
			'compile_path' => RUNTIME_PATH . 'cache/',

			'cache_switch' => true,//是否开启静态缓存
			'cache_path' => RUNTIME_PATH . 'html/',//静态html缓存文件目录
			'cache_time' => 3600,//静态html缓存时效（单位秒）

			'var_left' => '{{',//模板变量左标记
			'var_right' => '}}',//模板变量右标记
			'tag_left' => '<',//模板标签左标记
			'tag_right' => '>',//模板标签右标记
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