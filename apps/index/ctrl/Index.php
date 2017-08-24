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

use apps\index\model\UserModel;
use likephp\core\Ctrl;
use Medoo\Medoo;

class Index extends Ctrl
{
	public function index()
	{

//		$database = new Medoo([
//			'database_type' => 'mysql',
//			'database_name' => 'test',
//			'server' => 'localhost',
//			'username' => 'root',
//			'password' => 'root',
//			'charset' => 'utf8'
//		]);
////		dump($database);
//		$a= $database->select("like_user",['uid','uname'], [
//			"OR" => [
//				"AND" => [
//					"uid[>=]" => "3",
//					"uid[<]" => "5"
//				],
//				"uname" => "foo"
//			]
//		]);
//		dump($a);
//		dump($database->last());

		$user_model = new UserModel();
		dump($user_model);
//		$list = $user_model->where('uid>1')->field('uid,uname,unick')->select();
//		$list = $user_model->where([])->field('a,b,c')->select();
//		dump($list);
	}

	public function test()
	{
		$this->assign('name', '名字');
		$this->assign('info', ['name' => '替换', 'user' => ['name' => 'info的user的name']]);
		$this->Render('testview');
	}

	public function testb()
	{
		$this->assign('name', '名字aaaaa');
		$this->assign('info', ['name' => '替换aaaaaa', 'user' => ['name' => 'info的user的name']]);
		$this->Render('testview');
	}
}