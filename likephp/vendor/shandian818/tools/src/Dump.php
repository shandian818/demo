<?php
/**
 * 小工具-打印
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/8
 * Time: 20:11
 */

namespace tools;


class Dump
{
	/**
	 * 页面打印变量
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $data
	 */
	static public function dumpToHtml($data)
	{
		$debug = debug_backtrace();
		unset($debug[1]['args']);
		$info = $debug[1]['file'] . ":" . $debug[1]['line'];
		$string = "\n";
		$string .= "<div>\n";
		$string .= "	<p style=\"color: red;margin: 0\">" . $info . "</p>\n";
		$string .= "	<p style=\"color: green;line-height: 16px; font-size: 14px;margin: 5px\">\n";
		$string .= self::_dump($data);
		$string .= "	</p>\n";
		$string .= "</div>\n";
		echo $string;
	}

	/**
	 * 控制台打印变量
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $data
	 */
	static public function dumpToConsole($data)
	{
		$debug = debug_backtrace();
		unset($debug[1]['args']);
		$info = $debug[1]['file'] . ":" . $debug[1]['line'];
		$string = "\n";
		$string .= "<script>\n";
		$string .= "//调试\n";
		$string .= "console.log('%cfile:" . addslashes($info) . "','color:red');\n";
		$result = self::_dump($data, true);
		$string .= "console.log('%c" . $result . "','color:green');\n";;
		$string .= "</script>\n";
		echo $string;
	}


	/**
	 * 组织格式化输出字符串（为了dump和dumpc使用）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $data
	 * @param bool $is_console 是否console格式
	 * @param null $field_name 字段名（递归使用）
	 * @param int $level 级（递归使用）
	 * @param null $prop_string （对象时，属性说明）
	 * @return string
	 */
	static private function _dump($data, $is_console = false, $field_name = null, $level = 0, $prop_string = null)
	{
		$rn = $is_console ? '\n' : "<br />\n";
		$space = $is_console ? "|    " : "|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$string = "";
		$repeat_space = str_repeat($space, $level);
		$string .= $repeat_space;
		$type = ucfirst(gettype($data));
		$real_type = $type === 'Double' ? 'Float' : $type;
		$count = 0;//数组或对象的key个数
		$field_string = (!is_null($field_name)) ? (is_null($prop_string) ? "[\"$field_name\"] => " : "[\"$field_name\" : $prop_string] => ") : "";
		if (is_array($data)) {
			$count += count($data);
			$string .= $field_string;
			$string .= $real_type . " ($count)" . $rn;
			$level++;
			$string .= $repeat_space . "(" . $rn;
			foreach ($data as $field_name => $value) {
				$string .= self::_dump($value, $is_console, $field_name, $level);
			}
			$string .= $repeat_space . ")" . $rn;
		} else if (is_object($data)) {
			$ref = new \ReflectionClass($data);
			$class_name = $ref->name;
			$obj_prop_strings = '';
			$level++;
			if ('stdClass' != $class_name) {
				$props = $ref->getProperties();
				$count += count($props);
				if (!empty($count)) {
					foreach ($props as $prop) {
						$obj_prop_name = $prop->name;
						$pro = $ref->getProperty($obj_prop_name);
						$pro->setAccessible(true);
						$obj_prop_value = $pro->getValue($data);
						$prop_string = $pro->isStatic() ? 'Static ' : '';
						$prop_string .= $pro->isPublic() ? 'Public' : '';
						$prop_string .= $pro->isProtected() ? 'Protected' : '';
						$prop_string .= $pro->isPrivate() ? 'Private' : '';
						$obj_prop_strings .= self::_dump($obj_prop_value, $is_console, $obj_prop_name, $level, $prop_string);
					}
				}
			} else {
				$count += count((array)$data);
				foreach ($data as $field_name => $value) {
					$obj_prop_strings .= self::_dump($value, $is_console, $field_name, $level);
				}
			}
			$string .= $field_string;
			$string .= $class_name . ' ' . $real_type . " ($count)" . $rn;
			$string .= $repeat_space . "(" . $rn;
			$string .= $obj_prop_strings;
			$string .= $repeat_space . ")" . $rn;

		} else {
			$count += strlen($data);
			$string .= $field_string;
			if (is_null($data)) {
				$real_data = 'null';
			} else if (is_bool($data)) {
				$real_data = $data ? 'TRUE' : 'FALSE';
			} else if (is_string($data)) {
				$real_data = '"' . $data . '"';
			} else {
				$real_data = $data;
			}
			$string .= $real_type . " ($count) " . $real_data . $rn;
		}

		return $string;
	}
}