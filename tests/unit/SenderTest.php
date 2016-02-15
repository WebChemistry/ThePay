<?php

class SenderTest extends \Codeception\TestCase\Test {

	/** @var \WebChemistry\ThePay\ThePay */
	protected $thePay;

	/** @var \UnitTester */
	protected $tester;

	protected function _before() {
		$extension = new \WebChemistry\ThePay\DI\ThePayExtension();
		$this->thePay = new \WebChemistry\ThePay\ThePay($extension->defaultValues);
	}

	public function testPrice() {
		$this->tester->assertExceptionThrown('WebChemistry\ThePay\InvalidArgumentException', function () {
			$this->thePay->createSender('test');
		});
		$this->tester->assertExceptionThrown('WebChemistry\ThePay\InvalidArgumentException', function () {
			$this->thePay->createSender(['test']);
		});
		$this->assertSame(400.00, $this->thePay->createSender(400)->getValue());
		$this->assertSame(400.00, $this->thePay->createSender('400')->getValue());
	}

	public function testData() {
		// Array
		$sender = $this->thePay->createSender(100);
		$data = ['customer' => 1];
		$sender->setMerchantData($data);
		$this->assertSame($data, $sender->getMerchantData());

		// String
		$sender->setMerchantData('data');
		$this->assertSame('data', $sender->getMerchantData());

		// Traversable
		$arrayAccess = \Nette\Utils\ArrayHash::from($data);
		$sender->setMerchantData($arrayAccess);
		$this->assertSame($data, $sender->getMerchantData());
	}

	public function testRender() {
		$this->assertNotEmpty($this->thePay->createSender(400)->render());
	}

}