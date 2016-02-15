<?php


class ExtensionTest extends \Codeception\TestCase\Test {

	public function testExtension() {
		$compiler = new \Nette\DI\Compiler();
		$compiler->addExtension('thepay', new \WebChemistry\ThePay\DI\ThePayExtension());
		$compiler->compile();
	}

}
