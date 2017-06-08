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
	private $_view_data;//模板变量

	/**
	 * 默认配置
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @var array
	 */
	private $_options = [
		'debug' => false,//是否调试模式

		'tpl_path' => './view/',//模板文件目录
		'tpl_suffix' => '.html',//模板后缀

		'compile_path' => './cache/',//编译文件目录
		'compile_suffix' => '.php',//编译文件后缀

		'cache_switch' => true,//是否开启静态缓存
		'cache_path' => './cache/html/',//静态html缓存文件目录
		'cache_time' => 3600,//静态html缓存时效（单位秒）

		'var_left' => '{{',//模板变量左标记
		'var_right' => '}}',//模板变量右标记
		'tag_left' => '<',//模板标签左标记
		'tag_right' => '>',//模板标签右标记
	];

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
	 * 渲染
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_name
	 * @param array $tpl_data
	 */
	public function make($tpl_name, $tpl_data = [])
	{
		$compile_file = $this->_options['compile_path'] . md5(strtolower($tpl_name)) . $this->_options['compile_suffix'];
		if (false != $this->_options['debug']) {
			//调试开启
			//重新编译
			extract($this->_view_data);
			$this->_makeCompileFile($compile_file, $tpl_name, $tpl_data);
			include $compile_file;
		}

		if (false == $this->_options['debug'] && false != $this->_options['cache_switch']) {
			//调试未开启并且静态缓存开启
			$cache_file = $this->_options['cache_path'] . md5(strtolower($tpl_name)) . '.html';
			if (file_exists($cache_file)) {
				//静态缓存存在
				include $cache_file;
			} else {
				//静态缓存不存在-生成静态缓存

			}
		}
	}

	private function _makeCompileFile($compile_file, $tpl_name, $tpl_data)
	{
		if (!empty($tpl_data)) {
			$this->_view_data = array_merge($this->_view_data, $tpl_data);
		}
		$tpl_real_filename = $this->_getTplRealFileName($tpl_name);//获取真实模板文件名称
		$tpl_real_content = $this->_getTplRealFileContent($tpl_real_filename);//获取真实模板文件内容
		$parse_content = $this->_paraseAll($tpl_real_content);//获取真实模板文件内容
		if (!file_put_contents($compile_file, $parse_content)) {
			throw new \Exception('编译文件出错' . $compile_file);
		}
	}

	private function _paraseAll($tpl_real_content)
	{
		//1.去除bom头
		$parse_content = trim($this->_removeUTF8Bom($tpl_real_content));
		//2.解析include

		//3.解析变量

//		$parse_content = $this->_parseVarA($parse_content);
		$parse_content = $this->_parseVar($parse_content);

		return $parse_content;
	}


	/**
	 * 获取真实文件内容
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $tpl_name
	 * @return string
	 */
	private function _getTplRealFileName($tpl_name)
	{
		$app = \likephp\core\Request::getApp();
		$ctrl = \likephp\core\Request::getCtrl();
		$action = \likephp\core\Request::getAct();
		if (empty($this->_options['path'])) {
			$this->_options['path'] = APPS_PATH . $app . '/view/';//默认视图目录
		}
		if (file_exists($tpl_name) || false !== strpos($tpl_name, $this->_options['tpl_suffix'])) {
			//绝对路径文件或者包含模板后缀
			$real_filename = $tpl_name;
		} else if (is_null($tpl_name)) {
			$real_filename = $this->_options['path'] . strtolower($ctrl . DS . $action) . $this->_options['tpl_suffix'];
		} else if (false !== strpos($tpl_name, '/')) {
			//如果包含系统路径
			$real_filename = $this->_options['path'] . strtolower($tpl_name) . $this->_options['tpl_suffix'];
		} else {
			//仅写action
			$real_filename = $this->_options['path'] . strtolower($ctrl . DS . $tpl_name) . $this->_options['tpl_suffix'];
		}
		return $real_filename;
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
	private function _getTplRealFileContent($tpl_real_filename)
	{
		if (is_file($tpl_real_filename)) {
			return file_get_contents($tpl_real_filename);
		} else {
			throw new \Exception('模板文件不存在' . $tpl_real_filename);
		}
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

	private function _parseVarA($parse_content)
	{

		$pattern = '/' . $this->_options['var_left'] . '\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*' . $this->_options['var_right'] . '/i';
		if (preg_match($pattern, $parse_content)) {
			$parse_content = preg_replace_callback($pattern, '<?php echo \$this->_view_data["$1"];?>', $parse_content);
		}
		return $parse_content;
	}

	private function _parseVar($parse_content)
	{

		$pattern_dot = '/\$\w+((\.\w+)*)?/i';
		if (preg_match($pattern_dot, $parse_content)) {
			$parse_content = preg_replace_callback($pattern_dot, [$this, 'parseVarDot'], stripslashes($parse_content));
		}
		$pattern = '/' . $this->_options['var_left'] . '\s?(\$[^\d\s]*)' . $this->_options['var_right'] . '/i';
		if (preg_match($pattern, $parse_content)) {
			$parse_content = preg_replace($pattern, '<?php echo ($1);?>', $parse_content);
		}
		return $parse_content;

	}

	private function parseVarDot($var)
	{
		if (empty($var[0])) {
			return '';
		}
		$vars = explode('.', $var[0]);
		$name = array_shift($vars);
		foreach ($vars as $val) {
			$name .= '["' . trim($val) . '"]';
		}
		return $name;
	}
}