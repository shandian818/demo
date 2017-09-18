<?php
/**
 * Created by PhpStorm.
 * User: jiangxijun
 * Email: jiang818@qq.com
 * Qq: 263088049
 * Date: 2017/9/13
 * Time: 9:48
 */

namespace tools;


class Curl
{

	/**
	 * 异步自定义curl
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param array $curl_data
	 * @param string $class_name 异步回调类
	 * @param string $func_name 异步回调方法
	 * @return array|bool 同步返回的结果
	 */
	static public function custom($curl_data = [], $class_name = '', $func_name = '')
	{
		if (empty($curl_data)) {
			return false;
		}
		$curl_list = [];
		$response = [];
		if (!isset($curl_data[0])) {
			$curl_list[] = $curl_data;
		} else {
			$curl_list = $curl_data;
		}
		$ch_params = [];
		$chs = curl_multi_init();
		foreach ($curl_list as $curl_info) {
			if (empty($curl_info['url'])) {
				continue;
			}
			$ch = self::_start_curl($curl_info);
			$ch_sign = strval($ch);
			$ch_params[$ch_sign] = $curl_info;//用来存储每个请求
			curl_multi_add_handle($chs, $ch);
		}
		do {
			if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
				if ($status != CURLM_OK) {
					break;
				}
				while ($done = curl_multi_info_read($chs)) {
					$info = curl_getinfo($done["handle"]);
					$error = curl_error($done["handle"]);
					$result = curl_multi_getcontent($done["handle"]);
					$sign = strval($done["handle"]);
					$params = $ch_params[$sign];
					$return = [
						'params' => $params,
						'result' => $result,
						'info' => $info,
						'error' => $error
					];
					if (!empty($class_name) && !empty($func_name)) {
						@call_user_func([$class_name, $func_name], $return);
					}
					$response[$sign] = $return;
					curl_multi_remove_handle($chs, $done['handle']);
					curl_close($done['handle']);
					if ($active > 0) {
						curl_multi_select($chs, 1); //此处会导致阻塞大概1秒。
					}
				}
			}
		} while ($active > 0); //还有句柄处理还在进行中
		curl_multi_close($chs);
		return $response;
	}

	/**
	 * User: jiangxijun
	 * Email: jiang818@qq.com
	 * Qq: 263088049
	 * @param $curl_info
	 * @return resource
	 */
	static public function _start_curl($curl_info)
	{
		$url = $curl_info['url'];
		$data = $curl_info['data'];
		$options = $curl_info['options'];
		$timeout = ($options['timeout'] > 0) ? $options['timeout'] : 30;
		$header = isset($options['header']) ? $options['header'] : 0;
		if (is_null($data) && empty($options['type'])) {
			$type = 'GET';
		} else if (!is_null($data) && empty($options['type'])) {
			$type = 'POST';
		} else {
			$type = strtoupper($options['type']);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, $header);
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		switch ($type) {
			case 'GET':
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case 'PUT':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
		}
		return $ch;
	}
}