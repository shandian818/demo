<?php
/**
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
	static private $_config;

	static public function set($name, $value=null)
	{
		$config_data = self::_getConfigFromCache();

		$configArray = self::_updateConfig($name, $value, $config_data);

		$set_status = self::_setConfigToCache($configArray);

		return $set_status;
	}

	static public function get($name = '')
	{
		$config_data = self::_getConfigFromCache();
		if (false === $config_data) {
			//原值为false
			return false;
		} elseif (is_null($config_data)) {
			//原值为null
			$value = null;
		} else {
			//原值存在
			//从session获取值
			$value = self::_getConfigValueByName($name, $config_data);
		}
		return $value;
	}

	static private function _setConfigToCache($config_array)
	{
		return self::$_config = serialize($config_array);

	}

	static private function _getConfigFromCache()
	{
		return unserialize(self::$_config);
	}

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