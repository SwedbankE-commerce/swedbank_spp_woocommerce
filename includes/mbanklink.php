<?php

/**
 *
 * @author   
 */
include 'logger.php';

class swedbank_v3_mbanklink {

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

        require __DIR__ . '/mbbl/Protocol/Protocol.php';

        $lng = explode('_', $orData['payment_method'])[4];
        $bankType = explode('_', $orData['payment_method'])[2];

        $merchantReferenceId =  $orData['id'];

        $protocol = new Protocol(
            trim($this->obSw->settings['seller_id_' . $lng]), // seller ID (VK_SND_ID)
            $this->obSw->settings['privatekey_' . $lng], // private key
            '', // private key password, leave empty, if not neede
            $this->obSw->settings['publickey_' . $lng], // public key
            $this->home_url . '/index.phps?swedbankv3=doneC&order_id=' . $merchantReferenceId . '&pmmm=' . $orData['payment_method'].'&lv='.$lng
        );

        require __DIR__ . '/mbbl/Banklink.php';
        if ($lng == 'ee')
            $lng = 'et';

        $banklink = new Banklink($protocol, $lng, $bankType);

        switch (strtolower(explode('_',get_locale())[1])) {
            case 'en':
                $lnv = 'ENG';
                break;
            case 'lt':
                $lnv = 'LIT';
                break;
            case 'ee':
                $lnv = 'EST';
                break;
            case 'et':
                $lnv = 'EST';
                break;
            case 'ru':
                $lnv = 'RUS';
                break;
            default:
                $lnv = 'ENG';
        }
        $ordM = 'Order Nr: ' . $merchantReferenceId;


        $request = $banklink->getPaymentRequest($merchantReferenceId, $orData['total'], $ordM, $lnv);

        $this->obSw->settings['debuging'] === 'yes' ? $this->log->logData(print_r($request->getRequestData(),true)) : null;

//echo $request->getRequestUrl();
        return '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">
        function closethisasap() {
            document.forms["redirectpost"].submit();
        }
    </script>
<body onload="closethisasap();">
<form method="POST" name="redirectpost" action="' . $request->getRequestUrl() . '">

    ' . $request->getRequestInputs() . '
    <input type="submit" style="display: none;" value="Pay" />
</form>
</body>
</html>';

    }


}
