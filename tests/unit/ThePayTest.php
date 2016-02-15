<?php

class ThePayTest extends \Codeception\TestCase\Test {

	/** @var \WebChemistry\ThePay\ThePay */
	protected $thePay;

	protected function _before() {
		$this->thePay = new \WebChemistry\ThePay\ThePay([
			'merchantId' => 1,
			'accountId' => 1,
			'demoGateUrl' => 'https://www.thepay.cz/demo-gate/',
			'gateUrl' => 'https://www.thepay.cz/gate/',
			'wsdl' => 'https://www.thepay.cz/gate/api/api.wsdl',
			'wsdlDemo' => 'https://www.thepay.cz/demo-gate/api/api-demo.wsdl',
			'notificationTest' => 'https://www.thepay.cz/demo-gate/testNotif.php',
			'password' => 'my$up3rsecr3tp4$$word',
			'writer' => NULL
		]);
	}

	public function testMethods() {
		$this->assertInstanceOf('WebChemistry\ThePay\Sender', $this->thePay->createSender(500));
		$this->assertInstanceOf('WebChemistry\ThePay\Receiver', $this->thePay->getReceiver());

		$this->assertNotSame($this->thePay->createSender(500.00), $this->thePay->createSender(500.00));
		$this->assertSame($this->thePay->getReceiver(), $this->thePay->getReceiver());
	}

	public function testIsTest() {
		$this->assertTrue($this->thePay->isTest());
	}

}
