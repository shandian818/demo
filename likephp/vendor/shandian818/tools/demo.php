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

//修改此处引用即可演示demo
//修改此处引用即可演示demo
//修改此处引用即可演示demo
require_once 'src/Dump.php';
require_once 'src/Tree.php';
require_once 'src/Curl.php';

//demo start
//demo start
//demo start
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
echo '<hr>测试打印<br>';
//页面打印变量
\tools\Dump::dumpToHtml($test);

//控制台打印变量
\tools\Dump::dumpToConsole($test);


/*--------------------------------*/
$list = [
	['id' => 1, 'pid' => 0, 'name' => 'name_1'],
	['id' => 2, 'pid' => 0, 'name' => 'name_2'],
	['id' => 3, 'pid' => 0, 'name' => 'name_3'],
	['id' => 4, 'pid' => 1, 'name' => 'name_4'],
	['id' => 5, 'pid' => 1, 'name' => 'name_5'],
	['id' => 6, 'pid' => 2, 'name' => 'name_6'],
	['id' => 7, 'pid' => 1, 'name' => 'name_7'],
	['id' => 8, 'pid' => 3, 'name' => 'name_8'],
	['id' => 9, 'pid' => 8, 'name' => 'name_9'],
	['id' => 10, 'pid' => 8, 'name' => 'name_10'],
	['id' => 11, 'pid' => 3, 'name' => 'name_11'],
	['id' => 12, 'pid' => 6, 'name' => 'name_12'],
	['id' => 13, 'pid' => 7, 'name' => 'name_13'],
	['id' => 14, 'pid' => 8, 'name' => 'name_14'],
	['id' => 15, 'pid' => 7, 'name' => 'name_15'],
];

$tree_array = \tools\Tree::getTree($list);
echo '<hr>测试树形结构<br>';
foreach ($tree_array as $value) {
	echo $value['full_name'] . '<br>';
}

echo '<br><br>';
echo '<hr>测试children结构<br>';
$children_array = \tools\Tree::getChildren($list);
\tools\Dump::dumpToHtml($children_array);


echo '<hr>测试子数据集合(一级)<br>';
$child_array = \tools\Tree::getChild($list, 1);
\tools\Dump::dumpToHtml($child_array);


echo '<hr>测试子数据id集合（所有子孙）<br>';
$child_ids_array = \tools\Tree::getChildIds($list, 1);
\tools\Dump::dumpToHtml($child_ids_array);


echo '<hr>测试父数据集合（包含自己）<br>';
$parents_array = \tools\Tree::getParents($list, 15);
\tools\Dump::dumpToHtml($parents_array);


echo '<hr>测试父数据id集合（不含自己）<br>';
$parents_ids_array = \tools\Tree::getParentsIds($list, 15);
\tools\Dump::dumpToHtml($parents_ids_array);

/*--------------------------------*/
echo '<hr>开始curl=300次<br>';
for ($i = 0; $i < 300; $i++) {
	$curl_data[] =
		[
			'url' => 'http://likephp.com/result.php?aaa=' . $i,
			'data' => null,//可以没有
			'options' => [
				'type' => 'POST',//请求类型
				'timeout' => 5,//请求超时时长（秒）
			]
		];

}

$res = \tools\Curl::custom($curl_data,'\tools\Dump','dumpToHtml');