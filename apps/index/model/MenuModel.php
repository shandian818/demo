<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/8/23
 * Time: 16:31
 */

namespace apps\index\model;


use likephp\core\Model;

class MenuModel extends Model
{
	public function getOne($id)
	{
		$result = $this->where(['id' => $id])->find();
		dump($result);
		return $result;
	}
}