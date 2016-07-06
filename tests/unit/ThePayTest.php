<?php

class ThePayTest extends \Codeception\TestCase\Test {

	/** @var \WebChemistry\ThePay\ThePay */
	protected $thePay;

	protected function _before() {
		$this->thePay = new \WebChemistry\ThePay\ThePay();
	}

	public function testMethods() {
		$this->assertInstanceOf(\WebChemistry\ThePay\Sender::class, $this->thePay->createSender(500));
		$this->assertInstanceOf(\WebChemistry\ThePay\Receiver::class, $this->thePay->getReceiver());
		$this->assertInstanceOf(\WebChemistry\ThePay\Api::class, $this->thePay->getApi());

		$this->assertNotSame($this->thePay->createSender(500.00), $this->thePay->createSender(500.00));
		$this->assertSame($this->thePay->getReceiver(), $this->thePay->getReceiver());
	}

	public function testIsTest() {
		$this->assertTrue($this->thePay->isTest());
	}

}
