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
	private $_tabale_prefix;//表前缀
	private $_db;//数据库实例
	private $_db_objs = [];//数据库实例
	//存放sql每段数据
	private $_data = [
		'where' => null,
		'field' => null,

	];
	//存放sql每段语句
	private $_sql = [
		'where' => null,
		'field' => null,

	];

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
	public function getRealTableName()
	{
		if (empty($this->real_tabale_name)) {
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
	private function _getTablePrefix($table_prefix)
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
	private function _getModelName($model_name)
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
	private function _getModelNameByClassName()
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
	public function getDb($db_config = null)
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

	/**
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $where
	 * @return $this
	 */
	public function where($where)
	{
		if (!empty($where)) {
			$this->_data['where'] = $where;
		}
		return $this;
	}

	public function field($field)
	{
		if (!empty($field)) {
			$this->_data['field'] = $field;
		}
		return $this;
	}

	public function select()
	{
		$sql = 'SELECT ';
		$sql .= $this->_parseField();
		$sql .= ' FROM ' . $this->real_tabale_name;
		$sql .= $this->_parseWhere();
		$sth = $this->_db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
		$sth->execute(array(':calories' => 150, ':colour' => 'red'));
		$result = $sth->fetchAll();
		return $result;
	}

	private function _parseWhere()
	{
		$where_sql = '';
		if (!empty($this->_data['where'])) {
			$where_sql .= ' WHERE ';
			if (is_array($this->_data['where'])) {

			} else if (is_string($this->_data['where'])) {
				$where_sql .= $this->_data['where'];
			}
		}
		return $where_sql;
	}

	private function _parseField()
	{
		$field_sql = '';
		if (!empty($this->_data['field'])) {
			if (is_array($this->_data['field'])) {
				$field_sql .= implode(', ', $this->_data['field']);
			} else if (is_string($this->_data['field'])) {
				$field_sql .= $this->_data['field'];
			}
		}
		return $field_sql;
	}

	public function __call($method, $args)
	{
		$methods = ['strict', 'order', 'alias', 'having', 'group', 'distinct'];
		if (in_array(strtolower($method), $methods)) {
			$this->_data[strtolower($method)] = $args[0];
			return $this;
		} else if (in_array(strtolower($method), ['count', 'sum', 'min', 'max', 'avg'])) {
			// 统计查询的实现
			$field = isset($args[0]) ? $args[0] : '*';
			$method_sql = strtoupper($method) . '(' . $field . ') AS like_' . $method;
			$this->_sql['field'] = $method_sql . $this->_sql[strtolower($method)];
		} else {
			throw new \Exception('调用方法不存在');//待完善
		}
	}

//	public function parseSql($sql, $options = array())
//	{
//		$selectSql = 'SELECT %DISTINCT% %FIELD% FROM %TABLE%%FORCE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%LOCK%%COMMENT%';
//		$sql = str_replace(
//			['%TABLE%', '%DISTINCT%', '%FIELD%', '%JOIN%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%', '%UNION%', '%LOCK%', '%COMMENT%', '%FORCE%'],
//			[
//				$this->real_tabale_name,
//				$this->parseDistinct(isset($options['distinct']) ? $options['distinct'] : false),
//				$this->parseField(!empty($options['field']) ? $options['field'] : '*'),
//				$this->parseJoin(!empty($options['join']) ? $options['join'] : ''),
//				$this->parseWhere(!empty($options['where']) ? $options['where'] : ''),
//				$this->parseGroup(!empty($options['group']) ? $options['group'] : ''),
//				$this->parseHaving(!empty($options['having']) ? $options['having'] : ''),
//				$this->parseOrder(!empty($options['order']) ? $options['order'] : ''),
//				$this->parseLimit(!empty($options['limit']) ? $options['limit'] : ''),
//				$this->parseUnion(!empty($options['union']) ? $options['union'] : ''),
//				$this->parseLock(isset($options['lock']) ? $options['lock'] : false),
//				$this->parseComment(!empty($options['comment']) ? $options['comment'] : ''),
//				$this->parseForce(!empty($options['force']) ? $options['force'] : ''),
//			], $selectSql);
//		return $sql;
//	}
}