<?php

namespace WebChemistry\ThePay;

class InvalidMerchantDataException extends InvalidReceivedDataException {

	/** @var array */
	protected $items = [];

	public static function missingItems(array $items) {
		$obj = new static('Merchant data missing: ' . implode(', ', $items) . '.');
		$obj->items = $items;

		throw $obj;
	}

	//

	/**
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}

}
