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
use likephp\core\Model;
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
//		$a = $database->select("like_user", ['uid', 'uname'], [
//			"OR" => [
//				"AND" => [
////					"uid[!]" => [1, 2, 3],
//					"uid[!]" => [1, 2, 3],
//				],
//				"uname" => "foo"
//			]
//		]);
//		dumpc($a);
//		dumpc($database->last());exit;

//		$user_model = new UserModel();
////		dumpc($user_model);
//		$list = $user_model->where('uid>1')->field('uid,uname,unick')->group('status')->order('uid DESC')->select();
////		$list = $user_model->where([])->field('a,b,c')->select();
//		dumpc($list);
//		dumpc($user_model);
//		dumpc($user_model->getLastSql());
//
////
		$model = new Model();
		$where = [
			"AND" => [
				"OR" => [
					"uid[><]" => [1, 3],
					"uid[=]" => 5,
				],
				"uname[~]" => "foo"
			]
		];
		$list = $model
			->table('User')
			->where($where)
			->field('uid,uname,unick')
//			->group('status')
			->order('uid DESC')
			->comment('测试注释')
			->select();
		dumpc($list);
//		dumpc($model);
		dumpc($model->getLastSql());
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