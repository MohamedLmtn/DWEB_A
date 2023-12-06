<!DOCTYPE html>
<html lang="fr">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="styleHistCom.css">
    <title>Historique des Produits Commandés</title>
</head>


<body>
    <button id="back" onclick="goBack()">Revenir en arrière</button>

    <script>
        // Fonction pour revenir en arrière dans l'historique du navigateur
        function goBack() {
            window.history.back();
        }
    </script>
    <?php
    // Vérifier si le client est connecté
    if (isset($_SESSION['idcli'])) {
        require_once("./conn_phpmyadmin.php");
        $db = conn();

        $idClient = $_SESSION['idcli'];

        // Récupérer l'historique des commandes pour ce client
        $requeteCommandes = $db->prepare("SELECT * FROM COMMANDE WHERE ID_CLIENT = :idClient  and STATUT_CMD = 'acheter' order by DATE_CMD desc");
        $requeteCommandes->execute([":idClient" => $idClient]);
        $commandes = $requeteCommandes->fetchAll();

        // Vérifier s'il y a des commandes
        if (count($commandes) > 0) {
            // Afficher l'historique des commandes
            echo "<h1>Historique des Commandes</h1>";
            echo "<ul>";
          
            foreach ($commandes as $commande) {

                echo "<li>";
                echo "ID de la commande : " . $commande['ID_COMMANDE'] . "<br>";
                echo "Date de la commande : " . $commande['DATE_CMD'] . "<br>";
                echo "Statut de la commande : " . $commande['STATUT_CMD'] . "<br>";
                 ?>
                <button id='btnDet' onclick='popup(<?=$commande["ID_COMMANDE"]?>)' >Afficher Détails</button>   
    <?php
 
    echo "</li>";
            }
           echo "</ul>";
        } else {
            // Aucune commande
            echo "<script>alert('Vous n\'avez aucune commande.');
            window.location.href = 'profile.php';</script>";
        }
    } ?>
   

        <script>
        
        function popup(idcmd){
            
            window.open("historiquecmddetails.php?idcmd=" + idcmd, "_blank", "width=600,height=400");

         
        }
        
        
        
        </script>







</body>

</html>