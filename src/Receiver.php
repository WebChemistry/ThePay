<?php

namespace WebChemistry\ThePay;

use Nette\Http\Request;

class Receiver extends \TpReturnedPayment {

	/** @var IWriter */
	private $writer;

	/** @var \TpMissingParameterException */
	private $error;

	/** @var \TpDataApiPayment */
	private $remotePayment;

	/**
	 * @param \TpMerchantConfig $config
	 * @param IWriter $writer
	 * @param Request $request
	 */
	public function __construct(\TpMerchantConfig $config, IWriter $writer = NULL, Request $request = NULL) {
		try {
			if ($request) {
				parent::__construct($config, $request->getUrl()->getQueryParameters());
			} else {
				parent::__construct($config);
			}
		} catch (\TpMissingParameterException $e) {
			$this->error = $e;
		}
		$this->writer = $writer;
		if ($this->writer) {
			$this->writer->setReceiver($this);
		}
	}

	/**
	 * @return float
	 */
	public function getValue() {
		return (float) parent::getValue();
	}

	/**
	 * @return IWriter|null
	 */
	public function getWriter() {
		return $this->writer;
	}

	/**
	 * @param bool $throwException
	 * @throws Exception
	 * @return bool
	 */
	public function verify($throwException = TRUE) {
		if ($this->error) {
			if ($throwException) {
				throw new Exception($this->error->getMessage(), $this->error->getCode());
			}

			return FALSE;
		}
		try {
			$this->verifySignature();
		} catch (\TpInvalidSignatureException $e) {
			if ($throwException) {
				throw new Exception('Invalid signature.');
			}

			return FALSE;
		}
		if (!is_numeric(parent::getValue())) {
			if ($throwException) {
				throw new Exception('Price is not numeric.');
			}

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @return \TpDataApiPayment
	 */
	public function getRemotePayment() {
		if (!$this->remotePayment) {
			$this->remotePayment = \TpDataApiHelper::getPayment($this->config, $this->getPaymentId())->getPayment();
		}

		return $this->remotePayment;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return (int) parent::getStatus();
	}

	/**
	 * @return string|array
	 */
	public function getMerchantData() {
		return Helper::getData(parent::getMerchantData());
	}

	/**
	 * @return string|array
	 */
	public function getCustomerData() {
		return Helper::getData(parent::getCustomerData());
	}

	/**
	 * @return int
	 */
	public function getPaymentId() {
		return (int) parent::getPaymentId();
	}

	/**
	 * @return int
	 */
	public function getMethodId() {
		return (int) parent::getMethodId();
	}

	/**
	 * @return bool
	 */
	public function isSuccess() {
		return $this->getStatus() === self::STATUS_OK;
	}

	/**
	 * @return bool
	 */
	public function isWaiting() {
		return $this->getStatus() === self::STATUS_WAITING;
	}

	/**
	 * @return bool
	 */
	public function isCanceled() {
		return $this->getStatus() === self::STATUS_CANCELED;
	}

	/**
	 * @return bool
	 */
	public function isError() {
		return $this->getStatus() === self::STATUS_ERROR;
	}

	/**
	 * @return bool
	 */
	public function isUnderPaid() {
		return $this->getStatus() === self::STATUS_UNDERPAID;
	}

}
