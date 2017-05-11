<?php

/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/4
 * Time: 10:46
 */

namespace apps\index\ctrl;

class Index
{
	public function index()
	{
		dump($_GET);
		echo 'Index/index';
	}

	public function test()
	{
		echo 'Index/test';
	}
}