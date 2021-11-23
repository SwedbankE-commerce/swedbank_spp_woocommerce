<?php

/**
 *
 * @author   
 */
include 'logger.php';

class swedbank_v3_query {

    private $obSw;
    private $log;

    public function __construct($obSw) {
        $this->obSw = $obSw;
        $this->log = new \Swedbank_Client_Logger();
    }



    public function query($orderId, $oD) {


        $test = true;
        $vtid = '';
        $psw = '';

        if ($oD === 'swedbank_v3_swedbank_v3_lt' || $oD === 'swedbank_v3_card_lt') {
            $test = $this->obSw->settings['testmode_lt'] === 'yes' ? true : false;
            $vtid = $test ? $this->obSw->settings['testvtid_lt'] : $this->obSw->settings['vtid_lt'];
            $psw = $test ? $this->obSw->settings['testpass_lt'] : $this->obSw->settings['pass_lt'];
        } else {
            $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData('This is not banklink type payment.') : null;
            return false;
        }

        $xml = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<Request version="2">
   <Authentication>
      <client>{$vtid}</client>
      <password>{$psw}</password>
   </Authentication>
   <Transaction>
    <HistoricTxn>
        <method>query</method>
        <reference type="merchant">{$orderId}</reference>
    </HistoricTxn>
  </Transaction>
</Request>

EOL;

        $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData($xml) : null;

        $xml = $this->curOp($test ? 'https://accreditation.datacash.com/Transaction/acq_a' : 'https://mars.transaction.datacash.com/Transaction', $xml);

        $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData($xml) : null;

        try {
            $object = new SimpleXMLElement($xml);
        } catch (Exception $exc) {
            $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData('Failed parse xml') : null;
            return false;
        }

        if ((int) $object->status === 1) {

            var_dump($object);

            if($oD === 'swedbank_v3_card_lt'){
                if( isset($object->QueryTxnResult) ){
                    if((int) $object->QueryTxnResult->status[0] == 150){
                        return false;
                    } else {
                        return ((int) $object->QueryTxnResult->status[0]);
                    }
                } else {
                    return ((int) $object->HpsTxn->AuthAttempts->Attempt->dc_response[0]);
                }


            } else {
                return ((string) $object->QueryTxnResult->APMTxn->Purchase->Status[0]);
            }


        } else
            return false;
    }

    function curOp($envUrl, $xml) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $envUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.12) Gecko/2009070611 Firefox/3.0.12");


        //print_r($xml); die;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $respond = curl_exec($ch);
        curl_close($ch);
        return $respond;
    }

}
