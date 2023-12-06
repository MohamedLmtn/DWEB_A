<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylemdp.css">
    <title>Mot De Passe Oublier</title>
</head>

<body>

    <?php
    if (isset($_GET["nvmdp"]) && !empty($_GET["nvmdp"]) && $_GET["nvmdp"] == "true") {
    ?>
        <header><a href="./motdepasseoublier.php"><img src="./img/flechretour.svg"></a></header>


        <form action="./traitement_mdp_oublie.php?value=nvmpd" method="post">
            <label for="newmdp">nouveau mot de passe</label>
            <input type="password" name="newmdp" id="newmdp" minlength="9" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&amp;])[A-Za-z\d@$!%*?&amp;]{8,20}$" title="Le mot de passe doit contenir au moins 8 caractères, avec au moins une lettre en Majuscule, un chiffre et un caractère spécial (@$!%*#?&amp;)" required>

            <label for="cnfmdp">confirmer votre mot de passe</label>
            <input type="password" name="cnfmdp" id="cnfmdp" minlength="9" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&amp;])[A-Za-z\d@$!%*?&amp;]{8,20}$" title="Le mot de passe doit contenir au moins 8 caractères, avec au moins une lettre en Majuscule, un chiffre et un caractère spécial (@$!%*#?&amp;)" required>


            <input type="hidden" value="<?= $_GET["mailrec"] ?>" name="mailrec">
            <input type="submit" id="btnsubmit">

        </form>
    <?php


    } else if (isset($_GET["code_fourni_mail"]) && !empty($_GET["code_fourni_mail"]) && $_GET["code_fourni_mail"] == "true") {
    ?>
        <form action="./traitement_mdp_oublie.php?value=code_fourni_mail" method="post">
            <label for="code_de_mail">Entrez le code que vous avez reçu par E-mail</label>
            <label for="code_de_mail">Le code</label>
            <input type="hidden" value="<?= $_GET["mailrec"] ?>" name="mailrec">
            <input type="text" name="code_de_mail" id="code_de_mail">

            <input type="submit" id="btnsubmit">
        </form>
    <?php

    } else {

    ?>
        <header><a href="./LogIn/connexion.html"><img src="./img/flechretour.svg"></a></header>


        <form action="./traitement_mdp_oublie.php?value=envoi_du_mail" method="post">
            <label for="mail">Votre Email</label>
            <input type="email" name="mail" id="mail">

            <input type="submit" id="btnsubmit">
        </form>
    <?php

    }
    ?>

    <script>
        const newmdp = document.getElementById('newmdp');
        const cnfmdp = document.getElementById('cnfmdp');
        const btnsubmit = document.getElementById('btnsubmit');


        function verifierValeurs() {
            if (newmdp.value === cnfmdp.value) {
                btnsubmit.removeAttribute('disabled');
            } else {
                btnsubmit.setAttribute('disabled', 'true');
            }
        }


        newmdp.addEventListener('input', verifierValeurs);
        cnfmdp.addEventListener('input', verifierValeurs);
    </script>
</body>

</html>