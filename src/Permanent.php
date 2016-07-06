<?php

namespace WebChemistry\ThePay;

class Permanent {

	/** @var \TpPermanentPaymentResponse */
	private $response;

	/**
	 * @param \TpPermanentPaymentResponse $response
	 */
	public function __construct(\TpPermanentPaymentResponse $response) {
		$this->response = $response;
	}

	/**
	 * @return bool
	 */
	public function isOk() {
		return $this->response->getStatus() === TRUE;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->response->getErrorDescription();
	}

	/**
	 * @return \TpPermanentPaymentResponseMethod[]
	 */
	public function getMethods() {
		return $this->response->getPaymentMethods();
	}

}
