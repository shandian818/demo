<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 11:49
 */


/**
 * 在控制台打印结果
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * @param $val
 * @param bool $is_repeat 是否带开始结束
 */
function clog($val, $is_repeat = false)
{
	$debug = debug_backtrace();
	unset($debug[0]['args']);
	$repeat = str_repeat('-', 20);
	$string = "\n";
	$string .= "<script>\n";
	$string .= $is_repeat ? "console.log('%c" . $repeat . "开始" . $repeat . "','color:#fff;background-color: #000;');\n" : "";
	$string .= "console.log('%cfile:" . addslashes($debug[0]['file']) . "|line:" . $debug[0]['line'] . "|time:" . microtime(true) . "|mem:" . memory_get_usage() . "','color:red');\n";
	if (is_array($val) || is_object($val)) {
		$string .= "console.log(" . json_encode($val) . ");\n";
	} else {
		$val = is_bool($val) ? ($val ? "true" : "false") : $val;
		$string .= "console.log('" . $val . "');\n";
	}
	$string .= $is_repeat ? "console.log('%c" . $repeat . "结束" . $repeat . "','color:#fff;background-color: #000;');" : "";
	$string .= "</script>";
	echo $string;
}


function dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
{
	$do_dump_indent = "<span style='color:#666666;'>|</span> &nbsp;&nbsp; ";
	$reference = $reference . $var_name;
	$keyvar = 'the_do_dump_recursion_protection_scheme';
	$keyname = 'referenced_object_name';

	// So this is always visible and always left justified and readable
	echo "<div style='text-align:left; background-color:white; font: 100% monospace; color:black;'>";

	if (is_array($var) && isset($var[$keyvar])) {
		$real_var = &$var[$keyvar];
		$real_name = &$var[$keyname];
		$type = ucfirst(gettype($real_var));
		echo "$indent$var_name <span style='color:#666666'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
	} else {
		$var = array($keyvar => $var, $keyname => $reference);
		$avar = &$var[$keyvar];

		$type = ucfirst(gettype($avar));
		if ($type == "String") $type_color = "<span style='color:green'>";
		elseif ($type == "Integer") $type_color = "<span style='color:red'>";
		elseif ($type == "Double") {
			$type_color = "<span style='color:#0099c5'>";
			$type = "Float";
		} elseif ($type == "Boolean") $type_color = "<span style='color:#92008d'>";
		elseif ($type == "NULL") $type_color = "<span style='color:black'>";

		if (is_array($avar)) {
			$count = count($avar);
			echo "$indent" . ($var_name ? "$var_name => " : "") . "<span style='color:#666666'>$type ($count)</span><br>$indent(<br>";
			$keys = array_keys($avar);
			foreach ($keys as $name) {
				$value = &$avar[$name];
				dump($value, "['$name']", $indent . $do_dump_indent, $reference);
			}
			echo "$indent)<br>";
		} elseif (is_object($avar)) {
			echo "$indent$var_name <span style='color:#666666'>$type</span><br>$indent(<br>";
			foreach ($avar as $name => $value) dump($value, "$name", $indent . $do_dump_indent, $reference);
			echo "$indent)<br>";
		} elseif (is_int($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
		elseif (is_string($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color\"" . htmlentities($avar) . "\"</span><br>";
		elseif (is_float($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
		elseif (is_bool($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
		elseif (is_null($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> {$type_color}NULL</span><br>";
		else echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> " . htmlentities($avar) . "<br>";
		$var = $var[$keyvar];
	}
	echo "</div>";
}