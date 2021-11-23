<?php
/**
 *
 * @package  WC_Gateway_Swedbank_Integration
 * @author   Darius Augaitis
 */

    class WC_Gateway_Swedbank_Integration  {
        private $order;
        private $swMod;

        public function __construct($order, $swMod) {
            global $woocommerce;

            $this->order = $order;
            $this->swMod = $swMod;

        }


        public function getOrder()
        {
            return $this->order;
        }


        public function getSwMod()
        {
            return $this->swMod;
        }

        public function get_url($home_url){

           if($this->swMod->id === 'swedbank_v3_card_lt' ){
                include 'hps.php';
                $ob =  new swedbank_v3_hps($this->getOrder(), $this->getSwMod(), $home_url);
                return $ob->setupCon();
           } if($this->swMod->id === 'swedbank_v3_swedbank_v3_1' || $this->swMod->id === 'swedbank_v3_swedbank_v3_2' || $this->swMod->id === 'swedbank_v3_swedbank_v3_3'){
                include 'banklink.php';
                $ob =  new swedbank_v3_banklink($this->getOrder(), $this->getSwMod(), $home_url);
                return $ob->setupCon();
            } else {
                $orData = $this->order->get_data();
                return [$home_url.'?swedbankv3=redirectmbbl&order_id='.$orData['id'], $orData['id'], $orData['id']];
           }
         }
         
         
         public function getDone(){
             include 'hps.php';
             $ob =  new swedbank_v3_hps($this->getOrder(), $this->getSwMod(), '');
             return $ob->complyte();
         }
         
         public function getDoneB(){
             include 'banklink.php';
             $ob =  new swedbank_v3_banklink($this->getOrder(), $this->getSwMod(), '');
             return $ob->complyte();
         }



    }
