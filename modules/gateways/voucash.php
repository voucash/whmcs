<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function voucash_MetaData()
{
    return array(
        'DisplayName' => 'VouCash',
        'APIVersion' => '1.0',
        'DisableLocalCreditCardInput' => true,
        'TokenisedStorage' => false,
    );
}


function voucash_config()
{
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'VouCash',
        )
    );
}

function voucash_link($params)
{
	$returnUrl = WHMCS\Config\Setting::getValue("SystemURL").'/clientarea.php';
	$notifyUrl = WHMCS\Config\Setting::getValue("SystemURL").'/modules/gateways/voucash/callback.php';
    $invoiceid = $params['invoiceid'];
    $amount = $params['amount'];
    $currency = $params['currency'];
    $link =  "https://voucash.com/api/payment?order_id=$invoiceid&amount=$amount&currency=$currency&notify_url=$notifyUrl&return_url=$returnUrl";
    return "<a href=\"{$link}\" target=\"_blank\" class=\"btn btn-success btn-sm\">前往 VouCash 支付</a>";
}