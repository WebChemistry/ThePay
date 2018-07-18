<?php

namespace WebChemistry\ThePay;

use Nette\Http\IRequest;

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
	 * @param IRequest $request
	 */
	public function __construct(\TpMerchantConfig $config, IWriter $writer = NULL, IRequest $request = NULL) {
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
	 * @param float|null $minValue
	 * @param array $needs
	 * @throws ThePayThrowable
	 * @return array
	 */
	public function fastCheckArray($minValue = null, array $needs = []) {
		$this->fastCheck($minValue);

		return $this->getMerchantDataArray($needs);
	}

	/**
	 * @param float|null $minValue
	 * @throws ThePayThrowable
	 * @return array|string
	 */
	public function fastCheckString($minValue = null) {
		$this->fastCheck($minValue);

		return $this->getMerchantData();
	}

	/**
	 * @param float|null $minValue
	 * @throws ThePayThrowable
	 * @return void
	 */
	protected function fastCheck($minValue = null) {
		$this->verifySignature();
		$this->checkSuccess();
		$this->checkValue($minValue);
	}

	/**
	 * @throws InvalidReceivedDataException
	 */
	public function checkSuccess(): void {
		if (!$this->isSuccess()) {
			throw new InvalidReceivedDataException('Payment is not successful.', InvalidReceivedDataException::NOT_SUCCESS);
		}
	}

	/**
	 * @param float $minValue
	 * @throws InvalidReceivedDataException
	 */
	public function checkValue($minValue) {
		// precision 2 => the pay allows only 2 decimal places
		if (round($this->getValue(), 2) < round($minValue, 2)) {
			throw new InvalidReceivedDataException('Value is below.', InvalidReceivedDataException::VALUE_BELOW);
		}
	}

	/**
	 * @param bool $throwException
	 * @throws ThePayException
	 * @return bool
	 */
	public function verifySignature($throwException = TRUE) {
		if ($this->error) {
			if ($throwException) {
				throw new ThePayException($this->error->getMessage(), $this->error->getCode());
			}

			return FALSE;
		}
		try {
			parent::verifySignature();
		} catch (\TpInvalidSignatureException $e) {
			if ($throwException) {
				throw new ThePayException('Invalid signature.');
			}

			return FALSE;
		}
		if (!is_numeric(parent::getValue())) {
			if ($throwException) {
				throw new ThePayException('Price is not numeric.');
			}

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @return \TpDataApiPayment
	 * @throws \TpSoapException
	 */
	public function getRemotePayment() {
		if (!$this->remotePayment) {
			$this->remotePayment = (new Api($this->config))->getPayment($this->getPaymentId());
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
	 * @param array $needs
	 * @return array
	 * @throws InvalidMerchantDataException
	 */
	public function getMerchantDataArray(array $needs = []) {
		$data = @unserialize(parent::getMerchantData());
		if ($data === false) {
			throw new InvalidMerchantDataException('Merchant data is not array.');
		}
		$missing = [];
		foreach ($needs as $need) {
			if (!isset($data[$need])) {
				$missing[] = $need;
			}
		}
		if ($missing) {
			InvalidMerchantDataException::missingItems($missing);
		}

		return $data;
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
