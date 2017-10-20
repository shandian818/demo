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

use apps\index\model\MenuModel;
use apps\index\model\UserModel;
use likephp\core\Ctrl;
use likephp\core\Model;
use Medoo\Medoo;

class Index extends Ctrl
{
	public function index()
	{
//		$model = new Model();
//		$list = $model->table('Menu')->where(['status[!=]'=>1])->select();
//		dump($list);
//		$this->api_success($list);
//		$list = [];
//		for ($i = 1; $i <= 10; $i++) {
//			$data = [
//				'pid'=>0,
//				'status'=>1,
//				'name'=>'字段填充'.$i,
//			];
//			$list[]=$data;
//		}
//		$status = $model->table('Menu')->addAll($list);
//		dump($model->getLastSql());
		$menu_model = new MenuModel();
		$list = $menu_model->getOne(3);
		dump($list);

	}

	public function test()
	{
		$this->assign('name', '名字');
		$this->assign('info', ['name' => '替换', 'user' => ['name' => 'info的user的name']]);
		$this->render('testview');
	}

	public function testb()
	{
		$this->assign('name', '名字aaaaa');
		$this->assign('info', ['name' => '替换aaaaaa', 'user' => ['name' => 'info的user的name']]);
		$this->render('testview');
	}
}