<?php

namespace WebChemistry\ThePay;

class Api {

	/** @var \TpMerchantConfig */
	private $config;

	/**
	 * @param \TpMerchantConfig $config
	 */
	public function __construct(\TpMerchantConfig $config) {
		$this->config = $config;
	}

	/**
	 * @param bool $onlyActive
	 * @return \TpDataApiGetPaymentMethodsResponse
	 * @throws \TpSoapException
	 */
	public function getPaymentMethods($onlyActive = TRUE) {
		return \TpDataApiHelper::getPaymentMethods($this->config, $onlyActive);
	}

	/**
	 * @param int $paymentId
	 * @return null|\TpDataApiPayment
	 * @throws \TpSoapException
	 */
	public function getPayment($paymentId) {
		$response = \TpDataApiHelper::getPayment($this->config, $paymentId);

		return $response->getPayment();
	}

	/**
	 * @param int $paymentId
	 * @return null|\TpDataApiPaymentInfo
	 * @throws \TpSoapException
	 */
	public function getPaymentInstructions($paymentId) {
		$response = \TpDataApiHelper::getPaymentInstructions($this->config, $paymentId);

		return $response->getPaymentInfo();
	}

	/**
	 * @param int $paymentId
	 * @return int|null
	 * @throws \TpSoapException
	 */
	public function getPaymentState($paymentId) {
		$response = \TpDataApiHelper::getPaymentState($this->config, $paymentId);

		return $response->getState();
	}

	/**
	 * @param \TpDataApiGetPaymentsSearchParams|NULL $search
	 * @param int $itemsPerPage
	 * @param int $page
	 * @param string $orderBy
	 * @param string $orderByType
	 * @return \TpDataApiGetPaymentsResponse
	 * @throws \TpSoapException
	 */
	public function getPayments($itemsPerPage = NULL, $page = NULL, \TpDataApiGetPaymentsSearchParams $search = NULL, $orderBy = NULL, $orderByType = NULL) {
		$pagination = NULL;
		$order = NULL;
		if ($itemsPerPage) {
			$pagination = new \TpDataApiPaginationRequest();
			$pagination->setItemsOnPage($itemsPerPage);
			$pagination->setPage($page);
		}
		if ($orderBy) {
			$order = new \TpDataApiOrdering();
			$order->setOrderBy($orderBy);
			$order->setOrderHow($orderByType ? : 'DESC');
		}

		return \TpDataApiHelper::getPayments($this->config, $search, $pagination, $order);
	}

}
