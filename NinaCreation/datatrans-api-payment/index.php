<HTML>

<HEAD>
    <TITLE>Datatrans Payments API for Secure Payment</TITLE>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</HEAD>

<BODY>

    <div class="container">
        <h1>Datatrans Payments API for Secure Payment</h1>
        <div class="outer-container">
            <img class="logo_nina" src="../img/logo_nina_cre.png" alt="image">
            <div class="inner-container">

                <p class="text-style">Prix total</p>


                <p class="price-color-align"><?php echo $_GET["value"] ?> CHF </p>

                <input type="hidden" name="amount" id="amount" value="<?php echo $_GET["value"]?>" />


                <input type="button" id="pay-now" class="pay-button" value="Pay via DataTrans" onClick="initiate()" />
            </div>
        </div>
    </div>
    <script src="https://pay.sandbox.datatrans.com/upp/payment/js/datatrans-2.0.0.js"></script>
    <script>
        function initiate() {
            $.ajax({
                    method: "POST",
                    url: "initialize-datatrans-ajax.php",
                    dataType: "JSON",
                    data: {
                        "amount": $("#amount").val()
                    }
                })
                .done(function(response) {
                    if (response.responseType == 'success') {
                        proceedPayment(response.transactionId);
                    } else {
                        alert(response.responseType + ": " + response.message);
                    }
                });
        };

        function proceedPayment(transactionId) {
            Datatrans.startPayment({
                transactionId: transactionId,
                'opened': function() {
                    console.log('payment-form opened');
                },
                'loaded': function() {
                    console.log('payment-form loaded');
                },
                'closed': function() {
                    console.log('payment-page closed');
                },
                'error': function() {
                    console.log('error');
                }
            });
        }
    </script>
</BODY>

</HTML>