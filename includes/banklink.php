<?php

/**
 *
 * @author   
 */
include 'logger.php';

class swedbank_v3_banklink {

    private $order;
    private $obSw;
    private $home_url;
    private $log;

    public function __construct($order, $obSw, $home_url) {
        $this->order = $order;
        $this->obSw = $obSw;
        $this->home_url = $home_url;
        $this->log = new \Swedbank_Client_Logger();
    }

    public function setupCon() {

        $orData = $this->order->get_data();
        $test = true;
        $vtid = '';
        $psw = '';
        $bankLL = '';
        $serviceType = null;
        $paymentmethod = null;

        $oD = $orData['payment_method'];

        $test = $this->obSw->settings['testmode_lt'] === 'yes' ? true : false;
        $vtid = $test ? $this->obSw->settings['testvtid_lt'] : $this->obSw->settings['vtid_lt'];
        $psw = $test ? $this->obSw->settings['testpass_lt'] : $this->obSw->settings['pass_lt'];

        if ($oD === 'swedbank_v3_swedbank_v3_1' ) {
                $paymentmethod = 'SW';
                $serviceType = '<ServiceType>LIT_BANK</ServiceType>';
            $lng = get_locale();

            $lng = explode('_',$lng)[0];

            if ($lng !== 'lt' && $lng !== 'en' && $lng !== 'ru' ) {
                $lng = 'lt';
            }

            $bankLL = $lng;

        } else if ($oD === 'swedbank_v3_swedbank_v3_2' ) {
                $paymentmethod = 'SW';
                $serviceType = '<ServiceType>LTV_BANK</ServiceType>';
            $lng = get_locale();

            $lng = explode('_',$lng)[0];

            if ($lng !== 'lv' && $lng !== 'en' && $lng !== 'ru' ) {
                $lng = 'lv';
            }

            $bankLL = $lng;
        } else if ($oD === 'swedbank_v3_swedbank_v3_3') {
                $paymentmethod = 'SW';
                $serviceType = '<ServiceType>EST_BANK</ServiceType>';
            $lng = get_locale();

            $lng = explode('_',$lng)[0];

            if($lng === 'ee') {
                $lng = 'et';
            }
            if ($lng !== 'et' && $lng !== 'en' && $lng !== 'ru' ) {
                $lng = 'et';
            }

            $bankLL = $lng;
        } else {
            return false;
        }

        $merchantReferenceId = 'w' . rand(100, 1000) . '_' . $orData['id'];

        $purchaseAmount = $orData['total'] * 100; // in ct's


        $return_url = $this->home_url . '?swedbankv3=done&amp;order_id=' . $merchantReferenceId . '&amp;pmmm=' . $orData['payment_method']; // return url
        $error_url = $this->home_url . '?swedbankv3=done&amp;order_id=' . $merchantReferenceId . '&amp;pmmm=' . $orData['payment_method']; // error url

        $xml = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<Request version="2">
   <Authentication>
      <client>{$vtid}</client>
      <password>{$psw}</password>
   </Authentication>
   <Transaction>
    <TxnDetails>
      <merchantreference>{$merchantReferenceId}</merchantreference>
    </TxnDetails>
    <HpsTxn>
      <page_set_id>1</page_set_id>
      <method>setup_full</method>
    </HpsTxn>
    <APMTxns>
      <APMTxn>
        <method>purchase</method>
        <payment_method>{$paymentmethod}</payment_method>
        <AlternativePayment version="2">
          <TransactionDetails>
            <Description>Invoice nr: {$merchantReferenceId}</Description>
            <SuccessURL>{$return_url}</SuccessURL>
            <FailureURL>{$error_url}</FailureURL>
            <Language>{$bankLL}</Language>
            <PersonalDetails>
                <Email>{$orData['billing']['email']}</Email>
            </PersonalDetails>
            <BillingDetails>
              <AmountDetails>
                <Amount>{$purchaseAmount}</Amount>
                <Exponent>2</Exponent>
                <CurrencyCode>978</CurrencyCode>
              </AmountDetails>
            </BillingDetails>
          </TransactionDetails>
          <MethodDetails>
            {$serviceType}
          </MethodDetails>
        </AlternativePayment>
      </APMTxn>
    </APMTxns>
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

            $url = ((string) $object->HpsTxn->hps_url[0]) . '?HPS_SessionID=' . ((string) $object->HpsTxn->session_id[0]);
        } else
            return false;

        return [$url,$orData['id'],$merchantReferenceId];
    }

    public function complyte() {

        $orderId = $_GET['order_id'];

        $test = true;
        $vtid = '';
        $psw = '';

        $oD = $_GET['pmmm'];

            $test = $this->obSw->settings['testmode_lt'] === 'yes' ? true : false;
            $vtid = $test ? $this->obSw->settings['testvtid_lt'] : $this->obSw->settings['vtid_lt'];
            $psw = $test ? $this->obSw->settings['testpass_lt'] : $this->obSw->settings['pass_lt'];

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
            $xml = utf8_encode($xml);
            $object = new SimpleXMLElement($xml);
        } catch (Exception $exc) {
            $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData('Failed parse xml') : null;
            return false;
        }

        if ((int) $object->status === 1) {

            return ((string) $object->QueryTxnResult->APMTxn->Purchase->Status[0]);
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
