<?php

namespace WebChemistry\ThePay;

use Nette\Http\Request;

class ThePay {

	const WSDL = 'https://www.thepay.cz/gate/api/gate-api.wsdl';
	const WSDL_API = 'https://www.thepay.cz/gate/api/data.wsdl';
	const GATE = 'https://www.thepay.cz/gate/';
	const NOTIFICATION_TEST = 'https://www.thepay.cz/demo-gate/testNotif.php';

	/** @var \TpMerchantConfig */
	private $config;

	/** @var bool */
	private $isTest = FALSE;

	/** @var IWriter */
	private $writer;

	/** @var Receiver */
	private $receiver;

	/** @var Request */
	private $request;

	/** @var Api */
	private $api;

	/**
	 * @param array $config
	 * @param Request $request
	 */
	public function __construct(array $config = [], Request $request = NULL) {
		$this->config = new \TpMerchantConfig;
		if (isset($config['password']) && !isset($config['dataApiPassword'])) {
			$config['dataApiPassword'] = $config['password'];
		}
		foreach ($config as $name => $value) {
			$this->config->$name = $value;
		}

		$this->isTest = $this->config->merchantId == 1;
		if (!$this->isTest) {
			$this->config->gateUrl = self::GATE;
			$this->config->webServicesWsdl = self::WSDL;
			$this->config->dataWebServicesWsdl = self::WSDL_API;
		}

		if (isset($config['writer'])) {
			$this->writer = is_object($config['writer']) ? $config['writer'] : new $config['writer'];
		}
		
		$this->request = $request;
		$this->api = new Api($this->config);
	}

	/**
	 * @return bool
	 */
	public function isTest() {
		return $this->isTest;
	}

	/**
	 * @return Receiver
	 */
	public function getReceiver() {
		if (!$this->receiver) {
			$this->receiver = new Receiver($this->config, $this->writer, $this->request);
		}

		return $this->receiver;
	}

	/**
	 * @param float $price
	 * @return Sender
	 */
	public function createSender($price) {
		return new Sender($this->config, $price);
	}

	/**
	 * @param string $merchantData
	 * @param string $description
	 * @param string $returnUrl
	 * @return Permanent
	 * @throws \TpException
	 */
	public function createPermanent($merchantData, $description, $returnUrl) {
		$payment = new \TpPermanentPayment($this->config, $merchantData, $description, $returnUrl);

		return new Permanent(\TpPermanentPaymentHelper::createPermanentPayment($payment));
	}

	/**
	 * @param string $merchantData
	 * @param string $description
	 * @param string $returnUrl
	 * @return Permanent
	 * @throws \TpException
	 */
	public function getPermanent($merchantData, $description, $returnUrl) {
		$payment = new \TpPermanentPayment($this->config, $merchantData, $description, $returnUrl);

		return \TpPermanentPaymentHelper::getPermanentPayment($payment);
	}

	/**
	 * @return Api
	 */
	public function getApi() {
		return $this->api;
	}

}
