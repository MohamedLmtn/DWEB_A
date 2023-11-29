<!DOCTYPE html>
<html>

<head>
    <title>Résultat de la commande</title>
</head>

<body>

    <?php
    if ($_GET['succes'] == 1) {
    ?>
        <script>
            alert("Commande a été passée avec succès!");
            window.location.href = "panier.php?cmd_passe=true";
        </script>
    <?php

    } else if ($_GET['error'] == 2) {
    ?>
        <script>
            alert("Il y avait une erreur avec la commande!");
            window.location.href = "index.php";
        </script>
    <?php

    } else if ($_GET['cancel'] == 3) {
    ?>
        <script>
            alert("La commande a été annulé!");
            window.location.href = "index.php";
        </script>
    <?php

    }
    ?>


</body>

</html>