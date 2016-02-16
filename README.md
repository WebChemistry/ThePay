[![Build Status](https://travis-ci.org/WebChemistry/ThePay.svg?branch=master)](https://travis-ci.org/WebChemistry/ThePay)

Integration of [payment gate thepay](https://www.thepay.cz/). This library **uses** their [component](https://www.thepay.cz/ke-stazeni/), version 3.0 (you can see in 'lib' directory)

## Installation
```yaml
extensions:
    thePay: WebChemistry\ThePay\DI\ThePayExtension
```

## Configuration
```yaml
thePay:
    merchantId: 1
    accountId: 1
    password: myPassword
```

## Display payment component
```php
/** @var WebChemistry\ThePay\ThePay */
$thepay;

$sender = $thePay->createSender(199); // Price

$sender->setDescription('Super product'); // Description for easier identification in administration
$sender->setMerchantData('Customer id is 150.');
// or
$sender->setMerchantData([
    'customer' => 150
]);

echo $sender->render();
```

## Receives payment
```php
/** @var WebChemistry\ThePay\ThePay */
$thepay;

$receiver = $thepay->getReceiver();

if (!$receiver->verifySignature(FALSE)) {
    die('Bad request.');
}
if (!$receiver->isSuccess()) {
    die('Payment was not successful.');
}
// Get info from api
$remotePayment = $receiver->getRemotePayment();
if ($remotePayment) {
    $remotePayment->getValue(); // Price
}

$data = $receiver->getMerchantData();
$row = $db->get($data['customer']);
$row->successPayment();

// We can get also additional info from their api
$remotePayment = $receiver->getRemotePayment();
```