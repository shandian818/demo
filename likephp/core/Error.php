<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/6/8
 * Time: 19:54
 */

namespace likephp\core;


class Error
{
	public static function register()
	{
		error_reporting(E_ALL);
		set_error_handler([__CLASS__, 'appError']);
		set_exception_handler([__CLASS__, 'appException']);
		register_shutdown_function([__CLASS__, 'appShutdown']);
	}

	/**
	 * 程序错误时执行的函数
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 * @param $errcontext
	 */
	public static function appError($errno, $errstr, $errfile, $errline, $errcontext)
	{
		ob_start();
		ob_clean();
		$_html = '<b>出错啦!</b>';
		$_html .= '<p>' . $errstr . '</p>';
		$_html .= '<p>' . $errfile . ':' . $errline . '</p>';
		die($_html);
	}

	/**
	 * 程序异常时执行的函数
	 * 捕获php代码跑不了的错误
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $e
	 */
	public static function appException($e)
	{
		ob_start();
		ob_clean();
		$_html = '<b>异常啦!</b>';
		$_html .= '<p>' . $e->getMessage() . '</p>';
		$_html .= '<p>' . $e->getFile() . ':' . $e->getLine() . '</p>';
		die($_html);
	}

	/**
	 * 程序终止时调用的方法
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 */
	public static function appShutdown()
	{
		$error = error_get_last();
		if (!is_null($error) && in_array($error['type'], ['E_ERROR', 'E_CORE_ERROR', 'E_COMPILE_ERROR', 'E_RECOVERABLE_ERROR'])) {
			//发生致命错误
			die('发生致命错误');
		}
	}
}