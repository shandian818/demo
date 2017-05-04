<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/27
 * Time: 10:12
 */

namespace likephp\core;


class Route
{


	function __construct()
	{

		if(isset($_SERVER['REQUEST_URI'])&&$_SERVER['REQUEST_URI']!='/'){
			$uri = $_SERVER['REQUEST_URI'];
			$uri_array = explode('/',trim($uri,'/'));
			if(isset($uri_array[0])){

			}
		}
	}
}


