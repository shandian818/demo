<?php
/**
 * 配置类
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/5/4
 * Time: 11:02
 */

namespace likephp\core;


class Config
{
	static private $_config;//配置信息（目前存在静态变量）

	/**
	 * 设置配置信息
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $name
	 * @param null $value
	 * @return bool
	 */
	static public function set($name, $value = null)
	{
		$config_data = self::_getConfigFromCache();
		$configArray = self::_updateConfig($name, $value, $config_data);
		$set_status = self::_setConfigToCache($configArray);
		return $set_status;
	}

	/**
	 * 获取配置信息
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param string $name 配置项（可英文句号连接）
	 * @param null $default_value 默认值
	 * @return null
	 */
	static public function get($name = '', $default_value = null)
	{
		$config_data = self::_getConfigFromCache();
		$value = self::_getConfigValueByName($name, $config_data);
		return !is_null($value) ? $value : $default_value;
	}

	/**
	 * 配置写入缓存（目前存在静态变量）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $config_array
	 * @return bool
	 */
	static private function _setConfigToCache($config_array)
	{
		return (self::$_config = serialize($config_array)) ? true : false;

	}

	/**
	 * 缓存中读取配置（目前存在静态变量）
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @return mixed
	 */
	static private function _getConfigFromCache()
	{
		return unserialize(self::$_config);
	}

	/**
	 * 更新配置信息的变量
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $name
	 * @param $value
	 * @param $config_data
	 * @return array
	 */
	static private function _updateConfig($name, $value, $config_data)
	{
		$configArray = [];
		if ('' === $name) {
			$configArray = $value;
		} elseif (is_string($name)) {
			$nameArr = explode('.', $name);
			$item = &$config_data;
			for ($i = 0; $i < count($nameArr) - 1; $i++) {
				if (!key_exists($nameArr[$i], $item) || (key_exists($nameArr[$i], $item) && !is_array($item[$nameArr[$i]]))) {
					$item[$nameArr[$i]] = [];
				}
				$item = &$item[$nameArr[$i]];
			}
			if (is_null($value)) {
				//value为null，清除key为name的项
				unset($item[$nameArr[$i]]);
			} else {
				$item[$nameArr[$i]] = $value;
			}
			$configArray = $config_data;
		} else if (is_array($name)) {
			$configArray = $name;
		}
		return $configArray;
	}

	/**
	 * 根据name获取配置的值
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $name
	 * @param $config_data
	 * @return null
	 */
	static private function _getConfigValueByName($name, $config_data)
	{
		$value = null;//默认为null
		if ('' === $name) {
			$value = $config_data;
		} elseif (is_string($name)) {
			$nameArr = explode('.', $name);
			$item = $config_data;
			for ($i = 0; $i < count($nameArr); $i++) {
				if (key_exists($nameArr[$i], $item)) {
					$item = &$item[$nameArr[$i]];
				} else {
					$item = null;
				}
			}
			$value = $item;
		}
		return $value;
	}
}