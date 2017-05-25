<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/23
 * Time: 15:38
 */

namespace likephp\core;


class View
{
	private $_options = [
		'view_debug' => false,
		'view_path' => './view/',
		'view_suffix' => '.html',
		'view_cache_path' => './cache/',
		'view_directive_prefix' => 'like-',
	];

	private $_view_data = [];

	public function __construct($options = [])
	{
		$this->_options = array_merge($this->_options, $options);
	}

	public function assign($name, $value = null)
	{
		if (is_array($name)) {
			$this->_view_data = array_merge($this->_view_data, $name);
		} else if (!is_null($value)) {
			$this->_view_data[$name] = $value;
		} else {
			unset($this->_view_data[$name]);
		}
	}

	public function fetch()
	{
		$exist_cache = false;//是否存在缓存
		if (!$exist_cache || $this->_options['view_debug']) {
			//没有缓存或者是debug模式-重新编译模板

		} else {

		}
	}
}