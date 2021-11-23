<?php

require_once __DIR__.'/Response.php';

class AuthResponse extends Response
{

    private static $authMethods = [
            1 => 'ID card',
            2 => 'Mobile ID',
            5 => 'One-off code card',
            6 => 'PIN-calculator',
            7 => 'Code card',
            9 => 'Smart-ID',
        ];


    protected $userId;
    protected $userName;
    protected $country;
    protected $token;
    protected $rid;
    protected $nonce;
    protected $authDate;


    public function setUserId(string $userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserName(string $userName)
    {
        $this->userName = $userName;
        return $this;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserCountry($country)
    {
        $this->userCountry = $country;
        return $this;
    }

    public function getUserCountry()
    {
        return $this->userCountry;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
        return $this;
    }

    public function getNonce()
    {
        return $this->nonce;
    }

    public function setRid($rid)
    {
        $this->rid = $rid;
        return $this;
    }

    public function getRid()
    {
        return $this->rid;
    }

    public function setAuthDate($authDate)
    {
        $this->authDate = $authDate;
        return $this;
    }

    public function getAuthDate()
    {
        return $this->authDate;
    }

    public function getAuthMethod()
    {
        $authMethod = 'unknown';

        if ((int) $this->token > 0 && in_array($this->token, array_keys(self::$authMethods))) {
            $authMethod = self::$authMethods[$this->token];
        }

        return $authMethod;
    }
}
