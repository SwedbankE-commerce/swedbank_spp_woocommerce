<?php

//use DateTime;
//use DateTimeZone;
//use InvalidArgumentException;
include_once __DIR__.'/Services.php';
include_once __DIR__.'/../Response/AuthResponse.php';
include_once __DIR__.'/../Response/PaymentResponse.php';
//use UnexpectedValueException;


class Protocol
{

    protected $publicKey;
    protected $privateKey;
    protected $privateKeyPassword;
    protected $sellerId;
    protected $sellerName;
    protected $sellerAccount;
    protected $version;
    protected $requestUrl;
    protected $serviceId;
    protected $result;
    protected $useMbStrlen = true;
    protected $algorithm = OPENSSL_ALGO_SHA1;

    const FIELD_SERVICE = 'VK_SERVICE';
    const FIELD_VERSION = 'VK_VERSION';
    const FIELD_SND_ID = 'VK_SND_ID';
    const FIELD_STAMP = 'VK_STAMP';
    const FIELD_AMOUNT = 'VK_AMOUNT';
    const FIELD_CURR = 'VK_CURR';
    const FIELD_REF = 'VK_REF';
    const FIELD_MSG = 'VK_MSG';
    const FIELD_RETURN = 'VK_RETURN';
    const FIELD_CANCEL = 'VK_CANCEL';
    const FIELD_DATETIME = 'VK_DATETIME';
    const FIELD_T_DATETIME = 'VK_T_DATETIME';
    const FIELD_LANG = 'VK_LANG';
    const FIELD_NAME = 'VK_NAME';
    const FIELD_ACC = 'VK_ACC';
    const FIELD_MAC = 'VK_MAC';
    const FIELD_RID = 'VK_RID';
    const FIELD_REPLY = 'VK_REPLY';
    const FIELD_NONCE = 'VK_NONCE';
    const FIELD_REC_ID = 'VK_REC_ID';
    const FIELD_AUTO = 'VK_AUTO';
    const FIELD_SND_NAME = 'VK_SND_NAME';
    const FIELD_SND_ACC = 'VK_SND_ACC';
    const FIELD_REC_NAME = 'VK_REC_NAME';
    const FIELD_REC_ACC = 'VK_REC_ACC';
    const FIELD_T_NO = 'VK_T_NO';
    const FIELD_USER_ID = 'VK_USER_ID';
    const FIELD_USER_NAME = 'VK_USER_NAME';
    const FIELD_COUNTRY= 'VK_COUNTRY';
    const FIELD_TOKEN = 'VK_TOKEN';
    const FIELD_ENCODING = 'VK_ENCODING';


    public function __construct(
        $sellerId,
        $privateKey,
        $privateKeyPassword,
        $publicKey,
        $requestUrl,
        $sellerName = null,
        $sellerAccount = null,
        $version = '008'
    ) {
        $this->privateKey = $privateKey;
        $this->privateKeyPassword = $privateKeyPassword;
        $this->publicKey = $publicKey;

        $this->sellerId = $sellerId;
        $this->sellerName = $sellerName;
        $this->sellerAccount = $sellerAccount;
        $this->version = $version;
        $this->requestUrl = $requestUrl;

        // Detect which service to use
        if (strlen($sellerName) > 0 && strlen($sellerAccount) > 0) {
            $this->serviceId = Services::PAYMENT_REQUEST_1011;
            return;
        }

        $this->serviceId = Services::PAYMENT_REQUEST_1012;
    }

    public function useMbStrlen($useMbStrlen)
    {
        $this->useMbStrlen = (boolean)$useMbStrlen;
    }

    public function getPaymentRequest(
        $orderId,
        $sum,
        $message,
        $language = 'EST',
        $currency = 'EUR',
        $customRequestData = [],
        $encoding = 'UTF-8',
        $timezone = 'Europe/Tallinn'
    ) {
        $time = getenv('CI') ? getenv('TEST_DATETIME') : 'now';
        $datetime = new DateTime($time, new DateTimeZone($timezone));

        $data = [
            static::FIELD_SERVICE => $this->serviceId,
            static::FIELD_VERSION => $this->version,
            static::FIELD_SND_ID => $this->sellerId,
            static::FIELD_STAMP => $orderId,
            static::FIELD_AMOUNT => $sum,
            static::FIELD_CURR => $currency,
            static::FIELD_REF => '', // $orderId, //$this->calculateReference($orderId),
            static::FIELD_MSG => $message,
            static::FIELD_RETURN => $this->requestUrl,
            static::FIELD_CANCEL => $this->requestUrl,
            static::FIELD_DATETIME => $datetime->format('Y-m-d\TH:i:sO'),
            static::FIELD_LANG => $language,
            static::FIELD_ENCODING => 'UTF-8'
        ];

        if (Services::PAYMENT_REQUEST_1011 === $this->serviceId) {
            $data[static::FIELD_NAME] = $this->sellerName;
            $data[static::FIELD_ACC] = $this->sellerAccount;
        }

        // Merge custom data
        if (is_array($customRequestData)) {
            $data = array_merge($data, $customRequestData);
        }

        // Generate signature
        $data[static::FIELD_MAC] = $this->getSignature($data, $encoding);

        return $data;
    }

