<?php


class Response
{
    const STATUS_SUCCESS = 1;
    const STATUS_ERROR = -1;
    const RESPONSE_AUTO = 'Y';
    protected $status;
    protected $responseData;
    protected $language;
    protected $isAutomatic = false;


    public function __construct($status, $responseData)
    {
        $this->status = $status;
        $this->responseData = $responseData;
    }

    public function wasSuccessful()
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getResponseData()
    {
        return $this->responseData;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }
}
