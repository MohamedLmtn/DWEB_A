<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylePrFav.css">
    <title>Produits Favoris</title>
</head>

<body>
    <?php
    if (isset($_SESSION['idcli'])) {
        require_once("./conn.php");
        $db = conn();

        $idClient = $_SESSION['idcli'];

        // Récupérer les produits favoris du client
        $requeteProduitsFavoris = $db->prepare("SELECT P.ID_PRODUIT , P.NOM_PRODUIT FROM FAVORI F JOIN PRODUIT P ON F.ID_PRODUIT = P.ID_PRODUIT WHERE F.ID_CLIENT = :idClient");
        $requeteProduitsFavoris->execute([":idClient" => $idClient]);
        $produitsFavoris = $requeteProduitsFavoris->fetchAll();

        // Afficher les produits favoris
        echo "<h1>Vos Produits Favoris</h1>";
        if (count($produitsFavoris) > 0) {
            echo "<ul>";
            foreach ($produitsFavoris as $produit) {
                ?>
                <a href="./produit.php?idprod=<?=$produit["ID_PRODUIT"]?>"><li><?= $produit['NOM_PRODUIT'] ?></li></a>
              
              <?php
            }
            echo "</ul>";
        } else if (count($produitsFavoris) == 0) {
            "<script> alert('Vous n\'avez aucun produit en favori.');
        window.location.href = 'profile.php'; </script>";
        }
    }
    ?>
    <button onclick="goBack()">Revenir</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

</body>

</html>