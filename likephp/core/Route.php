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

	public $app;
	public $ctrl;
	public $act;

	private $_pathinfo;

	function __construct()
	{
		$var_pathinfo = Config::get('var_pathinfo');
		if (isset($_GET[$var_pathinfo])) {
			$_SERVER['PATH_INFO'] = $_GET[$var_pathinfo];
			unset($_GET[$var_pathinfo]);
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
		$this->_pathinfo();
		$match_route = false;//是否匹配了指定路由
		if ($match_route) {
			//匹配了规则路由

		} else {
			//默认路由
			$this->app = $this->_get_app();
			$this->ctrl = $this->_get_ctrl();
			$this->act = $this->_get_act();
		}
		$this->_setGet();
	}

	private function _pathinfo()
	{
		if (!empty($_SERVER['PATH_INFO'])) {
			$trim_path = trim($_SERVER['PATH_INFO']);
			if (strpos($trim_path, '.')) {
				$pathinfo_all = explode('.', $trim_path);
				$pathinfo = $pathinfo_all[0];
			} else {
				$pathinfo = $trim_path;
			}
			$this->_pathinfo = explode('/', trim($pathinfo, '/'));
		}
	}

	/**
	 * 获取应用
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return string
	 */
	private function _get_app()
	{
		$default_app = Config::get('default_app', 'index');
		if (defined('BIND_APP')) {
			$app = BIND_APP;
		} else if (isset($this->_pathinfo[0])) {
			$app = $this->_pathinfo[0];
			unset($this->_pathinfo[0]);
		} else {
			$app = $default_app;
		}
		return strtolower($app);
	}

	/**
	 * 获取控制器
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return string
	 */
	private function _get_ctrl()
	{
		$default_ctrl = Config::get('default_ctrl', 'index');
		if (defined('BIND_CTRL')) {
			$ctrl = BIND_CTRL;
		} else if (isset($this->_pathinfo[0]) && defined('BIND_APP')) {
			$ctrl = $this->_pathinfo[0];
			unset($this->_pathinfo[0]);
		} else if (isset($this->_pathinfo[1])) {
			$ctrl = $this->_pathinfo[1];
			unset($this->_pathinfo[1]);
		} else {
			$ctrl = $default_ctrl;
		}
		return ucfirst(strtolower($ctrl));
	}

	/**
	 * 获取操作
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return bool|null|string
	 */
	private function _get_act()
	{
		$default_act = Config::get('default_act', 'index');
		if (defined('BIND_ACT')) {
			$act = BIND_ACT;
		} else if (isset($this->_pathinfo[0]) && defined('BIND_APP') && defined('BIND_CTRL')) {
			$act = $this->_pathinfo[0];
			unset($this->_pathinfo[0]);
		} else if (isset($this->_pathinfo[1]) && defined('BIND_APP')) {
			$act = $this->_pathinfo[1];
			unset($this->_pathinfo[1]);
		} else if (isset($this->_pathinfo[2])) {
			$act = $this->_pathinfo[2];
			unset($this->_pathinfo[2]);
		} else {
			$act = $default_act;
		}
		return $act;
	}

	/**
	 * 设置get参数
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 */
	private function _setGet()
	{
		if (!empty($this->_pathinfo)) {
			if (isset($_GET[$_SERVER['QUERY_STRING']])) {
				unset($_GET[$_SERVER['QUERY_STRING']]);
			}
			$params_array = array_values($this->_pathinfo);
			$count = count($params_array);
			$i = 0;
			while ($i < $count) {
				if (isset($params_array[$i + 1])) {
					$_GET[$params_array[$i]] = $params_array[$i + 1];
				}
				$i += 2;
			}
		}
	}
}


