<?php

class ThePayTest extends \Codeception\TestCase\Test {

	/** @var \WebChemistry\ThePay\ThePay */
	protected $thePay;

	protected function _before() {
		$this->thePay = new \WebChemistry\ThePay\ThePay();
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
