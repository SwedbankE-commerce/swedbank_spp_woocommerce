<?php

include_once __DIR__.'/Protocol/Protocol.php';
include_once __DIR__.'/Request/AuthRequest.php';
include_once __DIR__.'/Request/PaymentRequest.php';
include_once __DIR__.'/Request/Request.php';
include_once __DIR__.'/Response/Response.php';


class Banklink
{

    protected $protocol;
    protected $requestData = null;
    protected $authData = null;
    protected $requestUrl;
    protected $testRequestUrl;
    protected $requestEncoding = 'UTF-8';
    protected $responseEncoding = 'UTF-8';

    public function __construct($protocol, $country='LT', $bank='SEB')
    {
        $country = strtoupper($country);
        $bank = strtoupper($bank);

        if ($country == 'ET') {
            if($bank == 'CITADELE'){
                $this->requestUrl = 'https://banklink.swedbank.com/EE/PARXEE22';
            } else if($bank == 'LHV'){
                $this->requestUrl = 'https://banklink.swedbank.com/EE/LHVBEE22';
            } else if($bank == 'SEB'){
                $this->requestUrl = 'https://banklink.swedbank.com/EE/EEUHEE2X';
            } else if($bank == 'SWEDBANK'){
                $this->requestUrl = 'https://www.swedbank.ee/banklink';
            } else if($bank == 'COOP'){
                $this->requestUrl = 'https://banklink.swedbank.com/EE/EKRDEE22';
            } else if($bank == 'LUMINOR'){
                $this->requestUrl = 'https://banklink.swedbank.com/LV/NDEAEE2X';
            } else {
                throw new UnexpectedValueException('Please select supported bank');
            }
        } else if ($country == 'LV') {
            if($bank == 'CITADELE'){
                $this->requestUrl = 'https://banklink.swedbank.com/LV/PARXLV22';
            } else if($bank == 'SEB'){
                $this->requestUrl = 'https://banklink.swedbank.com/LV/UNLALV2X';
            } else if($bank == 'SWEDBANK'){
                $this->requestUrl = 'https://www.swedbank.lv/banklink';
            } else if($bank == 'LUMINOR'){
                $this->requestUrl = 'https://banklink.swedbank.com/LV/RIKOLV2X';
            } else {
                throw new UnexpectedValueException('Please select supported bank');
            }
        } else if ($country == 'LT') {
            if($bank == 'CITADELE'){
                $this->requestUrl = 'https://banklink.swedbank.com/LT/INDULT2X';
            } else if($bank == 'SEB'){
                $this->requestUrl = 'https://banklink.swedbank.com/LT/CBVILT2X';
            } else if($bank == 'SWEDBANK'){
                $this->requestUrl = 'https://www.swedbank.lt/banklink';
            } else if($bank == 'LUMINOR'){
                $this->requestUrl = 'https://banklink.swedbank.com/LV/AGBLLT2X';
            } else {
                throw new UnexpectedValueException('Please select supported bank');
            }
        } else {
            throw new UnexpectedValueException('Please select supported country');
        }
        $this->protocol = $protocol;
    }

    public function debugMode()
    {
        return $this->setRequestUrl($this->testRequestUrl);
    }

    public function setRequestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
        return $this;
    }

    public function getPaymentRequest(
        $orderId,
        $sum,
        $message,
        $language = 'ENG',
        $currency = 'EUR',
        $customRequestData = [],
        $timezone = 'Europe/Vilnius'
    )
    {
        if ($this->requestData) {
            return $this->requestData;
        }

        $requestData = $this->protocol->getPaymentRequest(
            $orderId,
            $sum,
            $message,
            $language,
            $currency,
            array_merge($this->getAdditionalRequestFields(), $customRequestData),
            $this->requestEncoding,
            $timezone
        );

        // Add additional fields
        $requestData = array_merge($requestData, $this->getAdditionalFields());

        $this->requestData = new PaymentRequest($this->getRequestUrlFor('payment'), $requestData);

        return $this->requestData;
    }

    public function getAuthRequest(
        $recId = null,
        $nonce = null,
        $rid = null,
        $language = 'EST',
        $timezone = 'Europe/Tallinn'
    )
    {
        if ($this->authData) {
            return $this->authData;
        }

        $authData = $this->protocol->getAuthRequest($recId, $nonce, $rid, $this->requestEncoding, $language, $timezone);

        // Add additional fields
        $authData = array_merge($authData, $this->getAdditionalFields());

        $this->authData = new AuthRequest($this->getRequestUrlFor('auth'), $authData);

        return $this->authData;
    }

    public function handleResponse($responseData)
    {
        return $this->protocol->handleResponse($responseData, $this->getResponseEncoding($responseData));
    }

    public function getRequestUrlFor($type)
    {
        if (is_string($this->requestUrl)) {
            return $this->requestUrl;
        } elseif (is_array($this->requestUrl) && array_key_exists($type, $this->requestUrl)) {
            return $this->requestUrl[$type];
        }

        throw new UnexpectedValueException(sprintf('requestUrl is not string or array containing desired type (%s)', $type));
    }

    protected function getResponseEncoding(array $responseData)
    {
        if ($this->getEncodingField() && isset($responseData[$this->getEncodingField()])) {
            return $responseData[$this->getEncodingField()];
        }

        return $this->responseEncoding;
    }

    protected function getEncodingField()
    {
        return 'VK_ENCODING';
    }

    protected function getAdditionalFields()
    {
        return [];
    }

    protected function getAdditionalRequestFields()
    {
        return [];
    }
}
