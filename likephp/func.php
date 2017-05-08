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
	\tools\Dump::dumpToHtml($data);
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
	\tools\Dump::dumpToConsole($data);
}
