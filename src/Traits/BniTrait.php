<?php

namespace LatiefChan\Bniecollection\Billing\Traits;

use LatiefChan\Bniecollection\Billing\Exceptions\BillingException;
use LatiefChan\Bniecollection\Billing\Helpers\Network\Ngeping;
use LatiefChan\Bniecollection\Billing\Helpers\String\DclHashing;
use Respect\Validation\Validator as v;

trait BniTrait
{
    public function autoGenerateTrxId()
    {
        $random = new \Rych\Random\Random();
        return $random->getRandomString(30);
    }

    public function modifyTrxId($str)
    {
        $random = new \Rych\Random\Random();
        $remaining = 30 - strlen($str);
        $newRandomString = $random->getRandomString($remaining);
        return $newRandomString.$str;
    }

    public function hitBniEcollection($request)
    {
        $host = $this->getMode() == 'production' ? getenv('URL_API_BNI_ECOLLECTION_PRODUCTION') : getenv('URL_API_BNI_ECOLLECTION_DEVELOPMENT');
        Ngeping::host($host);

        $headers = array('Content-Type' => 'application/json');
        $options = array('timeout' => 15);
        $sendRequest = \Requests::post($host, $headers, $request, $options);
        if ($sendRequest->status_code != 200) {
            throw new BillingException('Status HTTP Code ' .$sendRequest->status_code);
        }

        return $sendRequest->body;
    }

    public function responseBniEcollection($response, $clientId, $secretKey)
    {
        if (v::json()->validate($response) === false) {
            throw new BillingException('Format response from bni ecollection is invalid');
        }

        $responseArray = json_decode($response, true);
		if ($responseArray['status'] != '000') {
            throw new BillingException($responseArray['message']);
        }

        $decryptData = DclHashing::parseData($responseArray['data'], $clientId, $secretKey);
		if ($decryptData == null) {
            throw new BillingException('Failed decrypt response from bni ecollection');
        }

		return json_encode(['status' => true, 'data' => $decryptData]);
    }
}
