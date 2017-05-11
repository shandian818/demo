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
	// PATHINFO变量名 用于兼容模式
	'var_pathinfo' => 'url',
	// 兼容PATH_INFO获取
	'pathinfo_fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
	// pathinfo分隔符
	'pathinfo_depr' => '/',


	//
	'default_module' => 'index',
	'default_controller' => 'index',
	'default_action' => 'index',
	'app_namespace' => 'apps'
];