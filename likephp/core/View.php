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
		'debug' => false,
		'path' => './view/',
		'suffix' => '.html',
		'cache_suffix' => '.php',
		'cache_path' => './cache/',
		'directive_prefix' => 'like-',
		'var_left' => '{{',
		'var_right' => '}}',
		'tag_left' => '<',
		'tag_right' => '>',
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
	public function make($tpl_name, $tpl_data = [])
	{
		$real_filename = $this->_getRealFilename($tpl_name);
		$cache_file = $this->_options['cache_path'] . md5($tpl_name) . $this->_options['cache_suffix'];
		if (!file_exists($cache_file) || $this->_options['debug']) {
			//没有缓存或者是debug模式-重新编译模板
			$cache_dir = dirname($cache_file);
			if (!is_dir($cache_dir)) {
				mkdir($cache_dir, 0777);
			}
			// 编译生成缓存
			$this->_makeCacheFile($cache_file, $real_filename, $tpl_data);
		}
		//获取缓存文件内容
		$cache_content = $this->_getCacheContent($cache_file);
		return $cache_content;
	}

	private function _getRealFilename($tpl_name)
	{
		$app = \likephp\core\Request::getApp();
		$ctrl = \likephp\core\Request::getCtrl();
		$action = \likephp\core\Request::getAct();
		if (empty($this->_options['path'])) {
			$this->_options['path'] = APPS_PATH . $app . '/view/';//默认视图目录
		}
		if (file_exists($tpl_name) || false !== strpos($tpl_name, $this->_options['suffix'])) {
			//绝对路径文件或者包含模板后缀
			$real_filename = $tpl_name;
		} else if (is_null($tpl_name)) {
			$real_filename = $this->_options['path'] . strtolower($ctrl . DS . $action) . $this->_options['suffix'];
		} else if (false !== strpos($tpl_name, '/')) {
			//如果包含系统路径
			$real_filename = $this->_options['path'] . strtolower($tpl_name) . $this->_options['suffix'];
		} else {
			//仅写action
			$real_filename = $this->_options['path'] . strtolower($ctrl . DS . $tpl_name) . $this->_options['suffix'];
		}
		return $real_filename;
	}

	/**
	 * 获取缓存文件内容
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $cache_file
	 * @return bool|string
	 */
	private function _getCacheContent($cache_file)
	{
		$content = file_get_contents($cache_file);
		return $content;
	}

	/**
	 * 生成缓存文件
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_name
	 * @param $tpl_data
	 */
	private function _makeCacheFile($cache_file, $real_filename, $tpl_data)
	{
		if (!empty($tpl_data)) {
			$this->_view_data = array_merge($this->_view_data, $tpl_data);
		}
		$tpl_content = $this->_getTplContent($real_filename);
		//模板解析
		$result = $this->_compile($tpl_content);
		file_put_contents($cache_file, $result);
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
	private function _getTplContent($real_filename)
	{
		if (is_file($real_filename)) {
			return file_get_contents($real_filename);
		} else {
			throw new \Exception('模板文件不存在' . $real_filename);
		}
	}

	/**
	 * 解析
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_content
	 * @return string
	 */
	private function _compile($tpl_content)
	{
		$content = trim($this->_removeUTF8Bom($tpl_content));
		$parse_include_content = $this->_makeInclude($content);
//		$content = preg_replace_callback('/' . $this->_options['tag_left'] . 'literal' . $this->_options['tag_right'] . '(.*?)' . $this->_options['tag_left'] . '\/literal' . $this->_options['tag_right'] . '/is', [$this, 'parseLiteral'], $content);
		$parse_content = $this->_compileVar($parse_include_content);
		return $parse_content;
	}

	private function _compileVar(&$content)
	{
		$content = preg_replace_callback('/(' . $this->_options['tag_left'] . ')([^\d\s].+?)(' . $this->_options['tag_right'] . ')/is', [$this, 'parseTag'], $content);
		return $content;
	}

	private function parseTag($content)
	{
		$content = preg_replace_callback('/\$\w+((\.\w+)*)?/', [$this, 'parseVar'], stripslashes($content[0]));
		return $content;
	}

	/**
	 * 去掉UTF-8 Bom头
	 * @param  string $string
	 * @access protected
	 * @return string
	 */
	private function _removeUTF8Bom($content)
	{
		$parse_content = substr($content, 0, 3) == pack('CCC', 239, 187, 191) ? substr($content, 3) : $content;
		return $parse_content;
	}

	private function _makeInclude($content)
	{
		$pattern = '/' . $this->_options['tag_left'] . 'include\sfile=[\'"](.+?)[\'"]\s*?\/' . $this->_options['tag_right'] . '/is';
		$parse_content = empty($content) ? '' : preg_replace_callback($pattern, [$this, '_parseInclude'], $content);
		return $parse_content;
	}

	private function _parseInclude($content)
	{
		$tpl_name = stripslashes($content[1]);
		$real_filename = $this->_getRealFilename($tpl_name);
		$content = $this->_getTplContent($real_filename);
		$parse_content = $this->_compile($content);
		return $parse_content;
	}

	private $_literal = [];

	/**
	 * 替换页面中的literal标签
	 *
	 * @access private
	 * @param string $content 模板内容
	 * @return string|false
	 */
	private function parseLiteral($content)
	{
		if (is_array($content)) {
			$content = $content[2];
		}
		if (trim($content) == '') {
			return '';
		}
		$i = count($this->_literal);
		$parseStr = "<!--###literal{$i}###-->";
		$this->_literal[$i] = $content;
		return $parseStr;
	}
}