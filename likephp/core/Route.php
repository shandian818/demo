<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 10:12
 */

namespace likephp\core;


class Route
{

	public $module;
	public $controller;
	public $action;

	function __construct()
	{
		$var_pathinfo = Config::get('var_pathinfo');
		if (isset($_GET[$var_pathinfo])) {
			$_SERVER['PATH_INFO'] = $_GET[$var_pathinfo];
		}
		if (!isset($_SERVER['PATH_INFO'])) {
			foreach (Config::get('pathinfo_fetch') as $type) {
				if (!empty($_SERVER[$type])) {
					$_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ?
						substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
					break;
				}
			}
		}
		if (!empty($_SERVER['PATH_INFO'])) {
			$pathinfo_array = explode('/', trim($_SERVER['PATH_INFO'], '/'));
		}
		$module = isset($pathinfo_array[0]) ? $pathinfo_array[0] : Config::get('default_module');
		$controller = isset($pathinfo_array[1]) ? $pathinfo_array[1] : Config::get('default_controller');
		$action = isset($pathinfo_array[2]) ? $pathinfo_array[2] : Config::get('default_action');
		$this->module = strtolower($module);
		$this->controller = ucfirst(strtolower($controller));
		$this->action = $action;
	}
}


