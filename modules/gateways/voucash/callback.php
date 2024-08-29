<?php
    // Require libraries needed for gateway module functions.
    require_once __DIR__ . '/../../../init.php';
    App::load_function('gateway');
    App::load_function('invoice');

    // Detect module name from filename.
    // $gatewayModuleName = basename(__FILE__, '.php');

    // Fetch gateway configuration parameters.
    $gatewayParams = getGatewayVariables("voucash");

    // Die if module is not active.
    if (!$gatewayParams['type']) {
        die("Module Not Activated");
    }

    logTransaction($gatewayParams['name'], $_REQUEST, 'Start Verification');

    $raw_post_data = file_get_contents('php://input');
    file_put_contents('/tmp/ipn.log', $raw_post_data);
    $ch = curl_init("https://voucash.com/api/payment/verify");

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $raw_post_data);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    // curl_setopt($ch, CURLOPT_CAINFO, $tmpfile);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    $http_code = $info['http_code'];


    if ( ! ($res)) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
        echo "connect error";
        return;
    }

    
    if ($http_code != 200) {
        curl_close($ch);
        echo "server response error";
        return;
    }

    curl_close($ch);

    if ($res == "verified") {
        $invoiceId = $_POST['order_id'];
        $amount = $_POST['amount'];
        // $voucher = $_POST['voucher'];
        addInvoicePayment(
            $invoiceId,
            "",
            $amount,
            null,
            $gatewayParams['name']
        );
        @file_put_contents("/tmp/voucher.txt", $_POST['voucher']."\n\n", FILE_APPEND);
        logTransaction($gatewayParams['name'], $_REQUEST, "Invoice {$invoiceId} has been paid. Amount received: {$paymentAmount}");
        echo "success";
        return;
    }
    echo "failed";