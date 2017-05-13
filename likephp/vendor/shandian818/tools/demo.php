<?php
/**
 * 示例
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/8
 * Time: 20:11
 */
require 'vendor/autoload.php';
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

//页面打印变量
\tools\Dump::dumpToHtml($test);

//控制台打印变量
\tools\Dump::dumpToConsole($test);