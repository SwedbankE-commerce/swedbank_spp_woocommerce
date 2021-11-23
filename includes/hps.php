<?php

/**
 *
 * @author   
 */
include 'logger.php';

class swedbank_v3_hps {

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
        $pgSetId = '';

        if ($orData['payment_method'] === 'swedbank_v3_card_lt') {
            $test = $this->obSw->settings['testmode_lt'] === 'yes' ? true : false;
            $vtid = $test ? $this->obSw->settings['testvtid_lt'] : $this->obSw->settings['vtid_lt'];
            $psw = $test ? $this->obSw->settings['testpass_lt'] : $this->obSw->settings['pass_lt'];
        }  else {
            $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData('This is not card type payment.') : null;
            return false;
        }

        $merchantReferenceId = 'w' . rand(100, 1000) . '_' . $orData['id'];
        $purchaseAmount = $orData['total'];

        $expire_url = $this->home_url . '?swedbankv3=done&amp;order_id=' . $merchantReferenceId . '&amp;pmmm=' . $orData['payment_method']; // expire url
        $return_url = $this->home_url . '?swedbankv3=done&amp;order_id=' . $merchantReferenceId . '&amp;pmmm=' . $orData['payment_method']; // return url
        $error_url = $this->home_url . '?swedbankv3=done&amp;order_id=' . $merchantReferenceId . '&amp;pmmm=' . $orData['payment_method']; // error url

        $re_url = $this->home_url . '/checkout/'; // back url

        $page_set_id = $test ? '329' : '4018';

        $date = date('Ymd H:i:s');

        $lng = get_locale();

        $lng = explode('_',$lng)[0];

        if($lng === 'et') {
            $lng = 'ee';
        }
        if ($lng !== 'lt' && $lng !== 'dk' && $lng !== 'ee' && $lng !== 'lv' && $lng !== 'no' && $lng !== 'ru' &&
            $lng !== 'se' && $lng !== 'fr' && $lng !== 'it' && $lng !== 'es' && $lng !== 'de' && $lng !== 'pl' &&
            $lng !== 'nl' && $lng !== 'sk' && $lng !== 'hu') {
            $lng = 'en';
        }

        $xml = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<Request version="2">
   <Authentication>
      <client>{$vtid}</client>
      <password>{$psw}</password>
   </Authentication>
   <Transaction>
      <TxnDetails>
      <Risk>
        <Action service="1">
        <MerchantConfiguration>
          <channel>W</channel>
         </MerchantConfiguration>
        <CustomerDetails>
          <OrderDetails>
            <BillingDetails>
              <state_province></state_province>
              <name>{$orData['billing']['first_name']} {$orData['billing']['last_name']}</name>
              <address_line1>{$orData['billing']['address_1']}</address_line1>
              <address_line2>{$orData['billing']['address_2']}</address_line2>
              <city>{$orData['billing']['city']}</city>
              <zip_code>{$orData['billing']['postcode']}</zip_code>
              <country>{$orData['billing']['country']}</country>
            </BillingDetails>
          </OrderDetails>
          <PersonalDetails>
             <first_name>{$orData['billing']['first_name']}</first_name>
             <surname>{$orData['billing']['last_name']}</surname>
             <telephone>{$orData['billing']['phone']}</telephone>
          </PersonalDetails>
          <ShippingDetails>
            <title></title>
            <first_name>{$orData['billing']['first_name']}</first_name>
            <surname>{$orData['billing']['last_name']}</surname>
            <address_line1>{$orData['billing']['address_1']}</address_line1>
            <address_line2>{$orData['billing']['address_2']}</address_line2>
            <city>{$orData['billing']['city']}</city>
            <country>{$orData['billing']['country']}</country>
            <zip_code>{$orData['billing']['postcode']}</zip_code>
          </ShippingDetails>
          <PaymentDetails>
            <payment_method>CC</payment_method>
          </PaymentDetails>
          <RiskDetails>
            <email_address>{$orData['billing']['email']}</email_address>
            <ip_address>{$orData['customer_ip_address']}</ip_address>
          </RiskDetails>
        </CustomerDetails>
      </Action>
     </Risk>
     <merchantreference>{$merchantReferenceId}</merchantreference>
     <ThreeDSecure>
        <purchase_datetime>{$date}</purchase_datetime>
        <merchant_url>{$this->home_url}</merchant_url>
        <purchase_desc>Invoice nr: {$merchantReferenceId}</purchase_desc>
        <verify>yes</verify>
     </ThreeDSecure>
     <capturemethod>ecomm</capturemethod>
     <amount currency="EUR">{$purchaseAmount}</amount>
   </TxnDetails>
   <HpsTxn>
     <method>setup_full</method>
     <page_set_id>{$page_set_id}</page_set_id>
     <return_url>{$return_url}</return_url>
     <expiry_url>{$expire_url}</expiry_url>
     <error_url>{$error_url}</error_url>
     <DynamicData>
    <dyn_data_3></dyn_data_3>
    <dyn_data_4>{$re_url}</dyn_data_4>
        <dyn_data_5>{$lng}</dyn_data_5>
    <dyn_data_6>visaelectron_maestro_visa_mastercard</dyn_data_6>
    <dyn_data_7></dyn_data_7>
    <dyn_data_8></dyn_data_8>
    <dyn_data_9></dyn_data_9>
</DynamicData>
   </HpsTxn>
   <CardTxn>
      <method>auth</method>
   </CardTxn>
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
        $dc_ref = $_GET['dts_reference'];

        $test = true;
        $vtid = '';
        $psw = '';


        if ($_GET['pmmm'] === 'swedbank_v3_card_lt') {
            $test = $this->obSw->settings['testmode_lt'] === 'yes' ? true : false;
            $vtid = $test ? $this->obSw->settings['testvtid_lt'] : $this->obSw->settings['vtid_lt'];
            $psw = $test ? $this->obSw->settings['testpass_lt'] : $this->obSw->settings['pass_lt'];
        }  else {
            $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData('This is not card type payment.') : null;
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
            $_POST['status'] = 'SUCCESS';
            //authcode
            $_POST['authcode'] = $object->QueryTxnResult->authcode[0];
            //pan
            $_POST['pan'] = $object->QueryTxnResult->Card->pan[0];
            $_POST['fulfill_date'] = $object->QueryTxnResult->fulfill_date[0];
            $_POST['merchant_reference'] = $object->QueryTxnResult->merchant_reference[0];

            return ((int) $object->QueryTxnResult->status[0]);
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
