<?php

namespace WebChemistry\ThePay;

class InvalidReceivedDataException extends \Exception implements ThePayThrowable {

	const NOT_SUCCESS = 1;
	const VALUE_BELOW = 2;

}
