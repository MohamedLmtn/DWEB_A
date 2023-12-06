<!DOCTYPE html>
<html lang="fr">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="192x192" href="img/logo_sans_fond.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylefooter.css">
    <link rel="stylesheet" href="stylePanier.css">
    <title>Nina Créations</title>


</head>
<?php include("./include/header.php") ?>
<div>

    <body>
        <?php
        require_once("./conn_phpmyadmin.php");
        $db = conn();

        if (isset($_SESSION['idcli']) && !empty($_SESSION['idcli'])) {


            $verifpanier = $db->prepare("select count(*) from COMMANDE join COMMANDE_PROD on COMMANDE.ID_COMMANDE = COMMANDE_PROD.ID_COMMANDE where ID_CLIENT = :id and STATUT_CMD = 'en cours'");
            $verifpanier->execute([":id" => $_SESSION["idcli"]]);
            $conteur = $verifpanier->fetchAll();



            if (isset($_GET["cmd_passe"]) && $_GET["cmd_passe"] == "true") {
                $panier = $db->prepare("select * from COMMANDE join COMMANDE_PROD on COMMANDE.ID_COMMANDE = COMMANDE_PROD.ID_COMMANDE JOIN PRODUIT ON COMMANDE_PROD.ID_PRODUIT = PRODUIT.ID_PRODUIT where ID_CLIENT = :id and STATUT_CMD = 'en cours'");
                $panier->execute([":id" => $_SESSION["idcli"]]);
                while ($prodpanier = $panier->fetch()) {
                    $updatestock = $db->prepare("UPDATE PRODUIT set STOCK_PRODUIT = STOCK_PRODUIT - :QUANTITE where ID_PRODUIT = :idprod");
                    $updatestock->execute([":idprod" => $prodpanier["ID_PRODUIT"], ":QUANTITE" => $prodpanier["QUANTITE_CMD"]]);

                    $update_stat_commande = $db->prepare("UPDATE COMMANDE SET STATUT_CMD = 'acheter' WHERE ID_CLIENT = :id");
                    $update_stat_commande->execute([":id" => $_SESSION["idcli"]]);

                    $recupstockapresupdate = $db->prepare("SELECT STOCK_PRODUIT FROM PRODUIT WHERE ID_PRODUIT = :idprod");
                    $recupstockapresupdate->execute([":idprod" => $prodpanier["ID_PRODUIT"]]);
                    $stockcmd = $recupstockapresupdate->fetch();

                    $allcmdproduit = $db->prepare("SELECT * from COMMANDE join COMMANDE_PROD on COMMANDE.ID_COMMANDE = COMMANDE_PROD.ID_COMMANDE where ID_PRODUIT = :idprod and STATUT_CMD = 'en cours'");
                    $allcmdproduit->execute([":idprod" => $prodpanier["ID_PRODUIT"]]);

                    while ($cmdprod = $allcmdproduit->fetch()) {
                        if ($cmdprod["QUANTITE_CMD"] > $stockcmd["STOCK_PRODUIT"]) {
                            $updatecmd = $db->prepare("UPDATE COMMANDE_PROD set QUANTITE_CMD = :stockprod where ID_COMMANDE =:idcmd");
                            $updatecmd->execute([":idcmd" => $cmdprod["ID_COMMANDE"], ":stockprod" => $stockcmd["STOCK_PRODUIT"]]);
                        }
                    }

                    header("location:index.php");
                }
            }



            if ($conteur[0]["count(*)"] > 0) {
                $panier = $db->prepare("SELECT * from COMMANDE join COMMANDE_PROD on COMMANDE.ID_COMMANDE = COMMANDE_PROD.ID_COMMANDE JOIN PRODUIT ON COMMANDE_PROD.ID_PRODUIT = PRODUIT.ID_PRODUIT where ID_CLIENT = :id and STATUT_CMD = 'en cours' AND PRODUIT.STATUT_PROD = 'disponible'");
                $panier->execute([":id" => $_SESSION["idcli"]]);
                $prixTot = 0;
                $index = 0;
        ?>

                <?php
                while ($prodpanier = $panier->fetch()) {
                    $imgprod = $db->prepare("SELECT IMAGE_PROD from IMG_PROD where ID_PRODUIT = :id limit 1");
                    $imgprod->execute(["id" => $prodpanier["ID_PRODUIT"]]);
                    $IMG = $imgprod->fetch();
                ?>
                    <div id="panierflex">
                        <div class="fleximg"><img src="<?= $IMG['IMAGE_PROD'] ?>"></div>
                        <div class="textflex">
                            <p> Nom: <?= htmlspecialchars($prodpanier["NOM_PRODUIT"]) ?></p>
                            <p> Prix: <?= htmlspecialchars($prodpanier["PRIX_PRODUIT"]) . "  CHF" ?></p>
                            <p> Quantité: <?= $prodpanier["QUANTITE_CMD"] . "  pièce" ?></p>
                        </div>
                        <div class="formqtes">
                            <form action="./traitement.php?value=qntchng" method="post">
                                <input type="hidden" value=<?= $prodpanier["ID_COMMANDE"] ?> name="idcmd">
                                <input type="number" name="qntcmd" id="qntcmd" min="1" max="<?= $prodpanier["STOCK_PRODUIT"] ?>" value="<?= $prodpanier["QUANTITE_CMD"] ?>">
                                <input type="submit" value="ajouter quantité">
                            </form>
                            <a href="./traitement.php?value=delprodpan&idcmd=<?= $prodpanier["ID_COMMANDE"]?>&idprod=<?=$prodpanier["ID_PRODUIT"]?>"><button>supprimer</button></a>
                        </div>
                    </div>
</div>
<?php
                    $prixTot += $prodpanier["PRIX_PRODUIT"] * $prodpanier["QUANTITE_CMD"];
                    $index++;
                }
?>
<div class="prixPanContainer">
    <div class="prixPan">
        <?php echo "Prix Total: " . $prixTot . "  CHF";
        ?>
    </div>
    <div class="prixPan">
        <a href="./datatrans-api-payment/index.php?value=<?= $prixTot ?>">
            Paiement </style>
        </a>
    </div>
</div>

<?php
            } else {
?>
    <script>
        alert("le panier est vide!");
        setTimeout(function() {
            window.location.href = "Boutique.php?type=homme";
        }, 0);
    </script>

<?php
            }
        } else {
?>
<script>
    alert("connectez vous!");
    setTimeout(function() {
        window.location.href = "LogIn/connexion.html?mode=conex";
    }, 0);
</script>

<?php

        }
?>

</div>

</body>

<div class="fotter">
    <?php include("./include/footer.php"); ?>
</div>





</html>