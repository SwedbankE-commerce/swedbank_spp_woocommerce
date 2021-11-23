<?php


require_once __DIR__.'/Response.php';

class PaymentResponse extends Response
{

    protected $orderId;
    protected $sum;
    protected $currency;
    protected $sender;
    protected $transactionId;
    protected $transactionDate;


    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setSender($senderName, $senderAccount)
    {
        $this->sender = new stdClass();
        $this->sender->name = $senderName;
        $this->sender->account = $senderAccount;
        return $this;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setReceiver($receiverName, $receiverAccount)
    {
        $this->receiver = new stdClass();
        $this->receiver->name = $receiverName;
        $this->receiver->account = $receiverAccount;
        return $this;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    public function setMessage( $message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setAutomatic($isAutomatic)
    {
        $this->isAutomatic = $isAutomatic;
        return $this;
    }

    public function isAutomatic()
    {
        return $this->isAutomatic;
    }
}
