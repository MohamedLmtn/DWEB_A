


<?php

class DatatransPaymentService
{
    public function initializeTransaction($amount, $orderId)
    {
        $url = 'https://api.sandbox.datatrans.com/v1/transactions';
        $succesUrl = 'https://esig-sandbox.ch/t24_2_v2/interface_commande_passé.php?succes=1';
        $cancelUrl = 'https://esig-sandbox.ch/t24_2_v2/interface_commande_passé.php?cancel=3';
        $errorUrl = 'https://esig-sandbox.ch/t24_2_v2/interface_commande_passé.php?error=2';

        require_once __DIR__ . '/Config.php';

        $amount = $amount * 100;

        $postFields = json_encode(array(
            'amount' => $amount,
            'currency' => "CHF",
            'refno' => $orderId,
            'redirect' => [
                'successUrl' => $succesUrl,
                "cancelUrl" => $cancelUrl,
                "errorUrl" => $errorUrl
            ]
        ));

        $key = Config::MERCHANT_ID . ':' . Config::PASSWORD;
        $keyBase64 = base64_encode($key);

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . $keyBase64,
                "Content-Type: application/json"
            )
        ));

        $curlResponse = curl_exec($ch);

        $curlJSONObject = json_decode($curlResponse);

        if (empty($curlResponse)) {
            $curlError = curl_error($ch);
        } else if (!empty($curlJSONObject->error)) {
            $curlError = $curlJSONObject->error->code . ": " . $curlJSONObject->error->message;
        }
        curl_close($ch);

        if (empty($curlJSONObject->transactionId)) {
            $result = array(
                'responseType' => "Error",
                'message' => $curlError
            );
        } else {
            $result = array(
                'responseType' => "success",
                'transactionId' => $curlJSONObject->transactionId
            );
        }

        $result = json_encode($result);

        return $result;
    }
}
?>