<?php

namespace WebChemistry\ThePay\DI;

use Nette\DI\CompilerExtension;

class ThePayExtension extends CompilerExtension {

	/** @var array */
	public $defaultValues = array(
		'merchantId' => 1,
		'accountId' => 1,
		'demoGateUrl' => 'https://www.thepay.cz/demo-gate/',
		'gateUrl' => 'https://www.thepay.cz/gate/',
		'wsdl' => 'https://www.thepay.cz/gate/api/api.wsdl',
		'wsdlDemo' => 'https://www.thepay.cz/demo-gate/api/api-demo.wsdl',
		'notificationTest' => 'https://www.thepay.cz/demo-gate/testNotif.php',
		'password' => 'my$up3rsecr3tp4$$word',
		'writer' => NULL
	);

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaultValues);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('thepay'))
			->setClass('WebChemistry\ThePay\ThePay', array($config));
	}

}
