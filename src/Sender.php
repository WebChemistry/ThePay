<?php

namespace WebChemistry\ThePay;

class Sender extends \TpPayment {

	/**
	 * @param \TpMerchantConfig $config
	 * @param float $price
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
		} catch (\TpInvalidArgumentException $e) {
			throw new InvalidArgumentException('Price must be float.');
		}

		return $this;
	}

	/**
	 * @param string|array|\Traversable $data
	 * @return self
	 */
	public function setCustomerData($data) {
		parent::setCustomerData(Helper::setData($data));

		return $this;
	}

	/**
	 * @param string|array|\Traversable $data
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
	 * @return \TpIframeMerchantHelper
	 */
	public function createIframeRenderer() {
		return new \TpIframeMerchantHelper($this);
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
