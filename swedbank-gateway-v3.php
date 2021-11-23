<?php
/**
 * Plugin Name: Swedbank gateway V3
 * Plugin URI: https://sppdemoshop.eu/
 * Description: Swedbank gateway V3 for bank transfer and Debit/Credit Card payment, Bank Link and Bank Instance support for WooCommerce.
 * Author: Darius Augaitis
 * Author URI:
 * Version: 3.2.0
 * Text Domain: swedbank-plugin
 * Domain Path: /languages
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('init', 'do_output_bufferv3');
function do_output_bufferv3()
{
    ob_start();
}

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scriptsv3');

function callback_for_setting_up_scriptsv3()
{

    wp_register_style('swedbankv3', plugins_url('css/swedbank_plugin.css', __FILE__));
    wp_enqueue_style('swedbankv3');
}

add_action('plugins_loaded', 'init_swedbank_v3_lt_gateway_class');

function add_swedbank_v3_lt_gateway_class($methods)
{
    $methods[] = 'WC_Gateway_Swedbank_lt_v3';
    return $methods;
}


add_filter('woocommerce_payment_gateways', 'add_swedbank_v3_lt_gateway_class');

add_filter('woocommerce_available_payment_gateways', 'filter_gateways3', 1);

function filter_gateways3($gateways)
{
    global $woocommerce;

    // return $gateways
    if (isset($gateways['swedbankv3'])) {

        if ($gateways['swedbankv3']->settings['card_lt'] === 'yes') {
            if (!isset($gateways['swedbank_v3_card_lt'])) {
                $gateways['swedbank_v3_card_lt'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_card_lt']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/cards.png';
                $gateways['swedbank_v3_card_lt']->id = 'swedbank_v3_card_lt';
                $gateways['swedbank_v3_card_lt']->title = __('Payment card', 'woocommerce');
                $gateways['swedbank_v3_card_lt']->description = '';
            }
        }

        if ((int)$gateways['swedbankv3']->settings['swedbank_v3_lt'] > 0) {
            if (!isset($gateways['swedbank_v3_swedbank_v3_lt'])) {
                $gateways['swedbank_v3_swedbank_v3_lt'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_swedbank_v3_lt']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/Swedbank.png';
                $gateways['swedbank_v3_swedbank_v3_lt']->id = 'swedbank_v3_swedbank_v3_' . $gateways['swedbankv3']->settings['swedbank_v3_lt'];
                $gateways['swedbank_v3_swedbank_v3_lt']->title = 'Swedbank';
                $gateways['swedbank_v3_swedbank_v3_lt']->description = '';
            }
        }


        if ($gateways['swedbankv3']->settings['swedbank_alone_lt'] === 'yes') {
            if (!isset($gateways['swedbank_v3_swedbank_alone_lt'])) {
                $gateways['swedbank_v3_swedbank_alone_lt'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_swedbank_alone_lt']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/Swedbank.png';
                $gateways['swedbank_v3_swedbank_alone_lt']->id = 'swedbank_v3_swedbank_alone_lt';
                $gateways['swedbank_v3_swedbank_alone_lt']->title = 'Swedbank';
                $gateways['swedbank_v3_swedbank_alone_lt']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['seb_lt'] === 'yes') {
            if (!isset($gateways['swedbank_v3_seb_a_lt'])) {
                $gateways['swedbank_v3_seb_a_lt'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_seb_a_lt']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/SEB_k_sw.png';
                $gateways['swedbank_v3_seb_a_lt']->id = 'swedbank_v3_seb_a_lt';
                $gateways['swedbank_v3_seb_a_lt']->title = 'SEB';
                $gateways['swedbank_v3_seb_a_lt']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['citadele_lt'] === 'yes') {
            if (!isset($gateways['swedbank_v3_citadele_a_lt'])) {
                $gateways['swedbank_v3_citadele_a_lt'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_citadele_a_lt']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/citadele.png';
                $gateways['swedbank_v3_citadele_a_lt']->id = 'swedbank_v3_citadele_a_lt';
                $gateways['swedbank_v3_citadele_a_lt']->title = 'Citadele';
                $gateways['swedbank_v3_citadele_a_lt']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['luminor_lt'] === 'yes') {
            if (!isset($gateways['swedbank_v3_luminor_a_lt'])) {
                $gateways['swedbank_v3_luminor_a_lt'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_luminor_a_lt']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/luminor.png';
                $gateways['swedbank_v3_luminor_a_lt']->id = 'swedbank_v3_luminor_a_lt';
                $gateways['swedbank_v3_luminor_a_lt']->title = 'Luminor';
                $gateways['swedbank_v3_luminor_a_lt']->description = '';
            }
        }


        if ($gateways['swedbankv3']->settings['swedbank_alone_lv'] === 'yes') {
            if (!isset($gateways['swedbank_v3_swedbank_alone_lv'])) {
                $gateways['swedbank_v3_swedbank_alone_lv'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_swedbank_alone_lv']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/Swedbank.png';
                $gateways['swedbank_v3_swedbank_alone_lv']->id = 'swedbank_v3_swedbank_alone_lv';
                $gateways['swedbank_v3_swedbank_alone_lv']->title = 'Swedbank';
                $gateways['swedbank_v3_swedbank_alone_lv']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['seb_lv'] === 'yes') {
            if (!isset($gateways['swedbank_v3_seb_a_lv'])) {
                $gateways['swedbank_v3_seb_a_lv'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_seb_a_lv']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/SEB_k_sw.png';
                $gateways['swedbank_v3_seb_a_lv']->id = 'swedbank_v3_seb_a_lv';
                $gateways['swedbank_v3_seb_a_lv']->title = 'SEB';
                $gateways['swedbank_v3_seb_a_lv']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['citadele_lv'] === 'yes') {
            if (!isset($gateways['swedbank_v3_citadele_a_lv'])) {
                $gateways['swedbank_v3_citadele_a_lv'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_citadele_a_lv']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/citadele.png';
                $gateways['swedbank_v3_citadele_a_lv']->id = 'swedbank_v3_citadele_a_lv';
                $gateways['swedbank_v3_citadele_a_lv']->title = 'Citadele';
                $gateways['swedbank_v3_citadele_a_lv']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['luminor_lv'] === 'yes') {
            if (!isset($gateways['swedbank_v3_luminor_a_lv'])) {
                $gateways['swedbank_v3_luminor_a_lv'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_luminor_a_lv']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/luminor.png';
                $gateways['swedbank_v3_luminor_a_lv']->id = 'swedbank_v3_luminor_a_lv';
                $gateways['swedbank_v3_luminor_a_lv']->title = 'Luminor';
                $gateways['swedbank_v3_luminor_a_lv']->description = '';
            }
        }


        if ($gateways['swedbankv3']->settings['swedbank_alone_ee'] === 'yes') {
            if (!isset($gateways['swedbank_v3_swedbank_alone_ee'])) {
                $gateways['swedbank_v3_swedbank_alone_ee'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_swedbank_alone_ee']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/Swedbank.png';
                $gateways['swedbank_v3_swedbank_alone_ee']->id = 'swedbank_v3_swedbank_alone_ee';
                $gateways['swedbank_v3_swedbank_alone_ee']->title = 'Swedbank';
                $gateways['swedbank_v3_swedbank_alone_ee']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['seb_ee'] === 'yes') {
            if (!isset($gateways['swedbank_v3_seb_a_ee'])) {
                $gateways['swedbank_v3_seb_a_ee'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_seb_a_ee']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/SEB_k_sw.png';
                $gateways['swedbank_v3_seb_a_ee']->id = 'swedbank_v3_seb_a_ee';
                $gateways['swedbank_v3_seb_a_ee']->title = 'SEB';
                $gateways['swedbank_v3_seb_a_ee']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['citadele_ee'] === 'yes') {
            if (!isset($gateways['swedbank_v3_citadele_a_ee'])) {
                $gateways['swedbank_v3_citadele_a_ee'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_citadele_a_ee']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/citadele.png';
                $gateways['swedbank_v3_citadele_a_ee']->id = 'swedbank_v3_citadele_a_ee';
                $gateways['swedbank_v3_citadele_a_ee']->title = 'Citadele';
                $gateways['swedbank_v3_citadele_a_ee']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['lhv_ee'] === 'yes') {
            if (!isset($gateways['swedbank_v3_lhv_a_ee'])) {
                $gateways['swedbank_v3_lhv_a_ee'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_lhv_a_ee']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/lhvlogo.png';
                $gateways['swedbank_v3_lhv_a_ee']->id = 'swedbank_v3_lhv_a_ee';
                $gateways['swedbank_v3_lhv_a_ee']->title = 'LHV';
                $gateways['swedbank_v3_lhv_a_ee']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['coop_ee'] === 'yes') {
            if (!isset($gateways['swedbank_v3_coop_a_ee'])) {
                $gateways['swedbank_v3_coop_a_ee'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_coop_a_ee']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/Coop.png';
                $gateways['swedbank_v3_coop_a_ee']->id = 'swedbank_v3_coop_a_ee';
                $gateways['swedbank_v3_coop_a_ee']->title = 'Coop';
                $gateways['swedbank_v3_coop_a_ee']->description = '';
            }
        }

        if ($gateways['swedbankv3']->settings['luminor_ee'] === 'yes') {
            if (!isset($gateways['swedbank_v3_luminor_a_ee'])) {
                $gateways['swedbank_v3_luminor_a_ee'] = clone $gateways['swedbankv3'];
                $gateways['swedbank_v3_luminor_a_ee']->icon = home_url().'/wp-content/plugins/swedbank-gateway-v3/image/luminor.png';
                $gateways['swedbank_v3_luminor_a_ee']->id = 'swedbank_v3_luminor_a_ee';
                $gateways['swedbank_v3_luminor_a_ee']->title = 'Luminor';
                $gateways['swedbank_v3_luminor_a_ee']->description = '';
            }
        }


        unset($gateways['swedbankv3']);
    }

    return $gateways;
}

function init_swedbank_v3_lt_gateway_class()
{


    class WC_Gateway_Swedbank_lt_v3 extends WC_Payment_Gateway
    {

        /** @var array Array of locales */
        public $locale;

        public function log($text) {
            $text = print_r($text, true);
            $text = preg_replace("/<password>(.*)<\/password>/","<password>********</password>", $text);

            file_put_contents( __DIR__.'/../../uploads/wc-logs/swedbankv3.log', date("Y-m-d H:i:s") . "\n-----\n$text\n\n", FILE_APPEND | LOCK_EX);
        }

        /**
         * Constructor for the gateway.
         */
        public function __construct()
        {
            $this->id = 'swedbankv3';
            $this->icon = apply_filters('woocommerce_bacs_icon', '');
            $this->has_fields = true;
            $this->method_title = __('Swedbank v3', 'woocommerce');
            $this->method_description = __('Allows payments by Debit/Credit Card and direct bank/wire/banklink transfer using Swedbank.', 'woocommerce');

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title = __('Swedbank v3', 'woocommerce');
            $this->description = $this->get_option('description');
            $this->instructions = $this->get_option('instructions');

            $sw_cer_lt = '-----BEGIN CERTIFICATE-----
MIIEDjCCAvagAwIBAgITVwAAAh/PBfcG6yBi9gAAAAACHzANBgkqhkiG9w0BAQUF
ADBOMQswCQYDVQQGEwJTRTEXMBUGA1UEChMOU3dlZGJhbmsgR3JvdXAxJjAkBgNV
BAMTHVN3ZWRiYW5rIEczIElzc3VpbmcgQ0EgVGllciBBMB4XDTE3MTEwNjA5MTAw
OVoXDTIwMTEwNTA5MTAwOVowODELMAkGA1UEBhMCTFQxETAPBgNVBAoTCFN3ZWRi
YW5rMRYwFAYDVQQDEw1CYW5rbGluayBIb3N0MIGfMA0GCSqGSIb3DQEBAQUAA4GN
ADCBiQKBgQDRaR94bIp05bc/o2ccvWmwuUEfN1WFUPL0wMXN1Wv1rWX68ay7liS/
LBzc3gBq9ungBLlFfaYxBohcJf43gNiZPzdUkBcXJnTeDZxdUzuRuzHA+JOyWqbt
4lcZ4K1l405LJsl5qaXApendeftIN2RpcCK/59Oqyu6thK05JB1HRQIDAQABo4IB
fTCCAXkwHQYDVR0OBBYEFGT0PNTdXiM+/v9Yd2xGD1qDoYdzMB8GA1UdIwQYMBaA
FAihXNOvBjSLItpZ/Qg6KAQ9wIRpMFQGA1UdHwRNMEswSaBHoEWGQ2h0dHA6Ly9p
bmZyYS5zd2VkYmFuay5jb20vcGtpL2NybC9Td2VkYmFua19HM19Jc3N1aW5nX0NB
X1RpZXJfQS5jcmwwXwYIKwYBBQUHAQEEUzBRME8GCCsGAQUFBzAChkNodHRwOi8v
aW5mcmEuc3dlZGJhbmsuY29tL3BraS9jcnQvU3dlZGJhbmtfRzNfSXNzdWluZ19D
QV9UaWVyX0EuY3J0MAsGA1UdDwQEAwIHgDA9BgkrBgEEAYI3FQcEMDAuBiYrBgEE
AYI3FQiD+8FigsjKGYf1jQeCwoAkgebsfGCCo+dNg/3yXAIBZAIBAzAVBgNVHSUE
DjAMBgorBgEEAYI3CgMMMB0GCSsGAQQBgjcVCgQQMA4wDAYKKwYBBAGCNwoDDDAN
BgkqhkiG9w0BAQUFAAOCAQEAPLcOnG0E3TH6w7wNu8TMsHGAy5jVo/KDQIwnFc1r
Wib679AWpNLkW9aiVghs+9xa+7Al2JFO83fbAwnwSCacif4UGodmdj7drAwwINsI
m4QiMBRY0c34FmokUxB88N8G/+qzKLMMZDL7ljEWtz8KZY31If4RTXTylMcLpU1r
2Y9lH/HH+fr+5wDXt/t+ikvbc2tEH6b+rByfjts7CGMXThb9QLRHnz5WwihYHmiC
iaXXIZr5BBYjzTQIgv9GD0JziRvhaD28Oeym394ICzxZJl3XzG25dY2KJNm1HWLu
u8n2e1CNTanCBA1Bv1S4V0OdyidLaTuJZ0y7ODa3+8trFQ==
-----END CERTIFICATE-----';
            $sw_cer_lv = '-----BEGIN CERTIFICATE-----
MIIEDjCCAvagAwIBAgITVwAAAiAdXtn3OPXGNwAAAAACIDANBgkqhkiG9w0BAQUF
ADBOMQswCQYDVQQGEwJTRTEXMBUGA1UEChMOU3dlZGJhbmsgR3JvdXAxJjAkBgNV
BAMTHVN3ZWRiYW5rIEczIElzc3VpbmcgQ0EgVGllciBBMB4XDTE3MTEwNjA5MTIy
NloXDTIwMTEwNTA5MTIyNlowODELMAkGA1UEBhMCTFYxETAPBgNVBAoTCFN3ZWRi
YW5rMRYwFAYDVQQDEw1CYW5rbGluayBIb3N0MIGfMA0GCSqGSIb3DQEBAQUAA4GN
ADCBiQKBgQDE+w2KupA9quH11ej1NAfczkL7TNmeHynzhNksmmtYtYNAuw3VmUzY
JoKb2o5RoOQ1bizVBKTOKbSIexcLaLrGk/KeOm+jZSDusiF/HXm0rz/pTBmhIG8G
lLCVH7u6E0huJP5scoaQuBtpWur2Y4bneKiETudK2GrrsTYcKdiwYQIDAQABo4IB
fTCCAXkwHQYDVR0OBBYEFFyIMWI8qDLQNzDOMOMMP75WcQDfMB8GA1UdIwQYMBaA
FAihXNOvBjSLItpZ/Qg6KAQ9wIRpMFQGA1UdHwRNMEswSaBHoEWGQ2h0dHA6Ly9p
bmZyYS5zd2VkYmFuay5jb20vcGtpL2NybC9Td2VkYmFua19HM19Jc3N1aW5nX0NB
X1RpZXJfQS5jcmwwXwYIKwYBBQUHAQEEUzBRME8GCCsGAQUFBzAChkNodHRwOi8v
aW5mcmEuc3dlZGJhbmsuY29tL3BraS9jcnQvU3dlZGJhbmtfRzNfSXNzdWluZ19D
QV9UaWVyX0EuY3J0MAsGA1UdDwQEAwIHgDA9BgkrBgEEAYI3FQcEMDAuBiYrBgEE
AYI3FQiD+8FigsjKGYf1jQeCwoAkgebsfGCCo+dNg/3yXAIBZAIBAzAVBgNVHSUE
DjAMBgorBgEEAYI3CgMMMB0GCSsGAQQBgjcVCgQQMA4wDAYKKwYBBAGCNwoDDDAN
BgkqhkiG9w0BAQUFAAOCAQEAUaoqhAEsdng2o0HRcydmg8ktjWo3uaukbHlHRvZH
etCADj5eVlt90ra981AEDAGjJsss1mnVTyQhNLTWmp1B8rS4QzctWn4gzpEfdO77
mI+cCzSdFPmHDMagAb/rAFu+qRsYV7oC+nscvTFLIBrr19ABsQwTMV4krsmHmbTX
rQO24rcJQ+cSwSz02hd8cyuHs7lrOXBtjSGMKyxkXh7ZBGHULjfgXmv8Anp7keAU
L4kd0XhkcpoP68btDIR3/nurIz2QpJbR6Me/+NYxK4NU6DY9aaZAZBZm6lYn/OTi
Dq9rIW1o3bLpig80lD3M/uusx7CzoGfo45YKopEsw5Tklg==
-----END CERTIFICATE-----';
            $sw_cer_ee = '-----BEGIN CERTIFICATE-----
MIIEDjCCAvagAwIBAgITVwAAAh6+2vl9fqWDTwAAAAACHjANBgkqhkiG9w0BAQUF
ADBOMQswCQYDVQQGEwJTRTEXMBUGA1UEChMOU3dlZGJhbmsgR3JvdXAxJjAkBgNV
BAMTHVN3ZWRiYW5rIEczIElzc3VpbmcgQ0EgVGllciBBMB4XDTE3MTEwNjA5MDc0
NFoXDTIwMTEwNTA5MDc0NFowODELMAkGA1UEBhMCRUUxETAPBgNVBAoTCFN3ZWRi
YW5rMRYwFAYDVQQDEw1CYW5rbGluayBIb3N0MIGfMA0GCSqGSIb3DQEBAQUAA4GN
ADCBiQKBgQCxSrHPphy8fR9ryqnJqXvk8clhTMcUM1ce03mec/8l8VW6Z8I0n5dc
ytAfJogJ03aaNcgxIkft8S1z8cYTDhkMGKKTGpeMntYTQ9eVW1yjWWCWRM6B2U0U
0ezQ44Aysl393t4BCofmSzOUJZQduinojkiIPgqwokpuOfi61E+qjwIDAQABo4IB
fTCCAXkwHQYDVR0OBBYEFPXf1N6RPeXvFb5rdq1uTfCTs04vMB8GA1UdIwQYMBaA
FAihXNOvBjSLItpZ/Qg6KAQ9wIRpMFQGA1UdHwRNMEswSaBHoEWGQ2h0dHA6Ly9p
bmZyYS5zd2VkYmFuay5jb20vcGtpL2NybC9Td2VkYmFua19HM19Jc3N1aW5nX0NB
X1RpZXJfQS5jcmwwXwYIKwYBBQUHAQEEUzBRME8GCCsGAQUFBzAChkNodHRwOi8v
aW5mcmEuc3dlZGJhbmsuY29tL3BraS9jcnQvU3dlZGJhbmtfRzNfSXNzdWluZ19D
QV9UaWVyX0EuY3J0MAsGA1UdDwQEAwIHgDA9BgkrBgEEAYI3FQcEMDAuBiYrBgEE
AYI3FQiD+8FigsjKGYf1jQeCwoAkgebsfGCCo+dNg/3yXAIBZAIBAzAVBgNVHSUE
DjAMBgorBgEEAYI3CgMMMB0GCSsGAQQBgjcVCgQQMA4wDAYKKwYBBAGCNwoDDDAN
BgkqhkiG9w0BAQUFAAOCAQEAco/ZCin6P5GqnAmkn/lKVLZvTnBeg9lsh0Y54sQr
KyXDJyBfJRjml1tGsFPSsi4kX0TjvQABo2o4v/JyRM5Kk1G+ytwOBzMk1oxEAvGj
/83oyPl8Ch483c3TJt9d5s9YKPtaPWgVgvhQpHWhoUwqO+AHhz2KWnoTVeewLa1Z
97ZVlp7J9gN9jmwjGEn/jOEaSPtrh4igF8OdqKILxTHeynN+30nDM9D5Z+KkGC3T
+ZWi5Ivtmzxwh9xNBMmBJDfU7aUG9FKpbJqUmMh6ddZFUjMW3DixALA2JIobiAy8
dkI0O60W+YGbfkkSv5ymwZjSI3k2XK75xitnEH8x/gO15w==
-----END CERTIFICATE-----';

            if (empty($this->settings['publickey_lt'])) {
                $this->settings['publickey_lt'] = $sw_cer_lt;
            }
            if (empty($this->settings['publickey_lv'])) {
                $this->settings['publickey_lv'] = $sw_cer_lv;
            }
            if (empty($this->settings['publickey_ee'])) {
                $this->settings['publickey_ee'] = $sw_cer_ee;
            }

            /*if(
                empty($this->settings['privatekey_lt']) &&
                empty($this->settings['cert_req_lt']) &&
                empty($this->settings['certificate_lt']) &&
                !empty($this->settings['stateOrProvinceNamee_lt']) &&
                !empty($this->settings['localityName_lt']) &&
                !empty($this->settings['organizationName_lt']) &&
                !empty($this->settings['organizationalUnitName_lt']) &&
                !empty($this->settings['commonName_lt']) &&
                !empty($this->settings['emailAddress_lt'])
            ){
                $cert = $this->generateCert('LT', $this->settings);
                $this->settings['privatekey_lt'] = $cert[0];
                $this->settings['certificate_lt'] = $cert[1];
                $this->settings['cert_req_lt'] = $cert[2];
            }

            if(
                empty($this->settings['privatekey_lv']) &&
                empty($this->settings['cert_req_lv']) &&
                empty($this->settings['certificate_lv']) &&
                !empty($this->settings['stateOrProvinceNamee_lv']) &&
                !empty($this->settings['localityName_lv']) &&
                !empty($this->settings['organizationName_lv']) &&
                !empty($this->settings['organizationalUnitName_lv']) &&
                !empty($this->settings['commonName_lv']) &&
                !empty($this->settings['emailAddress_lv'])
            ){
                $cert = $this->generateCert('LV', $this->settings);
                $this->settings['privatekey_lv'] = $cert[0];
                $this->settings['certificate_lv'] = $cert[1];
                $this->settings['cert_req_lv'] = $cert[2];
            }

            if(
                empty($this->settings['privatekey_ee']) &&
                empty($this->settings['cert_req_ee']) &&
                empty($this->settings['certificate_ee']) &&
                !empty($this->settings['stateOrProvinceNamee_ee']) &&
                !empty($this->settings['localityName_ee']) &&
                !empty($this->settings['organizationName_ee']) &&
                !empty($this->settings['organizationalUnitName_ee']) &&
                !empty($this->settings['commonName_ee']) &&
                !empty($this->settings['emailAddress_ee'])
            ){
                $cert = $this->generateCert('EE', $this->settings);
                $this->settings['privatekey_ee'] = $cert[0];
                $this->settings['certificate_ee'] = $cert[1];
                $this->settings['cert_req_ee'] = $cert[2];
            }*/
            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            add_action('woocommerce_thankyou_swedbank_v3_lt', array($this, 'thankyou_page'));

            add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
        }

        private function generateCert($country = 'LT', $settings)
        {

            //swedbank_stateOrProvinceNamee_ee swedbank_localityName_ee swedbank_organizationName_ee
            // swedbank_organizationalUnitName_ee swedbank_commonName_ee swedbank_emailAddress_ee
            $kal = strtolower($country);
            $dn = array(
                "countryName" => $country,
                "stateOrProvinceName" => $settings['stateOrProvinceNamee_' . $kal],
                "localityName" => $settings['localityName_' . $kal],
                "organizationName" => $settings['organizationName_' . $kal],
                "organizationalUnitName" => $settings['organizationalUnitName_' . $kal],
                "commonName" => $settings['commonName_' . $kal],
                "emailAddress" => $settings['emailAddress_' . $kal]
            );

// Generate a new private (and public) key pair
            $privkey = openssl_pkey_new(array(
                "private_key_bits" => (int)2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ));

// Generate a certificate signing request
            $csr = openssl_csr_new($dn, $privkey, array('digest_alg' => 'sha512'));

// Generate a self-signed cert, valid for 3 years
            $x509 = openssl_csr_sign($csr, null, $privkey, $days = 1095, array('digest_alg' => 'sha512'));

// Save your private key, CSR and self-signed cert for later use
            openssl_csr_export($csr, $csrout);
            openssl_x509_export($x509, $certout);
            openssl_pkey_export($privkey, $pkeyout);

            return [$pkeyout, $certout, $csrout];
        }

        /**
         * Initialise Gateway Settings Form Fields.
         */
        public function init_form_fields()
        {

            $this->form_fields = array(


                'general_settings' => array(
                    'id' => 'general_settings',
                    'type' => 'general_settings',
                    'title' => __('General settings', 'woocommerce'),
                ),
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('To use this plugin you need to accept <a href="https://ib.swedbank.lt/static/pdf/business/cash/cashflow/cardservice/TC_for_the_use_of_Swedbank_Payment_Portal_WooCommerce_Module_v20170421.pdf" target="_blank">terms and conditions</a>', 'woocommerce'),
                    'default' => 'no'
                ),
                'debuging' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Debugging', 'woocommerce'),
                    'description' => __('Storing transaction XML to logs. This should be turned ON only if needed to track where operation fails, otherwise keep disabled. (wp-content/uploads/wc-logs/swedbankv3.log)', 'woocommerce'),
                    'default' => 'no'
                ),
                'order_status' => array(
                    'title' => __('Order status after payment', 'woocommerce'),
                    'type' => 'select',
                    'options' => wc_get_order_statuses(),
                ),
                'instructions' => array(
                    'title' => __('Other information', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Other information will be visible on the thank you page and emails.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'spp_label' => array(
                    'id' => 'spp_label',
                    'type' => 'spp_label',
                    'title' => __('SPP settings', 'woocommerce'),
                ),
                'label' => array(
                    'id' => 'label',
                    'type' => 'label',
                    'title' => __('For SPP', 'woocommerce'),
                ),
                'card_lt' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Card payment', 'woocommerce'),
                    'default' => 'no'
                ),
                'swedbank_v3_lt' => array(
                    'title' => __('Bank Link', 'woocommerce'),
                    'type' => 'select',
                    'options' => array(
                        '0' => 'Only card payment signed using SPP contract',
                        '1' => 'Swedbank bank link for Lithuania',
                        '2' => 'Swedbank bank link for Latvia',
                        '3' => 'Swedbank bank link for Estonia'
                    )
                ),

                'testmode_lt' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Test mode', 'woocommerce'),
                    'default' => 'no'
                ),
                'testvtid_lt' => array(
                    'title' => __('Test ID / vTID', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('This information you got in you welcome email from Swedbank. If you don\'t have this information please contact Swedbank.', 'woocommerce'),
                    'desc_tip' => true,
                    'required' => true
                ),
                'testpass_lt' => array(
                    'title' => __('Test Password', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('This information you got in you welcome email from Swedbank. If you don\'t have this information please contact Swedbank.', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'vtid_lt' => array(
                    'title' => __('ID / vTID', 'woocommerce'),
                    'type' => 'text',
                    'default' => __('', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'pass_lt' => array(
                    'title' => __('Password', 'woocommerce'),
                    'type' => 'text',
                    'default' => __('', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'mbbl_label_lt' => array(
                    'id' => 'mbbl_label_lt',
                    'type' => 'mbbl_label_lt',
                    'title' => __('MBBL settings for LT', 'woocommerce'),
                ),
                'swedbank_alone_lt' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Stand alone Swedbank banklink', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'seb_lt' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('SEB Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'citadele_lt' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Citadele Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'luminor_lt' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Luminor Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                /*'stateOrProvinceNamee_lt' => array(
                    'title' => __('State or province name ', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'localityName_lt' => array(
                    'title' => __('Locality name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'organizationName_lt' => array(
                    'title' => __('Organization name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'organizationalUnitName_lt' => array(
                    'title' => __('Organizational unit name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'commonName_lt' => array(
                    'title' => __('Common name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'emailAddress_lt' => array(
                    'title' => __('Email address', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),*/
                'seller_id_lt' => array(
                    'title' => __('Seller ID', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'privatekey_lt' => array(
                    'title' => __('Private key', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                /*'certificate_lt' => array(
                    'title' => __('Certificate (Send this to swedbank)', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'cert_req_lt' => array(
                    'title' => __('Certificate request (Send this to Swedbank)', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'publickey_lt' => array(
                    'title' => __('Swedbank public key', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),*/

                'mbbl_label_lv' => array(
                    'id' => 'mbbl_label_lv',
                    'type' => 'mbbl_label_lv',
                    'title' => __('MBBL settings for LV', 'woocommerce'),
                ),
                'swedbank_alone_lv' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Stand alone Swedbank banklink', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'seb_lv' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('SEB Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'citadele_lv' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Citadele Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'luminor_lv' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Luminor Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                /*'stateOrProvinceNamee_lv' => array(
                    'title' => __('State or province name ', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'localityName_lv' => array(
                    'title' => __('Locality name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'organizationName_lv' => array(
                    'title' => __('Organization name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'organizationalUnitName_lv' => array(
                    'title' => __('Organizational unit name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'commonName_lv' => array(
                    'title' => __('Common name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'emailAddress_lv' => array(
                    'title' => __('Email address', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),*/
                'seller_id_lv' => array(
                    'title' => __('Seller ID', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'privatekey_lv' => array(
                    'title' => __('Private key', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                /*'certificate_lv' => array(
                    'title' => __('Certificate (Send this to swedbank)', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'cert_req_lv' => array(
                    'title' => __('Certificate request (Send this to Swedbank)', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'publickey_lv' => array(
                    'title' => __('Swedbank public key', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),*/


                'mbbl_label_ee' => array(
                    'id' => 'mbbl_label_ee',
                    'type' => 'mbbl_label_ee',
                    'title' => __('MBBL settings for EE', 'woocommerce'),
                ),
                'swedbank_alone_ee' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Stand alone Swedbank banklink', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'seb_ee' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('SEB Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'citadele_ee' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Citadele Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'lhv_ee' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('LHV Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'coop_ee' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('AS Coop Pank Eesti Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                'luminor_ee' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Luminor Payment Initiation', 'woocommerce'),
                    'description' => '',
                    'default' => 'no'
                ),
                /*'stateOrProvinceNamee_ee' => array(
                    'title' => __('State or province name ', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'localityName_ee' => array(
                    'title' => __('Locality name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'organizationName_ee' => array(
                    'title' => __('Organization name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'organizationalUnitName_ee' => array(
                    'title' => __('Organizational unit name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'commonName_ee' => array(
                    'title' => __('Common name', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'emailAddress_ee' => array(
                    'title' => __('Email address', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),*/
                'seller_id_ee' => array(
                    'title' => __('Seller ID', 'woocommerce'),
                    'type' => 'text',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'privatekey_ee' => array(
                    'title' => __('Private key', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
               /* 'certificate_ee' => array(
                    'title' => __('Certificate (Send this to swedbank)', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'cert_req_ee' => array(
                    'title' => __('Certificate request (Send this to Swedbank)', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),
                'publickey_ee' => array(
                    'title' => __('Swedbank public key', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => '',
                    'desc_tip' => false,
                    'required' => false
                ),*/




            );
        }

        /**
         * $this->doDone(true, $order, $list->ID);
         * Output for the order received page.
         *
         * @param int $order_id
         */
        public function thankyou_page($order_id)
        {
            if ($this->instructions) {
                echo wpautop(wptexturize(wp_kses_post($this->instructions)));
            }
            $this->bank_details($order_id);
        }

        public function cronjob()
        {
            include "includes/query.php";

            $query = new swedbank_v3_query($this);

            global $wpdb;

            $date_from = date('Y-m-d H:i:s', strtotime('now -3 hours'));
            $date_to = date('Y-m-d H:i:s', strtotime('now'));
            $post_status = implode("','", array('wc-processing', 'wc-on-hold', 'wc-pending'));

            $result = $wpdb->get_results("SELECT * FROM $wpdb->posts  LEFT JOIN `{$wpdb->prefix}swedbank_orderlist` ON ID = orderidcart
            WHERE post_status IN ('{$post_status}')
            AND post_date BETWEEN '{$date_from}' AND '{$date_to}'  ");

            //$result = $wpdb->get_results("SELECT * FROM  `{$wpdb->prefix}swedbank_orderlist` ");

            // print_r($result);
            if (!empty($result)) {
                foreach ($result as $list) {

                    $order = wc_get_order($list->ID);

                    $rez = $query->query($list->merchantreference, $order->get_payment_method());
                    //var_dump($rez);
                    if ($rez) {
                        $this->doDone(true, $order, $list->ID, $rez);
                    }


                }
            }


        }

        /**
         * @return mixed
         */
        public function notification()
        {
            include "includes/query.php";

            $query = new swedbank_v3_query($this);

            $xml = trim(file_get_contents('php://input'));
            try {
                $object = new SimpleXMLElement($xml);
            } catch (Exception $exc) {
                die('<Response>OK</Response>');
            }

            if (isset($object) && isset($object->Event) && isset($object->Event->Purchase)) {

                $oId = $object->Event->Purchase[0]->attributes()['TransactionId'];

                $order = wc_get_order(explode('_', $oId)[1]);

                $rez = $query->query($oId, $order->get_payment_method());

                if ($rez) {
                    $this->doDone(true, $order, $oId, $rez);
                }
                //-----

            }

            die('<Response>OK</Response>');
        }

        public function mbbl()
        {

            $home_url = home_url();
            include 'includes/mbanklink.php';
            $order = wc_get_order( $_GET['order_id']);
            $ob =  new swedbank_v3_mbanklink($order, $this, $home_url);
            echo $ob->setupCon();
            die;

        }

        /**
         * Add content to the WC emails.
         *
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
        public function email_instructions($order, $sent_to_admin, $plain_text = false)
        {

            if (!$sent_to_admin && ('swedbank_v3_card_lt' === $order->get_payment_method() || 'swedbank_v3_card_lv' === $order->get_payment_method() || 'swedbank_v3_card_ee' === $order->get_payment_method())) {
                $html = '';
                if ($_POST['status'] === 'SUCCESS' && !empty($_POST['authcode'])) {
                    $authcode = stripcslashes($_POST['authcode']);
                    $pan = stripcslashes($_POST['pan']);
                    $fulfill_date = stripcslashes($_POST['fulfill_date']);
                    $merchant_reference = stripcslashes($_POST['merchant_reference']);

                    $html = '<b>' . __('Shop URL', 'swedbank-plugin') . ':</b> ' . home_url() . '<br>
                                <b>' . __('Transaction Date', 'swedbank-plugin') . ':</b> ' . date("Y-m-d H:i:s", strtotime($fulfill_date)) . '<br>
                                <b>' . __('Order ID', 'swedbank-plugin') . ': </b>' . $merchant_reference . '<br>
                                <b>' . __('Paid By', 'swedbank-plugin') . ':</b> ' . $pan . '<br>
                                <b>' . __('Auth Code', 'swedbank-plugin') . ':</b> ' . $authcode . '<br>';

                } else {
                }

                if ($this->instructions) {
                    echo wpautop(wptexturize($this->instructions)) . PHP_EOL;
                }

                if (!empty($html)) {
                    echo wpautop(wptexturize($html)) . PHP_EOL;
                }

                $this->bank_details($order->get_id());
            }
        }

        /**
         * Get bank details and place into a list format.
         *
         * @param int $order_id
         */
        private function bank_details($order_id = '')
        {

            if (empty($this->account_details)) {
                return;
            }

            // Get order and store in $order
            $order = wc_get_order($order_id);

            // Get the order country and country $locale
            $country = $order->get_billing_country();
            $locale = $this->get_country_locale();

            // Get sortcode label in the $locale array and use appropriate one
            $sortcode = isset($locale[$country]['sortcode']['label']) ? $locale[$country]['sortcode']['label'] : __('Sort code', 'woocommerce');

            $swedbank_v3_lt_accounts = apply_filters('woocommerce_swedbank_v3_lt_accounts', $this->account_details);

            if (!empty($swedbank_v3_lt_accounts)) {
                $account_html = '';
                $has_details = false;

                foreach ($swedbank_v3_lt_accounts as $swedbank_v3_lt_account) {
                    $swedbank_v3_lt_account = (object)$swedbank_v3_lt_account;

                    if ($swedbank_v3_lt_account->account_name) {
                        $account_html .= '<h3 class="wc-swedbank_v3_lt-bank-details-account-name">' . wp_kses_post(wp_unslash($swedbank_v3_lt_account->account_name)) . ':</h3>' . PHP_EOL;
                    }

                    $account_html .= '<ul class="wc-swedbank_v3_lt-bank-details order_details swedbank_v3_lt_details">' . PHP_EOL;

                    // swedbank_v3_lt account fields shown on the thanks page and in emails
                    $account_fields = apply_filters('woocommerce_swedbank_v3_lt_account_fields', array(
                        'bank_name' => array(
                            'label' => __('Bank', 'woocommerce'),
                            'value' => $swedbank_v3_lt_account->bank_name,
                        ),
                        'account_number' => array(
                            'label' => __('Account number', 'woocommerce'),
                            'value' => $swedbank_v3_lt_account->account_number,
                        ),
                        'sort_code' => array(
                            'label' => $sortcode,
                            'value' => $swedbank_v3_lt_account->sort_code,
                        ),
                        'iban' => array(
                            'label' => __('IBAN', 'woocommerce'),
                            'value' => $swedbank_v3_lt_account->iban,
                        ),
                        'bic' => array(
                            'label' => __('BIC', 'woocommerce'),
                            'value' => $swedbank_v3_lt_account->bic,
                        ),
                    ), $order_id);

                    foreach ($account_fields as $field_key => $field) {
                        if (!empty($field['value'])) {
                            $account_html .= '<li class="' . esc_attr($field_key) . '">' . wp_kses_post($field['label']) . ': <strong>' . wp_kses_post(wptexturize($field['value'])) . '</strong></li>' . PHP_EOL;
                            $has_details = true;
                        }
                    }

                    $account_html .= '</ul>';
                }

                if ($has_details) {
                    echo '<section class="woocommerce-swedbank_v3_lt-bank-details"><h2 class="wc-swedbank_v3_lt-bank-details-heading">' . __('Our bank details', 'woocommerce') . '</h2>' . PHP_EOL . $account_html . '</section>';
                }
            }
        }

        /**
         * Process the payment and return the result.
         *
         * @param int $order_id
         * @return array
         */
        public function process_payment($order_id)
        {
            require_once "includes/class-wc-gateway-swedbank-integration.php";
            global $wpdb;

            $order = wc_get_order($order_id);

            $ob = new WC_Gateway_Swedbank_Integration($order, $this);
            $home_url = home_url();

            $url = $ob->get_url($home_url);

            if (!$url) {
                return array(
                    'result' => 'failure',
                    'message' => "<ul class=\"woocommerce-error\">\n\t\t\t<li><strong>Error:<\/strong> payment method failed.<\/li>\n\t\t\t<\/ul>\n",
                    "refresh" => false,
                    "reload" => false
                );
            }  else {


                //$url[1] = $wpdb->_real_escape($url[1]);
                //$url[2] = $wpdb->_real_escape($url[2]);

                //$wpdb->get_results("INSERT INTO `{$wpdb->prefix}swedbank_orderlist` ('orderidcart', 'merchantreference') VALUES ('{$url[1]}', '{$url[2]}') ");
                $wpdb->insert($wpdb->prefix . 'swedbank_orderlist', ['orderidcart' => $url[1], 'merchantreference' => $url[2]]);

                if(!is_array($url)){
                    return array(
                        'result' => 'success',
                        'redirect' => $url,
                    );
                }

                return array(
                    'result' => 'success',
                    'redirect' => $url[0],
                );
            }
        }

        public function doDone($n = false, $ob = null, $idn = null, $result = null)
        {
            require_once "includes/class-wc-gateway-swedbank-integration.php";

            if (!$this->settings['order_status'] || empty($this->settings['order_status'])) {
                $this->settings['order_status'] = 'wc-completed';
            }

            try {

                $order_status_list = wc_get_order_statuses();


                if ($ob === null) {
                    $id = explode('_', $_GET['order_id'])[1];
                    $pmmm = $_GET['pmmm'];
                    $order = wc_get_order($id);
                } else {
                    $id = $idn;
                    $pmmm = str_replace('swedbank_v3_', '', $ob->get_payment_method());
                    $order = $ob;
                }

                if ($order->get_status() == 'wc-completed') {
                    if (!$n)
                        wp_redirect($order->get_checkout_order_received_url());
                    exit;
                }

                $success = false;

                $ob = new WC_Gateway_Swedbank_Integration($order, $this);


                if ($pmmm === 'swedbank_v3_card_lt') {
                    if ($result === null)
                        $result = $ob->getDone();
                    if ($result === 1) {
                        $success = true;
                        $order->update_status($this->settings['order_status'], __($order_status_list[$this->settings['order_status']], 'woocommerce'));
                        // Reduce stock levels
                        wc_reduce_stock_levels($id);
                    }
                }
                if ($pmmm === 'swedbank_v3_swedbank_v3_1' || $pmmm === 'swedbank_v3_swedbank_v3_2' || $pmmm === 'swedbank_v3_swedbank_v3_3') {
                    if ($result === null)
                        $result = $ob->getDoneB();
                    if ($result === 'AUTHORISED' || $result === 'ACCEPTED') {
                        $success = true;
                        $order->update_status($this->settings['order_status'], __($order_status_list[$this->settings['order_status']], 'woocommerce'));
                        wc_reduce_stock_levels($id);
                    } else if ($result === 'REQUIRES_INVESTIGATION') {

                        $order->update_status('on-hold', __('Requires investigation', 'woocommerce'));
                        if (!$n) {
                            wc_add_notice(__('Requires investigation:', 'woocommerce') . ' automatic confirmation failed. Need manual confirmation. Please contact shop administrator and provide this order ID: ' . $id . '. ', 'error');
                            if (!$n)
                                wp_redirect($order->get_checkout_payment_url());
                            exit;
                        }
                    } else if ($result === 'PENDING') {
                        //pending
                        $success = true;
                        $order->update_status('processing', __('Processing', 'woocommerce'));
                        //wc_reduce_stock_levels($id);
                        WC()->cart->empty_cart();
                        if (!$n) {
                            wc_add_notice(__('Payment in progress:', 'woocommerce') . ' Waiting for confirmation from bank.', 'success');
                            if (!$n)
                                wp_redirect($order->get_checkout_order_received_url());
                            exit;
                        }
                    } else if ($result === 'CANCELLED') {
                        $order->update_status('cancelled', __('Cancelled', 'woocommerce'));
                        if (!$n) {
                            wc_add_notice(__('Canceled:', 'woocommerce') . ' payment is canceled.', 'notice');
                            if (!$n)
                                wp_redirect($order->get_cancel_order_url());
                            exit;
                        }
                    } else {
                        $order->update_status('failed', __('Failed', 'woocommerce'));
                    }
                } else {

                }
            } catch (Exception $e){
                $this->settings['debuging'] === 'yes' ? $this->log->logData($e) : null;
            }


            if ($success) {
                //$order->update_status('on-hold', __('Waiting confirmation', 'woocommerce'));
                // Remove cart
                WC()->cart->empty_cart();
                if (!$n) {
                    wp_redirect($order->get_checkout_order_received_url());
                    exit;
                }
            } else {
                if (!$n) {
                    wc_add_notice(__('Payment error:', 'woocommerce') . ' please try later.', 'error');
                    wp_redirect($order->get_checkout_payment_url());
                    exit;
                }
            }
        }

        public function doDoneC($n = false, $ob = null, $idn = null, $result = null)
        {

            require 'includes/mbbl/Protocol/Protocol.php';
            $lng = $_GET['lv'];
            try {


                $protocol = new Protocol(
                    trim($this->settings['seller_id_' . $lng]), // seller ID (VK_SND_ID)
                    trim($this->settings['privatekey_' . $lng]), // private key
                    '', // private key password, leave empty, if not neede
                    trim($this->settings['publickey_' . $lng]), // public key
                    '' // return url
                );

                require 'includes/mbbl/Banklink.php';
                $banklink = new Banklink($protocol);

                $this->settings['debuging'] === 'yes' ? $this->log->logData('POST: ' . print_r($_POST, true)) : null;
                $this->settings['debuging'] === 'yes' ? $this->log->logData('GET: ' . print_r($_GET, true)) : null;

                $r = $banklink->handleResponse(empty($_POST) ? $_GET : $_POST);

                if (!$this->settings['order_status'] || empty($this->settings['order_status'])) {
                    $this->settings['order_status'] = 'wc-completed';
                }

                if ($r->wasSuccessful()) {
                    $id = $_GET['order_id'];
                    $pmmm = $_GET['pmmm'];
                    $order = wc_get_order($id);
                    $order_status_list = wc_get_order_statuses();
                    $order->update_status($this->settings['order_status'], __($order_status_list[$this->settings['order_status']], 'woocommerce'));
                    // Reduce stock levels
                    wc_reduce_stock_levels($id);
                    wp_redirect($order->get_checkout_order_received_url());
                } else {
                    $id = $_GET['order_id'];
                    $pmmm = $_GET['pmmm'];
                    $order = wc_get_order($id);
                    $order->update_status('failed', __('Failed', 'woocommerce'));
                    wc_add_notice(__('Payment error:', 'woocommerce') . ' please try later.', 'error');
                    wp_redirect($order->get_checkout_payment_url());
                }
            } catch (Exception $e){
                $this->settings['debuging'] === 'yes' ? $this->log->logData($e) : null;
            }

        }

        public function doFinish()
        {
            $id = explode('_', $_GET['order_id'])[1];
            $order = wc_get_order($id);

            if ($_POST['status'] === 'SUCCESS') {
                $card_data = stripcslashes($_POST['authcode']);

                if (isset($card_data)) {
                    $i = $card_data;
                } else {
                    $i = '';
                }


                if (!empty($i)) {
                    $order->payment_complete($i);
                }
            }
        }

        public function generate_general_settings_html($key, $value)
        {

            $field = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class' => 'button-secondary',
                'css' => '',
                'custom_attributes' => array(),
                'desc_tip' => false,
                'description' => '',
                'title' => '',
            );

            $value = wp_parse_args($value, $defaults);

            ob_start();
            ?>
            <tr style="border-bottom: 1px solid #000">
                <th scope="row" class="titledesc">
                    <h1>General settings</h1>
                </th>
                <td class="forminp">

                </td>
            </tr>
            <?php
            return ob_get_clean();
        }

        public function generate_spp_label_html($key, $value)
        {
            $field = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class' => 'button-secondary',
                'css' => '',
                'custom_attributes' => array(),
                'desc_tip' => false,
                'description' => '',
                'title' => '',
            );

            $value = wp_parse_args($value, $defaults);

            ob_start();
            ?>
            <tr style="border-bottom: 1px solid #000">
                <th scope="row" class="titledesc">
                    <h1>SPP</h1>
                </th>
                <td class="forminp">
                    If signed with Swedbank SPP contract
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }

        public function generate_mbbl_label_lt_html($key, $value)
        {
            $field = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class' => 'button-secondary',
                'css' => '',
                'custom_attributes' => array(),
                'desc_tip' => false,
                'description' => '',
                'title' => '',
            );

            $value = wp_parse_args($value, $defaults);

            ob_start();
            ?>
            <tr style="border-bottom: 1px solid #000">
                <th scope="row" colspan="2" class="titledesc">
                    <h1>Bank Link Payment Initiation</h1>
                </th>
            </tr>
            <tr>
                <td class="forminp" colspan="2">
                    <div class="alert alert-info col-lg-offset-2">*Bellow this point settings are for stand alone
                        Swedbank Bank Link including Payment Initiation. Plese fill if yuo have signed contract for this
                        product.
                    </div>
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #000">
                <th scope="row" class="titledesc">
                    <h1>For Lithuania</h1>
                </th>
                <td class="forminp">
                    Signed contract with Swedbank Lithuania for stand alone Swedbank Bank Link and/or Payment Initiation
                </td>
            </tr>
            
            <?php
            return ob_get_clean();
        }

        public function generate_mbbl_label_lv_html($key, $value)
        {
            $field = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class' => 'button-secondary',
                'css' => '',
                'custom_attributes' => array(),
                'desc_tip' => false,
                'description' => '',
                'title' => '',
            );

            $value = wp_parse_args($value, $defaults);

            ob_start();
            ?>
            <tr style="border-bottom: 1px solid #000">
                <th scope="row" class="titledesc">
                    <h1>For Latvia</h1>
                </th>
                <td class="forminp">
                    Signed contract with Swedbank Latvia for stand alone Swedbank Bank Link and/or Payment Initiation
                </td>
            </tr>
            <t>
                <td class="forminp" colspan="2">
                    <div class="alert alert-info col-lg-offset-2">*To generate certificate please fill fields: "State or
                        province name", "Locality name", "Organization name", "Organizational unit name", "Common name",
                        "Email address" and press Save. If you have your own certificate you only need to enter private
                        key and Seller ID
                    </div>
                </td>
            </t>
            <?php
            return ob_get_clean();
        }

        public function generate_mbbl_label_ee_html($key, $value)
        {
            $field = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class' => 'button-secondary',
                'css' => '',
                'custom_attributes' => array(),
                'desc_tip' => false,
                'description' => '',
                'title' => '',
            );

            $value = wp_parse_args($value, $defaults);

            ob_start();
            ?>
            <tr style="border-bottom: 1px solid #000">
                <th scope="row" class="titledesc">
                    <h1>For Estonia</h1>
                </th>
                <td class="forminp">
                    Signed contract with Swedbank Estonia for stand alone Swedbank Bank Link and/or Payment Initiation
                </td>
            </tr>
            <t>
                <td class="forminp" colspan="2">
                    <div class="alert alert-info col-lg-offset-2">*To generate certificate please fill fields: "State or
                        province name", "Locality name", "Organization name", "Organizational unit name", "Common name",
                        "Email address" and press Save. If you have your own certificate you only need to enter private
                        key and Seller ID
                    </div>
                </td>
            </t>
            <?php
            return ob_get_clean();
        }

        public function generate_label_html($key, $data)
        {
            $field = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class' => 'button-secondary',
                'css' => '',
                'custom_attributes' => array(),
                'desc_tip' => false,
                'description' => '',
                'title' => '',
            );

            $data = wp_parse_args($data, $defaults);

            ob_start();
            ?>


            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label>Please add this URL to CronJob. Set to run this url each 10 minutes.</label>
                </th>
                <td class="forminp">
                    <?php echo home_url(); ?>?swedbankv3=cronjob
                </td>
            </tr>
            <tr>
                <th><label>Notification URL</label></th>
                <td>Please provide this link to Swedbank: <?php echo home_url(); ?>?swedbankv3=notification</td>
            </tr>


            <?php
            return ob_get_clean();
        }

    }

}

add_filter('query_vars', 'swedbank_v3_return');

/**
 *   Add the 'swedbankv3' query variable so WordPress
 *   won't remove it.
 */
function swedbank_v3_return($vars)
{
    $vars[] = "swedbank";
    return $vars;
}

/**
 *   check for  'swedbankv3' query variable and do what you want if its there
 */
add_action('template_redirect', 'swedbank_v3_done');

function swedbank_v3_done($template)
{

    global $wp_query;

    if (!isset($_GET['swedbankv3'])) {
        return $template;
    }


    if ($_GET['swedbankv3'] == 'done') {
        $WC_Gateway_Swedbank_lt_v3 = new WC_Gateway_Swedbank_lt_v3();
        //echo '<pre>';
        $WC_Gateway_Swedbank_lt_v3->doDone();

        //echo "</pre>";
        exit;
    } else if ($_GET['swedbankv3'] == 'doneC') {
        $WC_Gateway_Swedbank_lt_v3 = new WC_Gateway_Swedbank_lt_v3();
        //echo '<pre>';
        $WC_Gateway_Swedbank_lt_v3->doDoneC();

        //echo "</pre>";
        exit;
    } else if ($_GET['swedbankv3'] == 'finish') {
        $WC_Gateway_Swedbank_lt_v3 = new WC_Gateway_Swedbank_lt_v3();
        //echo '<pre>';
        $WC_Gateway_Swedbank_lt_v3->doFinish();

        //echo "</pre>";
        exit;
    } else if ($_GET['swedbankv3'] == 'cronjob') {
        $WC_Gateway_Swedbank_lt_v3 = new WC_Gateway_Swedbank_lt_v3();
        //echo '<pre>';
        $WC_Gateway_Swedbank_lt_v3->cronjob();

        //echo "</pre>";
        exit;
    } else if ($_GET['swedbankv3'] == 'notification') {
        $WC_Gateway_Swedbank_lt_v3 = new WC_Gateway_Swedbank_lt_v3();
        //echo '<pre>';
        $WC_Gateway_Swedbank_lt_v3->notification();

        //echo "</pre>";
        exit;
    }  else if ($_GET['swedbankv3'] == 'redirectmbbl') {
        $WC_Gateway_Swedbank_lt_v3 = new WC_Gateway_Swedbank_lt_v3();
        //echo '<pre>';
        $WC_Gateway_Swedbank_lt_v3->mbbl();

        //echo "</pre>";
        exit;
    }


    return $template;
}

register_activation_hook(__FILE__, 'on_activatev3');

function on_activatev3()
{
    global $wpdb;
    $create_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}swedbank_orderlist` (
              `orderidcart` VARCHAR(25),
              `merchantreference` VARCHAR(25) 
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
    ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($create_table_query);
}
