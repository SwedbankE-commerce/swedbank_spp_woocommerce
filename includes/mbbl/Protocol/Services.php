<?php

//use UnexpectedValueException;


final class Services
{

    const PAYMENT_REQUEST_1011 = '1011';
    const PAYMENT_REQUEST_1012 = '1012';
    const PAYMENT_RESPONSE_SUCCESS = '1111';
    const PAYMENT_RESPONSE_FAILED = '1911';
    const AUTH_REQUEST_4012 = '4012';
    const AUTH_RESPONSE_3013 = '3013';
    const AUTH_REQUEST_4011 = '4011';
    const AUTH_RESPONSE_3012 = '3012';

    public static function getFields( $serviceId)
    {
        switch ($serviceId) {
            case self::PAYMENT_REQUEST_1011:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_STAMP',
                    'VK_AMOUNT',
                    'VK_CURR',
                    'VK_ACC',
                    'VK_NAME',
                    'VK_REF',
                    'VK_MSG',
                    'VK_RETURN',
                    'VK_CANCEL',
                    'VK_DATETIME',
                ];
                break;
            case self::PAYMENT_REQUEST_1012:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_STAMP',
                    'VK_AMOUNT',
                    'VK_CURR',
                    'VK_REF',
                    'VK_MSG',
                    'VK_RETURN',
                    'VK_CANCEL',
                    'VK_DATETIME',
                ];
                break;
            case self::PAYMENT_RESPONSE_SUCCESS:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_REC_ID',
                    'VK_STAMP',
                    'VK_T_NO',
                    'VK_AMOUNT',
                    'VK_CURR',
                    'VK_REC_ACC',
                    'VK_REC_NAME',
                    'VK_SND_ACC',
                    'VK_SND_NAME',
                    'VK_REF',
                    'VK_MSG',
                    'VK_T_DATETIME',
                ];
                break;
            case self::PAYMENT_RESPONSE_FAILED:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_REC_ID',
                    'VK_STAMP',
                    'VK_REF',
                    'VK_MSG',
                ];
                break;
            case self::AUTH_REQUEST_4011:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_REPLY',
                    'VK_RETURN',
                    'VK_DATETIME',
                    'VK_RID',
                ];
                break;
            case self::AUTH_REQUEST_4012:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_REC_ID',
                    'VK_NONCE',
                    'VK_RETURN',
                    'VK_DATETIME',
                    'VK_RID',
                ];
                break;
            case self::AUTH_RESPONSE_3012:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_USER',
                    'VK_DATETIME',
                    'VK_SND_ID',
                    'VK_REC_ID',
                    'VK_USER_NAME',
                    'VK_USER_ID',
                    'VK_COUNTRY',
                    'VK_OTHER',
                    'VK_TOKEN',
                    'VK_RID',
                ];
                break;
            case self::AUTH_RESPONSE_3013:
                return [
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_DATETIME',
                    'VK_SND_ID',
                    'VK_REC_ID',
                    'VK_NONCE',
                    'VK_USER_NAME',
                    'VK_USER_ID',
                    'VK_COUNTRY',
                    'VK_OTHER',
                    'VK_TOKEN',
                    'VK_RID',
                ];
                break;
            default:
                throw new UnexpectedValueException(sprintf('Service %s is not supported.', $serviceId));
                break;
        }
    }


    public static function getPaymentResponseServices()
    {
        return [
            self::PAYMENT_RESPONSE_SUCCESS,
            self::PAYMENT_RESPONSE_FAILED,
        ];
    }

    public static function getAuthenticationResponseServices()
    {
        return [
            self::AUTH_RESPONSE_3012,
            self::AUTH_RESPONSE_3013,
        ];
    }
}
