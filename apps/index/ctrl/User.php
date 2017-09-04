<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/11
 * Time: 21:31
 */

namespace apps\index\ctrl;


use likephp\core\Ctrl;

class User extends Ctrl
{
	public function index()
	{
		echo 'user-index';
	}


	public function test()
	{
		$this->assign('name', '名字uuuuuu');
		$this->assign('info', ['name' => '替uuuuuuuuu换', 'user' => ['name' => 'uuuuuuinfo的uuuuuuuser的name']]);
		$this->render('testview');
	}
}