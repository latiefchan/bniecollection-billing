<?php

require __DIR__.'/../vendor/autoload.php';

use LatiefChan\Bniecollection\Billing\Transaction;

try {
    $billing = new Transaction();
    $billing->setTypeTransaction('createbilling');
    $billing->setClientId('000');
    $billing->setTrxId('=000==1');
    $billing->setTrxAmount(10000);
    $billing->setBillingType('c');
    $billing->setCustomerName('latief');
    $billing->setCustomerEmail('latiefchan52@gmail.com');
    $billing->setCustomerPhone('085817233128');
    $billing->setVirtualAccount('');
    $billing->setDateTimeExpired(2);
    $billing->setDescription('belanja');
    $billing->setSecretKey('1bd6d8393059d43afb11e7dff0532e09');
    $billing->setMode('development');
    $result = $billing->send();
    dump($result);

} catch (Exception $e) {
    dump($e->getMessage());
}
