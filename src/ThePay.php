<?php

namespace WebChemistry\ThePay;

use Nette\Http\Request;

class ThePay {

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

	/**
	 * @param array $config
	 * @param Request $request
	 */
	public function __construct(array $config, Request $request = NULL) {
		$this->config = new \TpMerchantConfig;
		$this->isTest = $config['accountId'] === 1 && $config['merchantId'] === 1;

		if ($config['writer']) {
			if (is_object($config['writer'])) {
				$this->writer = $config['writer'];
			} else {
				$this->writer = new $config['writer'];
			}
		}

		$this->config->accountId = $config['accountId'];
		$this->config->gateUrl = $this->isTest ? $config['demoGateUrl'] : $config['gateUrl'];
		$this->config->webServicesWsdl = $this->isTest ? $config['wsdlDemo'] : $config['wsdl'];
		$this->config->merchantId = $config['merchantId'];
		$this->config->password = $config['password'];
		$this->request = $request;
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

}
