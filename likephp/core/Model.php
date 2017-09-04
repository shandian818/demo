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
	public $model_name;//模型名称
	public $real_tabale_name;//真实表名
	public $db_name;//库名
	protected $_tabale_prefix;//表前缀
	protected $_db;//数据库实例
	protected $_db_objs = [];//数据库实例
	//存放sql每段数据
	protected $_data_array = [
	];
	//存放sql每段语句
	protected $_sql_array = [
	];

	protected $_sql;

	public function __construct($model_name = '', $table_prefix = '', $db_config = null)
	{
		$this->getDb($db_config);
		$this->_getModelName($model_name);
		$this->_getTablePrefix($table_prefix);
		$this->getRealTableName();


	}

	/**
	 * 获取真实表名
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return string
	 */
	private function getRealTableName()
	{
		if (empty($this->real_tabale_name) && !empty($this->model_name)) {
			$this->real_tabale_name = (!empty($this->db_name) ? $this->db_name . '.' : '') . $this->_tabale_prefix . strtolower($this->model_name);
		}
		return $this->real_tabale_name;
	}

	/**
	 * 获取表前缀
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $table_prefix
	 */
	protected function _getTablePrefix($table_prefix)
	{
		if (!empty($table_prefix)) {
			$this->_tabale_prefix = $table_prefix;
		}
	}

	/**
	 * 获取模型名
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $model_name
	 */
	protected function _getModelName($model_name)
	{
		if (empty($model_name)) {
			$this->model_name = $this->_getModelNameByClassName();
		} else {
			$this->model_name = $model_name;
		}
	}

	/**
	 * 根据类名获取模型名
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return bool|string
	 */
	protected function _getModelNameByClassName()
	{
		$name = substr(get_class($this), 0, -strlen('Model'));
		if ($pos = strrpos($name, '\\')) {
			$name = substr($name, $pos + 1);
		}
		return $name;
	}

	/**
	 * 获取db对象
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param null $db_config
	 * @return mixed|\PDO
	 */
	protected function getDb($db_config = null)
	{
		if (empty($db_config)) {
			$db_config = Config::get('db');
		}
		$dbhost = $db_config['dbhost'] ?: '';
		$dbport = $db_config['dbport'] ?: '';
		$dbuser = $db_config['dbuser'] ?: '';
		$dbpass = $db_config['dbpass'] ?: '';
		$dbname = $db_config['dbname'] ?: '';
		$this->_tabale_prefix = $db_config['dbpre'] ?: '';
		$this->db_name = $dbname;
		$connection_id = md5($dbhost . $dbport . $dbuser . $dbpass . $dbname);
		if (isset($this->_db_objs[$connection_id])) {
			$db = $this->_db_objs[$connection_id];
		} else {
			$db = new \PDO(
				"mysql:host={$dbhost};port={$dbport};dbname={$dbname}",
				$dbuser,
				$dbpass,
				[
					\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
				]
			);
			$this->_db_objs[$connection_id] = $db;
		}
		$this->_db = $db;
		return $db;
	}

	public function select()
	{
		$sql = $this->_parseAll();
		$sth = $this->execu($sql);
		$result = $sth->fetchAll();
		return $result;
	}

	private function _parseWhere()
	{
		$where_sql = '';
		if (!empty($this->_data_array['where'])) {
			$where_sql .= ' WHERE ';
			if (is_array($this->_data_array['where'])) {
				$where = $this->_data_array['where'];
				$where_keys = array_keys($where);
				$single_condition = array_diff_key($where, array_flip(
					['AND', 'OR']
				));

				if (!empty($single_condition)) {
					$condition = $this->_getWhereFieldString($single_condition, ' AND');

					if ($condition !== '') {
						$where_sql .= $condition;
					}
				}
				$where_AND = preg_grep("/^AND\s*#?$/i", $where_keys);
				$where_OR = preg_grep("/^OR\s*#?$/i", $where_keys);
				if (!empty($where_AND)) {
					$value = array_values($where_AND);
					$where_sql .= $this->_getWhereFieldString($where[$value[0]], ' AND');
				}
				if (!empty($where_OR)) {
					$value = array_values($where_OR);
					$where_sql .= $this->_getWhereFieldString($where[$value[0]], ' OR');
				}
			} else if (is_string($this->_data_array['where'])) {
				$where_sql .= $this->_data_array['where'];
			}
		}
		return $where_sql;
	}

	/**
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $sql
	 * @return mixed
	 */
	public function execu($sql)
	{
		$sth = $this->_db->prepare($sql);
		$sth->execute();
		return $sth;
	}

	private function _addQuote($value)
	{

		if (is_array($value)) {
			$string = '';
			foreach ($value as $v) {
				$string .= $this->_addQuote($v);
			}
			return $string;
		} else {
			if (is_int($value)) {
				return (int)$value;
			} else {
				return '"' . $value . '"';
			}
		}
	}

	private function _getWhereFieldString($data, $rel)
	{
		$where_array = [];
		foreach ($data as $key => $value) {
			$type = gettype($value);
			if (preg_match("/^(AND|OR)(\s+#.*)?$/i", $key, $relation_match) && $type === 'array') {
				$where_array[] = '(' . $this->_getWhereFieldString($value, ' ' . $relation_match[1]) . ')';
			} else {
				preg_match('/(#?)([a-zA-Z0-9_\.]+)(\[(?<operator>\>|\>\=|\<|\<\=|\!|\<\>|\>\<|\!?~)\])?/i', $key, $match);
				$field = $match[2];
				if (isset($match['operator'])) {
					$operator = $match['operator'];

					if ($operator === '!') {
						switch ($type) {
							case 'NULL':
								$where_array[] = $field . ' IS NOT NULL';
								break;

							case 'array':
								$where_array[] = $field . ' NOT IN (' . implode(',', $value) . ')';
								break;

							case 'integer':
							case 'double':
								$where_array[] = $field . ' != ' . $value;
								break;
							case 'string':
								$where_array[] = $field . ' != "' . $value . '"';
								break;
						}
					}
					if ($operator === '<>' || $operator === '><') {
						if ($type === 'array') {
							if ($operator === '><') {
								$field .= ' NOT';
							}

							$where_array[] = '(' . $field . ' BETWEEN ' . $value[0] . ' AND ' . $value[1] . ')';

						}
					}
					if ($operator === '~' || $operator === '!~') {
						if ($type !== 'array') {
							$value = [$value];
						}
						$connector = ' OR ';
						$stack = array_values($value);

						if (is_array($stack[0])) {
							if (isset($value['AND']) || isset($value['OR'])) {
								$connector = ' ' . array_keys($value)[0] . ' ';
								$value = $stack[0];
							}
						}
						$like_array = [];
						foreach ($value as $index => $item) {
							$item = strval($item);

							if (!preg_match('/(\[.+\]|_|%.+|.+%)/', $item)) {
								$item = "'%" . $item . "%'";
							}
							$like_array[] = $field . ($operator === '!~' ? ' NOT' : '') . ' LIKE ' . $item;
						}
						$where_array[] = '(' . implode($connector, $like_array) . ')';
					}
					if (in_array($operator, ['>', '>=', '<', '<='])) {
						$condition = $field . ' ' . $operator . ' ';
						if (is_numeric($value)) {
							$condition .= $value;
						} else {
							$condition .= $value;
						}
						$where_array[] = $condition;
					}
				} else {
					switch ($type) {
						case 'NULL':
							$where_array[] = $field . ' IS NULL';
							break;

						case 'array':
							$where_array[] = $field . ' IN (' . implode(',', $value) . ')';
							break;
						case 'integer':
						case 'double':
							$where_array[] = $field . ' = ' . $value;
							break;
						case 'string':
							$where_array[] = $field . ' = "' . $value . '"';
							break;
					}
				}
//				}
			}
		}
		$where_sql = implode($rel . ' ', $where_array);
		return $where_sql;
	}

	private function _parseField()
	{
		$field_sql = '';
		if (!empty($this->_data_array['field'])) {
			if (is_array($this->_data_array['field'])) {
				$field_sql .= implode(', ', $this->_data_array['field']);
			} else if (is_string($this->_data_array['field'])) {
				$field_sql .= $this->_data_array['field'];
			}
		}
		return $field_sql;
	}

	private function _parseOrder()
	{
		$field_sql = '';
		if (!empty($this->_data_array['order'])) {
			$field_sql .= ' ORDER BY ' . $this->_data_array['order'];
		}
		return $field_sql;
	}

	private function _parseTable()
	{
		if (!empty($this->real_tabale_name)) {
			$real_tabale_name = $this->real_tabale_name;
		} else if (!empty($this->_data_array['table'])) {
			$real_tabale_name = (!empty($this->db_name) ? $this->db_name . '.' : '') . $this->_tabale_prefix . strtolower($this->_data_array['table']);
		} else {
			$real_tabale_name = null;
		}
		$this->real_tabale_name = $real_tabale_name;
		return $real_tabale_name;
	}

	public function getLastSql()
	{

		return $this->_sql;
	}

	public function __call($method, $args)
	{
		$methods = ['table', 'where', 'order', 'comment', 'having', 'group', 'field'];
		if (in_array(strtolower($method), $methods)) {
			$this->_data_array[strtolower($method)] = $args[0];
			return $this;
//			//暂时先不做了
//		} else if (in_array(strtolower($method), ['count', 'sum', 'min', 'max', 'avg'])) {
//			// 统计查询的实现
//			$field = isset($args[0]) ? $args[0] : '*';
//			$method_sql = strtoupper($method) . '(' . $field . ') AS like_' . $method;
//			$this->_sql_array['field'] = $method_sql . $this->_sql_array[strtolower($method)];
		} else {
			throw new \Exception('调用方法不存在');//待完善
		}
	}

	private function _parseJoin()
	{
		$join_sql = '';
		if (!empty($this->_data_array['join'])) {
			$join_sql .= $this->_data_array['join'];
		}
		return $join_sql;
	}

	private function _parseLimit()
	{
		$limit_sql = '';
		if (!empty($this->_data_array['limit'])) {
			$limit_sql .= 'LIMIT ' . $this->_data_array['limit'];
		}
		return $limit_sql;
	}

	private function _parseGroup()
	{
		$group_sql = '';
		if (!empty($this->_data_array['group'])) {
			$group_sql .= ' GROUP BY ' . $this->_data_array['group'];
		}
		return $group_sql;
	}

	private function _parseHaving()
	{
		$having_sql = '';
		if (!empty($this->_data_array['having'])) {
			$having_sql .= ' HAVING ' . $this->_data_array['having'];
		}
		return $having_sql;
	}

	private function _parseComment()
	{
		$comment_sql = '';
		if (!empty($this->_data_array['comment'])) {
			$comment_sql .= '/* ' . $this->_data_array['comment'] . ' */';
		}
		return $comment_sql;
	}

	private function _parseAll()
	{
		$selectSql = 'SELECT %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %COMMENT%';
		$sql = str_replace(
			[
				'%TABLE%',
				'%FIELD%',
				'%JOIN%',
				'%WHERE%',
				'%GROUP%',
				'%HAVING%',
				'%ORDER%',
				'%LIMIT%',
				'%COMMENT%'
			],
			[
				$this->_parseTable(),
				$this->_parseField(),
				$this->_parseJoin(),
				$this->_parseWhere(),
				$this->_parseGroup(),
				$this->_parseHaving(),
				$this->_parseOrder(),
				$this->_parseLimit(),
				$this->_parseComment()
			], $selectSql);
		$this->_sql = $sql;
		return $sql;
	}

	public function addAll($datas = [])
	{
		if (!isset($datas[0])) {
			$datas = [$datas];
		}
		$fields = [];
		$values = [];
		foreach ($datas as $data) {
			$line_values = [];
			foreach ($data as $key => $value) {
				if (!in_array($key, $fields)) {
					$fields[] = $key;
					$line_values[] = $this->_addQuote($value);
				}
			}
			$values[] = ' (' . implode(', ', $line_values) . ')';
		}
		$this->_parseTable();
		$sql = 'INSERT INTO ' . $this->real_tabale_name . ' (' . implode(', ', $fields) . ') VALUES ' . implode(', ', $values);
		$result = $this->execu($sql);
	}
}