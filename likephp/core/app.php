<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/26
 * Time: 15:19
 */

namespace likephp\core;


class App
{
	static public function run()
	{
		require_once 'loader.php';
		$loader = new \likephp\core\Loader();
		$loader->register();
		$loader->addNamespace('likephp',LIKE_PATH);
	}
}