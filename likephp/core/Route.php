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
	static private $_pathinfo;

	static public function init()
	{
		$var_pathinfo = Config::get('pathinfo.var');
		if (isset($_GET[$var_pathinfo])) {
			$_SERVER['PATH_INFO'] = $_GET[$var_pathinfo];
			unset($_GET[$var_pathinfo]);
		}
		if (!isset($_SERVER['PATH_INFO'])) {
			foreach (Config::get('pathinfo.fetch') as $type) {
				if (!empty($_SERVER[$type])) {
					$_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ?
						substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
					break;
				}
			}
		}
		self::_pathinfo();
		$match_route = false;//是否匹配了指定路由
		if ($match_route) {
			//匹配了规则路由

		} else {
			//默认路由
			$_app = self::_get_app();
			$_ctrl = self::_get_ctrl();
			$_act = self::_get_act();

		}
		Request::getInstance($_app, $_ctrl, $_act);
		self::_setGet();
	}

	static private function _pathinfo()
	{
		if (!empty($_SERVER['PATH_INFO'])) {
			$trim_path = trim($_SERVER['PATH_INFO']);
			if (strpos($trim_path, '.')) {
				$pathinfo_all = explode('.', $trim_path);
				$pathinfo = $pathinfo_all[0];
			} else {
				$pathinfo = $trim_path;
			}
			self::$_pathinfo = explode('/', trim($pathinfo, '/'));
		}
	}

	/**
	 * 获取应用
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return string
	 */
	static private function _get_app()
	{
		$default_app = Config::get('sys.default_app', 'index');
		if (defined('BIND_APP')) {
			$app = BIND_APP;
		} else if (isset(self::$_pathinfo[0])) {
			$app = self::$_pathinfo[0];
			unset(self::$_pathinfo[0]);
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
	static private function _get_ctrl()
	{
		$default_ctrl = Config::get('sys.default_ctrl', 'index');
		if (defined('BIND_CTRL')) {
			$ctrl = BIND_CTRL;
		} else if (isset(self::$_pathinfo[0]) && defined('BIND_APP')) {
			$ctrl = self::$_pathinfo[0];
			unset(self::$_pathinfo[0]);
		} else if (isset(self::$_pathinfo[1])) {
			$ctrl = self::$_pathinfo[1];
			unset(self::$_pathinfo[1]);
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
	static private function _get_act()
	{
		$default_act = Config::get('sys.default_act', 'index');
		if (defined('BIND_ACT')) {
			$act = BIND_ACT;
		} else if (isset(self::$_pathinfo[0]) && defined('BIND_APP') && defined('BIND_CTRL')) {
			$act = self::$_pathinfo[0];
			unset(self::$_pathinfo[0]);
		} else if (isset(self::$_pathinfo[1]) && defined('BIND_APP')) {
			$act = self::$_pathinfo[1];
			unset(self::$_pathinfo[1]);
		} else if (isset(self::$_pathinfo[2])) {
			$act = self::$_pathinfo[2];
			unset(self::$_pathinfo[2]);
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
	static private function _setGet()
	{
		if (!empty(self::$_pathinfo)) {
			if (isset($_GET[$_SERVER['QUERY_STRING']])) {
				unset($_GET[$_SERVER['QUERY_STRING']]);
			}
			$params_array = array_values(self::$_pathinfo);
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


