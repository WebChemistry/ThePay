<?php

namespace WebChemistry\ThePay;

class Helper {

	/**
	 * @param array|string|\Traversable $data
	 * @return string
	 */
	public static function setData($data) {
		if ($data instanceof \Traversable) {
			$data = iterator_to_array($data);
		}
		if (is_array($data)) {
			$data = serialize($data);
		}

		return $data;
	}

	/**
	 * @param string $data
	 * @return array|string
	 */
	public static function getData($data) {
		$unserialize = @unserialize($data);

		return $unserialize !== FALSE ? $unserialize : $data;
	}

}
