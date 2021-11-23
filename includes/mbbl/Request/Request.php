<?php


class Request
{

    protected $requestUrl;
    protected $requestData;

    public function __construct(string $requestUrl, array $requestData)
    {
        $this->requestUrl = $requestUrl;
        $this->requestData = $requestData;
    }

    public function getRequestInputs()
    {
        if (empty($this->requestData)) {
            throw new UnexpectedValueException('Request data is empty.');
        }

        $html = '';

        foreach ($this->requestData as $key => $value) {
            $html .= vsprintf(
                '<input type="hidden" id="%s" name="%s" value="%s" />',
                [strtolower($key), $key, $value]
            )."\n";
        }

        return $html;
    }

    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    public function getRequestData()
    {
        return $this->requestData;
    }
}
