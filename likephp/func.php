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
	\tools\Dump::dumpToHtml($data, 2);
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
	\tools\Dump::dumpToConsole($data, 2);
}

/**
 * 大写字母转分隔符（下划线）+小写字母
 * 两边为分隔符（下划线）时，自动过滤掉
 * 例如: AbcDef => abc_def
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * @param $string
 * @param string $line 分隔符（默认为下划线）
 * @return mixed|string
 */
function caps_to_line($string, $line = '_')
{
	$string = preg_replace_callback('/([A-Z]{1})/', function ($matches) use ($line) {
		return $line . strtolower($matches[0]);
	}, $string);
	$string = trim($string, $line);
	return $string;
}