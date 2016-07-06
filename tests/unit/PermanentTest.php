<?php


class PermanentTest extends \Codeception\TestCase\Test {

	/** @var \WebChemistry\ThePay\ThePay */
	private $thePay;

	protected function setUp() {
		$this->thePay = new \WebChemistry\ThePay\ThePay();
	}

	public function testPermanent() {
		$permanent = $this->thePay->createPermanent('data', 'desc', 'localhost');

		$this->assertTrue($permanent->isOk());
		$this->assertNull($permanent->getErrorMessage());
		$this->assertTrue(is_array($permanent->getMethods()));
		foreach ($permanent->getMethods() as $method) {
			$this->assertInstanceOf(\TpPermanentPaymentResponseMethod::class, $method);
		}
	}

}
