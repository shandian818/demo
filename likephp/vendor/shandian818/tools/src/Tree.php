<?php
/**
 * 小工具-树
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/9/12
 * Time: 14:14
 */

namespace tools;

class Tree
{
	static private $_field_names = [
		'field_id' => 'id',
		'field_pid' => 'pid',
		'field_name' => 'name',
		'field_full_name' => 'full_name',
	];
	static private $_icons = ['&nbsp;&nbsp;│', '&nbsp;&nbsp;├ ', '&nbsp;&nbsp;└ '];
	static private $_tree_list = [];//格式化后的树结构

	static public function init($field_names = [])
	{
		self::$_field_names = array_merge(self::$_field_names, $field_names);
	}

	/**
	 * 获取children结构树
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param int $pid 父id
	 * @param string $field_name_children 存子孙节点的字段名
	 * @return array
	 */
	static public function getChildren($list, $pid = 0, $field_name_children = 'children')
	{
		$array_children = [];
		foreach ($list as $key => $value) {
			if ($value[self::$_field_names['field_pid']] == $pid) {
				$children = self::getChildren($list, $value[self::$_field_names['field_id']], $field_name_children);
				if (!empty($children)) {
					$value[$field_name_children] = $children;
				}
				$array_children[] = $value;
				unset($list[$key]);
			}
		}
		return $array_children;
	}

	/**
	 * 获取子节点集合（一级）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param int $pid 父id
	 * @return array
	 */
	static public function getChild($list, $pid = 0)
	{
		$array_child = [];
		foreach ($list as $key => $value) {
			if ($value[self::$_field_names['field_pid']] == $pid) {
				$array_child[] = $value;
				unset($list[$key]);
			}
		}
		return $array_child;
	}

	/**
	 * 获取所有子孙的id集合
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param int $pid 父id
	 * @return array
	 */
	static public function getChildIds($list, $pid = 0)
	{
		$array_child_ids = [];
		foreach ($list as $key => $value) {
			if ($value[self::$_field_names['field_pid']] == $pid) {
				$array_child_ids[] = $value[self::$_field_names['field_id']];
				unset($list[$key]);
				$array_child_ids = array_merge($array_child_ids, self::getChildIds($list, $value[self::$_field_names['field_id']]));
			}
		}
		return $array_child_ids;
	}

	/**
	 * 用于递归获取树形结构集合
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param int $id
	 * @param string $space
	 * @return array
	 */
	static private function _getTree($list, $id = 0, $space = '')
	{
		$array_child = self::getChild($list, $id);
		if (!($count = count($array_child))) {
			return [];
		}
		$num = 1;
		for ($i = 0; $i < $count; $i++) {

			if ($count == $num) {
				$prefix = self::$_icons[2];
				$icon = '';
			} else {
				$prefix = self::$_icons[1];
				$icon = '' != $space ? self::$_icons[0] : '';
			}
			$array_child[$i][self::$_field_names['field_full_name']] = ('' != $space ? $space . $prefix : "") . $array_child[$i][self::$_field_names['field_name']];
			self::$_tree_list[] = $array_child[$i];
			self::_getTree($list, $array_child[$i][self::$_field_names['field_id']], $space . $icon . "&nbsp;&nbsp;");
			$num++;
		}
	}

	/**
	 * 获取树形结构集合（格式化=缩进字段）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param int $id
	 * @return array
	 */
	static public function getTree($list, $id = 0)
	{
		self::$_tree_list = [];
		self::_getTree($list, $id);
		return self::$_tree_list;
	}

	/**
	 * 获取所有的父级数据集合（含自己）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param $id
	 * @return array
	 */
	static public function getParents($list, $id)
	{
		$array_parents = [];
		foreach ($list as $key => $value) {
			if ($value[self::$_field_names['field_id']] == $id) {
				$array_parents[] = $value;
				unset($list[$key]);
				$array_parents = array_merge(self::getParents($list, $value[self::$_field_names['field_pid']]), $array_parents);
			}
		}
		return $array_parents;
	}

	/**
	 * 获取所有父级的id集合（不含自己）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $list
	 * @param $id
	 * @return array
	 */
	static public function getParentsIds($list, $id)
	{
		$array_parents_ids = [];
		foreach ($list as $key => $value) {
			if ($value[self::$_field_names['field_id']] == $id) {
				if ($value[self::$_field_names['field_pid']]) {
					$array_parents_ids[] = $value[self::$_field_names['field_pid']];
					unset($list[$key]);
					$array_parents_ids = array_merge($array_parents_ids, self::getParentsIds($list, $value[self::$_field_names['field_pid']]));
				}
			}
		}
		return $array_parents_ids;
	}

}