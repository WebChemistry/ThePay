<?php

namespace WebChemistry\ThePay\DI;

use Nette\DI\CompilerExtension;
use WebChemistry\ThePay\ThePay;

class ThePayExtension extends CompilerExtension {

	/** @var array */
	public $defaultValues = [
		'merchantId' => 1,
		'accountId' => 1,
		'demoGateUrl' => 'https://www.thepay.cz/demo-gate/',
		'gateUrl' => 'https://www.thepay.cz/gate/',
		'wsdl' => 'https://www.thepay.cz/gate/api/gate-api.wsdl',
		'wsdlDemo' => 'https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl',
		'notificationTest' => 'https://www.thepay.cz/demo-gate/testNotif.php',
		'password' => 'my$up3rsecr3tp4$$word',
		'writer' => NULL
	];

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaultValues);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('thepay'))
			->setClass(ThePay::class, [$config]);
	}

}
