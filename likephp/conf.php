<?php
/**
 * 默认配置文件
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/28
 * Time: 9:47
 */

return [

	//系统配置
	'sys' => [
		'default_app' => 'index',
		'default_ctrl' => 'index',
		'default_act' => 'index',
		'apps_namespace' => 'apps',
	],
	//视图配置
	'view' => [

		'tpl_path' => './view/',//模板文件目录
		'tpl_suffix' => '.html',//模板后缀

		'compile_path' => RUNTIME_PATH . 'cache/',//编译文件目录
		'compile_suffix' => '.php',//编译文件后缀

		'cache_switch' => true,//是否开启静态缓存
		'cache_path' => RUNTIME_PATH . 'cache/html/',//静态html缓存文件目录
		'cache_time' => 60,//静态html缓存时效（单位秒）

		'var_left' => '{{',//模板变量左标记
		'var_right' => '}}',//模板变量右标记
		'tag_left' => '<',//模板标签左标记
		'tag_right' => '>',//模板标签右标记
	],
	//pathinfo配置
	'pathinfo' => [
		'var' => 'url',
		'fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
		'depr' => '/',
	],
	//db配置
	'db' => [
		'dbhost'=>'127.0.0.1',
		'dbport'=>'3306',
		'dbuser'=>'root',
		'dbpass'=>'root',
		'dbname'=>'test',
	],


];