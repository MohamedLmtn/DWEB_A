<?php
require_once 'DatatransPaymentService.php';
$dataTransPaymentService = new DatatransPaymentService();
$amount = $_POST["amount"];
$orderId = rand();
$result = $dataTransPaymentService->initializeTransaction($amount, $orderId);
print $result;
?>