<!DOCTYPE html>
<html lang="fr">
<?php session_start()?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" sizes="192x192" href="img/logo_sans_fond.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylefooter.css">

    <script src="./bootstrap/js/bootstrap.js"></script>

    <title>Nina Créations</title>
</head>
<?php require_once("./include/header.php") ?>

<body>
    <section-prod>
        <p>NOS MEILLEURS PRODUITS</p>
        <?php require_once("controleur.php");
                prod5etoile();
        ?>
    </section-prod>
</body>
<?php require_once("./include/footer.php"); ?>
<script src="script.js"></script>
</html>