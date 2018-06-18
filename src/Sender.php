<?php

namespace WebChemistry\ThePay;

use Nette\Utils\Strings;

class Sender extends \TpPayment {

	/**
	 * @param \TpMerchantConfig $config
	 * @param float $price
	 * @throws InvalidArgumentException
	 */
	public function __construct(\TpMerchantConfig $config, $price) {
		parent::__construct($config);

		$this->setValue($price);
	}

	/**
	 * @param float $float
	 * @return self
	 * @throws InvalidArgumentException
	 */
	public function setValue($float) {
		try {
			parent::setValue($float);
		} catch (\TpInvalidParameterException $e) {
			throw new InvalidArgumentException('Price must be float.');
		}

		return $this;
	}

	/**
	 * @param string $returnUrl
	 * @throws InvalidArgumentException
	 */
	public function setReturnUrl($returnUrl) {
		if (Strings::startsWith($returnUrl, 'http')) {
			throw new InvalidArgumentException('Return url must be absolute.');
		}
		parent::setReturnUrl($returnUrl);
	}

	/**
	 * @param array $data
	 */
	public function setMerchantDataArray(array $data) {
		$this->setMerchantData(serialize($data));
	}

	/**
	 * @param string $data
	 * @return self
	 */
	public function setMerchantData($data) {
		parent::setMerchantData(Helper::setData($data));

		return $this;
	}

	/**
	 * @return array|string
	 */
	public function getMerchantData() {
		return Helper::getData(parent::getMerchantData());
	}

	/**
	 * @return \TpDivMerchantHelper
	 */
	public function createDivRenderer() {
		return new \TpDivMerchantHelper($this);
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @param bool $showIcon
	 * @param bool $disablePopupCss
	 * @return \TpRadioMerchantHelper
	 */
	public function createRadioRenderer($name = NULL, $value = NULL, $showIcon = TRUE, $disablePopupCss = FALSE) {
		return new \TpRadioMerchantHelper($this->config, $name, $value, $showIcon, $disablePopupCss);
	}

	/**
	 * @return \TpButtonMerchantHelper
	 */
	public function createButtonRenderer() {
		return new \TpButtonMerchantHelper($this);
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->createDivRenderer()->render();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

}
