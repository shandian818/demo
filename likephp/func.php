<?php
/**
 * 系统公共函数
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 11:49
 */

/**
 * 页面打印变量
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * @param $data
 */
function dump($data)
{
	$debug = debug_backtrace();
	unset($debug[0]['args']);
	$info = $debug[0]['file'] . "|line:" . $debug[0]['line'] . "|time:" . microtime(true) . "|mem:" . memory_get_usage();
	$string = '';
	$string .= '<div>';
	$string .= '<h5 style="color: red;margin: 0">' . $info . '</h5>';
	$string .= '<p style="color: green;line-height: 18px; font-size: 14px">';
	$string .= _dump($data);
	$string .= '</p>';
	$string .= '</div>';
	echo $string;
}

/**
 * 控制台打印变量
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * @param $data
 */
function dumpc($data)
{
	$debug = debug_backtrace();
	unset($debug[0]['args']);
	$info = $debug[0]['file'] . "|line:" . $debug[0]['line'] . "|time:" . microtime(true) . "|mem:" . memory_get_usage();
	$repeat = str_repeat('-', 20);
	$string = "\n";
	$string .= "<script>\n";
	$string .= "//调试\n";
	$string .= "console.log('%cfile:" . addslashes($info) . "','color:red');\n";
	$result = _dump($data, true);
	$string .= "console.log('%c" . $result . "','color:green');\n";;
	$string .= "</script>\n";
	echo $string;
}


/**
 * 组织格式化输出字符串（为了dump和dumpc使用）
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * @param $data 需要打印变量
 * @param bool $is_console 是否console格式
 * @param null $field_name 字段名（递归使用）
 * @param int $level 级（递归使用）
 * @return string
 */
function _dump($data, $is_console = false, $field_name = null, $level = 0)
{
	$rn = $is_console ? '\n' : '<br />';
	$space = $is_console ? "|    " : "|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$string = "";
	$repeat_space = str_repeat($space, $level);
	$string .= $repeat_space;
	$type = ucfirst(gettype($data));
	$real_type = $type === 'Double' ? 'Float' : $type;
	$count = 0;
	if (is_array($data) || is_object($data)) {
		$count += count((array)$data);
		$string .= !is_null($field_name) ? "[\"$field_name\"]=>" : "";
		$string .= $real_type . " ($count)" . $rn;
		$level++;
		$string .= $repeat_space . "(" . $rn;
		foreach ($data as $field_name => $value) {
			$string .= _dump($value, $is_console, $field_name, $level);
		}
		$string .= $repeat_space . ")" . $rn;
	} else {
		$count += strlen($data);
		$string .= !is_null($field_name) ? "[\"$field_name\"]=>" : "";

		if (is_null($data)) {
			$real_data = 'null';
		} else if (is_bool($data)) {
			$real_data = $data ? 'TRUE' : 'FALSE';
		} else if (is_string($data)) {
			$real_data = '"' . $data . '"';
		} else {
			$real_data = $data;
		}
		$string .= $real_type . " ($count) " . $real_data;
		$string .= $rn;
	}

	return $string;
}