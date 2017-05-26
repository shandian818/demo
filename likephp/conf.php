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
		'path' => '',//为空则在app下的view
		'suffix' => '.html',
		'cache_suffix' => '.php',
		'cache_path' => './cache/',
		'directive_prefix' => 'like-',
	],
	//pathinfo配置

	'pathinfo' => [
		'var' => 'url',
		'fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
		'depr' => '/',
	]


];