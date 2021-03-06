<?php

namespace WebChemistry\ThePay;

interface IWriter {

	/**
	 * @param Receiver $receiver
	 * @return IWriter
	 */
	public function setReceiver(Receiver $receiver);

	/**
	 * @return bool
	 */
	public function isExists();

	/**
	 * @return void
	 */
	public function write();

}
