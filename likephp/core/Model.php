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
		$sth = $this->_db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
		$sth->execute(array(':calories' => 150, ':colour' => 'red'));
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
				$where_AND = preg_grep("/^AND\s*#?$/i", $where_keys);
				$where_OR = preg_grep("/^OR\s*#?$/i", $where_keys);
				$map=[];
				if (!empty($where_AND))
				{
					$value = array_values($where_AND);
					$where_clause = ' WHERE ' . $this->dataImplode($where[ $value[ 0 ] ], $map, ' AND');
				}

				if (!empty($where_OR))
				{
					$value = array_values($where_OR);
					$where_clause = ' WHERE ' . $this->dataImplode($where[ $value[ 0 ] ], $map, ' OR');
				}
			} else if (is_string($this->_data_array['where'])) {
				$where_sql .= $this->_data_array['where'];
			}
		}
		return $where_sql;
	}

	private function dataImplode($a,$b=null,$c){

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
		} else if (in_array(strtolower($method), ['count', 'sum', 'min', 'max', 'avg'])) {
			// 统计查询的实现
			$field = isset($args[0]) ? $args[0] : '*';
			$method_sql = strtoupper($method) . '(' . $field . ') AS like_' . $method;
			$this->_sql_array['field'] = $method_sql . $this->_sql_array[strtolower($method)];
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
			$comment_sql .= '/*   ' . $this->_data_array['comment'] . '   */';
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
}