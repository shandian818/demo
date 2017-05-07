<?php
/**
 * 项目入口文件
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/4/26
 * Time: 11:32
 */

//定义应用目录
define("APP_PATH", __DIR__ . "/../apps/");
//载入框架核心
require_once __DIR__ . "/../likephp/boot.php";

//

//$a = \likephp\core\Request::isGet();
//
//
//dump($_SERVER);
//dump($a);
//
//dump($_SERVER);
//dump($a);
//$req = \likephp\core\Request::getInstance();
//dump($req::domain(false,true));
//
//$index = new \apps\index\ctrl\Index();
//$index->index();

$o = new stdClass();
$o->a = 'dsad';
$o->ab = 123;
$o->abc = false;
$test = [
	'a' => 1,
	'b' => [
		'c' => 2,
		'd' => [
			'e' => 3,
			'f' => [
				'g' => 4,
				'i' => $o,
				'j' => true,
				'k' => 5.6654,
				'l' => 'true',
				"m" => '我是来测试的',
				'h' => [
					'aa' => 'asd',
					'aaasd' => null,
					'aaassssd' => '',
					'bb' => 'dsadasd'
				]
			]
		]
	]
];
\likephp\core\Config::set($test);
$data = \likephp\core\Config::get();
dump($data);
\likephp\core\Config::set('b.c', '修改1111');
$data = \likephp\core\Config::get();
dump($data);

$data = \likephp\core\Config::get('b.d.f.h.bb');
dump($data);
\likephp\core\Config::set('b.d.f.h.bb', '修改222');
$data = \likephp\core\Config::get();
dump($data);
$data = \likephp\core\Config::get('b.d.f.h.bb');
dump($data);
$data = \likephp\core\Config::set('b.d.f.h.bb',false);
$data = \likephp\core\Config::get();
dump($data);
$data = \likephp\core\Config::set('b.d.f.h.bb',null);
$data = \likephp\core\Config::get();
dump($data);
$data = \likephp\core\Config::set('b.d.f','');
$data = \likephp\core\Config::get('b.d.f');
dump($data);
$data = \likephp\core\Config::get();
dump($data);
