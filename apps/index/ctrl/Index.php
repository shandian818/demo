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

use likephp\core\Ctrl;

class Index extends Ctrl
{
	public function index()
	{
		dump($_GET);
		dump($_SERVER);
		dumpc($_GET);
		dumpc($_SERVER);
		echo 'Index/index';
	}

	public function test()
	{
		$this->assign('name', '名字');
		$this->assign('info', ['name' => '替换', 'user' => ['name' => 'info的user的name']]);
		$this->Render('testA');
	}

	public function testb()
	{
		$this->assign('name', '名字aaaaa');
		$this->assign('info', ['name' => '替换aaaaaa', 'user' => ['name' => 'info的user的name']]);
		$this->Render('testA');
	}
}