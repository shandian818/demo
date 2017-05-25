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
	/**
	 * 默认配置
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @var array
	 */
	private $_options = [
		'view_debug' => false,
		'view_path' => './view/',
		'view_suffix' => '.html',
		'view_cache_suffix' => '.php',
		'view_cache_path' => './cache/',
		'view_directive_prefix' => 'like-',
	];

	//用来渲染的数据
	private $_view_data = [];

	/**
	 * 构造
	 * View constructor.
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		$this->_options = array_merge($this->_options, $options);
	}

	/**
	 * 模板变量赋值
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $name
	 * @param null $value
	 */
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

	/**
	 * 获取渲染后的模板内容
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_name
	 * @param array $tpl_data
	 */
	public function fetch($tpl_name, $tpl_data = [])
	{
		$cache_file = $this->_options['view_cache_path'] . md5($tpl_name) . $this->_options['view_cache_suffix'];
		if (!file_exists($cache_file) || $this->_options['view_debug']) {
			//没有缓存或者是debug模式-重新编译模板
			$cache_dir = dirname($cache_file);
			if (!is_dir($cache_dir)) {
				mkdir($cache_dir, 0777);
			}
			// 编译生成缓存
			$content = $this->_getCacheContent($tpl_name, $tpl_data);
			file_put_contents($cache_file, $content);
		}
	}

	/**
	 * 获取缓存文件内容
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_name
	 * @param $tpl_data
	 * @return string
	 */
	private function _getCacheContent($tpl_name, $tpl_data)
	{
		if (!empty($tpl_data)) {
			$this->_view_data = array_merge($this->_view_data, $tpl_data);
		}
		$tpl_content = $this->_getTplContent($tpl_name);
		//模板解析
		$result = $this->_parseContent($tpl_content);
		return $result;
	}

	/**
	 * 获取模板原始内容
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_name
	 * @return bool|string
	 * @throws \Exception
	 */
	private function _getTplContent($tpl_name)
	{
		if (file_exists($tpl_name) || false !== strpos($tpl_name, $this->_options['view_suffix'])) {
			$real_filename = $tpl_name;
		} else {
			$real_filename = $this->_options['view_path'] . strtolower($tpl_name) . $this->_options['view_suffix'];
		}
		if (is_file($real_filename)) {
			return file_get_contents($real_filename);
		} else {
			throw new \Exception('模板文件不存在' . $real_filename);
		}
	}

	/**
	 * 解析内容
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_content
	 * @return string
	 */
	private function _parseContent($tpl_content)
	{

		return '';
	}
}