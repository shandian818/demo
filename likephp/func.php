<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 11:49
 */


function dump($arr)
{
	echo "<pre>";
	print_r($arr);
	echo "<pre>";
}

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
		$string .= "console.log('" . $val . "');\n";
	}
	$string .= $is_repeat ? "console.log('%c" . $repeat . "结束" . $repeat . "','color:#fff;background-color: #000;');" : "";
	$string .= "</script>";
	echo $string;
}