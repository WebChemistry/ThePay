<?php

namespace WebChemistry\ThePay\DI;

use Nette\DI\CompilerExtension;
use WebChemistry\ThePay\ThePay;

class ThePayExtension extends CompilerExtension {

	/** @var array */
	public $defaultValues = [
		'merchantId' => 1,
		'accountId' => 1,
		'password' => 'my$up3rsecr3tp4$$word',
		'dataApiPassword' => 'my$up3rsecr3tp4$$word',
		'writer' => NULL
	];

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaultValues);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('thepay'))
			->setClass(ThePay::class, [$config]);
	}

}
