<HTML>
<HEAD>
<TITLE>Datatrans payment status notice</TITLE>
</HEAD>
<BODY>
    <div class="text-center">
<?php
if (! empty($_GET["status"])) {
    ?>
    <h1>Something wrong with the payment process.</h1>
        <p>Kindly contact support with the reference of
	your transaction id <?php echo $_GET["datatransTrxId"]; ?></p>
<?php
} else {
    ?>
    <h1>Your order has been placed</h1>
        <p>We will contact you shortly.</p>
<?php
}
?>
</div>
</BODY>
</HTML>