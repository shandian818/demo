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

	public function __construct($model_name = '', $table_prefix = '', $db_config = null)
	{
		$this->_db = $this->getDb($db_config);


	}

	public function getRealTableName()
	{
		if (empty($this->real_tabale_name)) {
			$tableName = !empty($this->_tabale_prefix) ? $this->_tabale_prefix : '';
			if (!empty($this->tableName)) {
				$tableName .= $this->tableName;
			} else {
				$tableName .= parse_name($this->model_name);
			}
			$this->trueTableName = strtolower($tableName);
		}
		return (!empty($this->dbName) ? $this->dbName . '.' : '') . $this->trueTableName;
	}

	private function _getTablePrefix($table_prefix)
	{
		if (!empty($table_prefix)) {
			$this->_tabale_prefix = $table_prefix;
		}
	}

	private function _getModelName($model_name)
	{
		if (empty($model_name)) {
			$this->model_name = $this->_getModelNameByClassName();
		} else {
			$this->model_name = $model_name;
		}
	}

	private function _getModelNameByClassName()
	{
		$name = substr(get_class($this), 0, -strlen('Model'));
		if ($pos = strrpos($name, '\\')) {
			$name = substr($name, $pos + 1);
		}
		return $name;
	}

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
		return $db;
	}

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
		$sql .= $this->_parseWhere();
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

	public function __call($name, $arguments)
	{
		// TODO: Implement __call() method.
		dump($name);
		dump($arguments);
		return $this;
	}

//	public function select($fields = '*')
//	{
//
//	}
}