    private function calculateReference($orderId)
    {
        $length = strlen($orderId);

        $orderId = (string) $orderId;
        $multipliers = [7, 3, 1];
        $total = 0;
        $multiplierKey = 0;

        for ($i = $length - 1; $i >= 0; --$i) {
            $total += (int) $orderId[$i] * $multipliers[$multiplierKey];
            $multiplierKey = $multiplierKey < 2 ? ++$multiplierKey : 0;
        };

        $closestTen = ceil($total / 10) * 10;
        $checkNum = $closestTen - $total;

        return $orderId.$checkNum;
    }

    public function getAuthRequest(
        $recId = null,
        $nonce = null,
        $rid = null,
        $encoding = 'UTF-8',
        $language = 'EST',
        $timezone = 'Europe/Tallinn'
    ) {
        $time = getenv('CI') ? getenv('TEST_DATETIME') : 'now';
        $datetime = new Datetime($time, new DateTimeZone($timezone));

        $this->serviceId = (is_null($nonce)) ? Services::AUTH_REQUEST_4011 : Services::AUTH_REQUEST_4012;

        $data = [
            static::FIELD_SERVICE => $this->serviceId,
            static::FIELD_VERSION => $this->version,
            static::FIELD_SND_ID => $this->sellerId,
            static::FIELD_RETURN => $this->requestUrl,
            static::FIELD_DATETIME => $datetime->format('Y-m-d\TH:i:sO'),
            static::FIELD_RID => '',
            static::FIELD_LANG => $language,
            static::FIELD_REPLY => Services::AUTH_RESPONSE_3012
        ];

        if (!is_null($nonce)) {
            $data[static::FIELD_SERVICE] = Services::AUTH_REQUEST_4012;
            $data[static::FIELD_NONCE] = $nonce;
            $data[static::FIELD_REC_ID] = $recId;
            unset($data[static::FIELD_REPLY]);
        }

        if (!is_null($rid)) {
            $data[static::FIELD_RID] = $rid;
        }

        // Generate signature
        $data[static::FIELD_MAC] = $this->getSignature($data, $encoding);

        return $data;
    }

    public function handleResponse($response, $encoding = 'UTF-8')
    {
        $success = $this->validateSignature($response, $encoding);

        $service = $response[static::FIELD_SERVICE];
        $servicesClass = static::getServicesClass();

        // Is payment response service?
        if (in_array($service, $servicesClass::getPaymentResponseServices())) {
            return $this->handlePaymentResponse($response, $success);
        }

        // Is authentication response service?
        if (in_array($service, $servicesClass::getAuthenticationResponseServices())) {
            return $this->handleAuthResponse($response, $success);
        }
    }

    protected function handlePaymentResponse($responseData, $success)
    {
        $servicesClass = static::getServicesClass();
        $status = PaymentResponse::STATUS_ERROR;

        if ($success && $responseData[static::FIELD_SERVICE] === $servicesClass::PAYMENT_RESPONSE_SUCCESS) {
            $status = PaymentResponse::STATUS_SUCCESS;
        }

        $response = new PaymentResponse($status, $responseData);
        $response->setOrderId($responseData[static::FIELD_STAMP]);

        if (isset($responseData[static::FIELD_LANG])) {
            $response->setLanguage($responseData[static::FIELD_LANG]);
        }

        if (isset($responseData[static::FIELD_AUTO])) {
            $response->setAutomatic($responseData[static::FIELD_AUTO] === PaymentResponse::RESPONSE_AUTO);
        }

        if (isset($responseData[static::FIELD_MSG])) {
            $response->setMessage($responseData[static::FIELD_MSG]);
        }

        if (PaymentResponse::STATUS_SUCCESS === $status) {
            // fallback: SEB has VK_ACC, others VK_REC_ACC
            $receiverAccount = isset($responseData[static::FIELD_REC_ACC]) ? $responseData[static::FIELD_REC_ACC] : $responseData[static::FIELD_ACC];

            $response
                ->setSum($responseData[static::FIELD_AMOUNT])
                ->setCurrency($responseData[static::FIELD_CURR])
                ->setSender($responseData[static::FIELD_SND_NAME], $responseData[static::FIELD_SND_ACC])
                ->setReceiver($responseData[static::FIELD_REC_NAME], $receiverAccount)
                ->setTransactionId($responseData[static::FIELD_T_NO])
                ->setTransactionDate($responseData[static::FIELD_T_DATETIME]);
        }

        return $response;
    }

