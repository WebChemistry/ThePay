[![Build Status](https://travis-ci.org/WebChemistry/ThePay.svg?branch=master)](https://travis-ci.org/WebChemistry/ThePay)

## Installation
```yaml
extensions:
    thePay: WebChemistry\ThePay\DI\ThePayExtension
```

## Configuration
Default values are for testing
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

$sender = $thePay->createSender(199);

$sender->setDescription('Super product');
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
    $remotePayment->getValue();
}

$data = $receiver->getData();
$row = $db->get($data['customer']);
$row->successPayment();
```

-------------------
ThePay version: 3.0