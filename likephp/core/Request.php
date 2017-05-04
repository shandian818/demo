<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 21:33
 */

namespace likephp\core;


class Request
{
	static private $_instance;
	static private $_method;
	static private $_scheme;
	static private $_domain;
	static private $_port;

	function __construct()
	{
		self::$_method = strtoupper($_SERVER['REQUEST_METHOD']);
		self::$_scheme = empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http' : $_SERVER['HTTP_X_CLIENT_PROTO'];
		self::$_domain = $_SERVER['SERVER_NAME'];
		self::$_port = $_SERVER['SERVER_PORT'];

	}

	/**
	 * 获取实例
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return static
	 */
	static public function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new static();
		}
		return self::$_instance;
	}

	/**
	 * 是否post请求
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return bool
	 */
	static public function isPost()
	{
		return self::$_method == 'POST';
	}

	/**
	 * 是否get请求
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return bool
	 */
	static public function isGet()
	{
		return self::$_method == 'GET';
	}

	/**
	 * 获取完整域名
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param bool $only
	 * @param bool $port
	 * @return string
	 */
	static public function domain($only = false, $port = true)
	{
		$domain = '';
		$domain .= $only === false ? self::$_scheme . '://' . self::$_domain : self::$_domain;
		$domain .= $port && self::$_port != 80 ? ':' . self::$_port : '';
		return $domain;
	}

	static public function pathinfo()
	{

	}
}