    protected function handleAuthResponse($responseData, $success)
    {
        $status = AuthResponse::STATUS_ERROR;
        if ($success) {
            $status = AuthResponse::STATUS_SUCCESS;
        }

        $response = new AuthResponse($status, $responseData);

        if (isset($responseData[static::FIELD_LANG])) {
            $response->setLanguage($responseData[static::FIELD_LANG]);
        }

        if (PaymentResponse::STATUS_SUCCESS === $status) {
            $response
                // Person data
                ->setUserId($responseData[static::FIELD_USER_ID])
                ->setUserName($responseData[static::FIELD_USER_NAME])
                ->setUserCountry($responseData[static::FIELD_COUNTRY])
                ->setToken($responseData[static::FIELD_TOKEN])
                // Request data
                ->setRid($responseData[static::FIELD_RID])
                ->setNonce($responseData[static::FIELD_NONCE])
                ->setAuthDate($responseData[static::FIELD_DATETIME]);
        }

        return $response;
    }

    public function getSignature($data, $encoding = 'UTF-8')
    {
        $mac = $this->generateSignature($data, $encoding);
        $signature = '';

        if (is_file($this->privateKey)) {
            $privateKey = openssl_pkey_get_private('file://'.$this->privateKey, $this->privateKeyPassword);
        } elseif (is_string($this->privateKey)) {
            $privateKey = openssl_pkey_get_private($this->privateKey, $this->privateKeyPassword);
        }

        if (!$privateKey) {
            throw new UnexpectedValueException('Can not get private key.');
        }
/*echo '<pre>';
        print_r($mac);
        print_r($data);

        die;*/
        //file_put_contents('uzklausa.txt', $mac."\n\n", FILE_APPEND);
        //file_put_contents('uzklausa.txt', print_r($data, true), FILE_APPEND);
        openssl_sign($mac, $signature, $privateKey, $this->algorithm);
        openssl_free_key($privateKey);

        $result = base64_encode($signature);

        return $result;
    }

    protected function generateSignature($data, $encoding = 'UTF-8')
    {
        if (!isset($data[static::FIELD_SERVICE])) {
            throw new InvalidArgumentException(static::FIELD_SERVICE.' key must be in data. Can\'t generate signature.');
        }

        $service = $data[static::FIELD_SERVICE];
        $fields = static::getFields($service);
        $mac = '';

        // VK_REC_ACC fallback to VK_ACC
        if (in_array(static::FIELD_REC_ACC, $fields) && isset($data[static::FIELD_ACC])) {
            $fields[array_search(static::FIELD_REC_ACC, $fields)] = static::FIELD_ACC;
        }

        foreach ($fields as $key) {
            // Check if field exists
            if (!isset($data[$key]) || $data[$key] === false || is_null($data[$key])) {
                throw new UnexpectedValueException(
                    vsprintf('Field %s must be set to use service %s.', [$key, $service])
                );
            }

            $value = $data[$key];
            $length = $this->useMbStrlen ? mb_strlen($value, $encoding) : strlen($value);
            $mac .= str_pad($length, 3, '0', STR_PAD_LEFT).$value;
        }

        return $mac;
    }


    protected function validateSignature($response, $encoding = 'UTF-8')
    {
        $data = $this->generateSignature($response, $encoding);
        if (is_file($this->publicKey)) {
            $publicKey = openssl_get_publickey('file://'.$this->publicKey);
        } elseif (is_string($this->publicKey)) {
            $publicKey = openssl_get_publickey($this->publicKey);
        }

        if (!$publicKey) {
            throw new UnexpectedValueException('Can not get public key.');
        }
        $this->result = openssl_verify($data, base64_decode($response[static::FIELD_MAC]), $publicKey, $this->algorithm);
        openssl_free_key($publicKey);
        return $this->result === 1;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    protected static function getFields($service)
    {
        $servicesClass = static::getServicesClass();
        return $servicesClass::getFields($service);
    }

    protected static function getServicesClass()
    {
        return Services::class;
    }
}
