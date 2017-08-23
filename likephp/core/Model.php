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
	private $_db = [];//数据库实例
	private $_run_sql = '';//运行的sql

	public function __construct($model_name = '', $table_prefix = '', $connection_id = null)
	{

	}

	public function getDb($connection_id)
	{
		if (isset($this->_db[$connection_id])) {
			$db = $this->_db[$connection_id];
		} else {
			$db_config = Config::get('db');
			$dbhost = $db_config['dbhost'];
			$dbport = $db_config['dbport'];
			$dbuser = $db_config['dbuser'];
			$dbpass = $db_config['dbpass'];
			$dbname = $db_config['dbname'];
			$connection_id = md5($dbhost . $dbport . $dbuser . $dbpass . $dbname);
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
			$this->_db[$connection_id] = $db;
		}
		return $db;
	}

	public function where($where_sql = '')
	{
		if (!empty($where_sql)) {
			$this->_run_sql .= ' WHERE ' . $where_sql;
		}
		return $this;
	}
}