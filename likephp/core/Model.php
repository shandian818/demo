<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/6/15
 * Time: 20:22
 */

namespace likephp\core;


class Model
{
	public function __construct($model_name = '', $table_prefix = null, $connection_id = null)
	{
		$db_config = Config::get('db');
	}
}