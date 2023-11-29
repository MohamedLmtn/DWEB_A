<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleadmin.css">
    <title>Page Admin</title>
</head>
<header>
    <a href="index.php">Revenir à l'accueil</a>-
    <a href="admine.php?requete=client">liste des clients</a>-
    <a href="admine.php?requete=produit">liste des produits</a>-
    <a href="newsletter.php">Envoyer newsletter</a>
</header>

<?php

require_once("./conn.php");
$db = conn();



if (isset($_GET["requete"]) && !empty($_GET["requete"]) && $_GET["requete"] == "client") {
    $requeteClients = $db->prepare("SELECT DISTINCT CLIENT.* 
        FROM CLIENT
        LEFT JOIN AVIS ON CLIENT.ID_CLIENT = AVIS.ID_CLIENT 
        LEFT JOIN FAVORI ON CLIENT.ID_CLIENT = FAVORI.ID_CLIENT 
        LEFT JOIN COMMANDE ON CLIENT.ID_CLIENT = COMMANDE.ID_CLIENT;");



    $requeteClients->execute();
    $clients = $requeteClients->fetchAll(PDO::FETCH_ASSOC);
?>

    <body>
        <h2>Liste des Clients</h2>

        <ul>
            <?php foreach ($clients as $client) : ?>
                <li>
                    <div>
                        <?= $client["ID_CLIENT"] ?> <a href="modif_profil_admin.php?client_id=<?= $client['ID_CLIENT'] ?>"><?= $client['NOM_CLI'] . ' ' . $client['PRENOM_CLI'] ?></a>
                        <p><?=$client["STATUT_CLI"]?></p>
                        <?php
                        if($client["STATUT_CLI"] === 'normal'||$client["STATUT_CLI"] === 'admin' ){
                            ?>
                            <a href="./traitementadmin.php?valeur=statutcpmt&idcli=<?=$client["ID_CLIENT"]?>&statut=inactif">desactiver le compte</a>
                            <?php
                            
                        }else{
                            ?>
                             <a href="./traitementadmin.php?valeur=statutcpmt&idcli=<?=$client["ID_CLIENT"]?>&statut=normal">activer le compte</a>
                            <?php
                            
                        }
                       ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </body>
<?php

} else if (isset($_GET["requete"]) && !empty($_GET["requete"]) && $_GET["requete"] == "produit") {

?>

    <?php
    $requeteproduits = $db->prepare("SELECT DISTINCT PRODUIT.*, TYPE_PROD.*, CATEGORIE_PROD.*
        FROM PRODUIT
        JOIN TYPE_PROD ON TYPE_PROD.ID_TYPEPROD = PRODUIT.ID_TYPEPROD
        JOIN CATEGORIE_PROD ON CATEGORIE_PROD.ID_CATEGORIEPROD = PRODUIT.ID_CATEGORIEPROD order by PRODUIT.ID_PRODUIT");
    $requeteproduits->execute();
    ?>

    <body>
        <section id="listprod">
            <a href="#addprod">ajouter un produit</a>
            <h2>Liste des produits</h2>
            <ul>
                <?php while ($produit = $requeteproduits->fetch()) {
                ?>
                    <li>
                        <div>
                            <?= $produit["ID_PRODUIT"] ?><a href="gestion_stock.php?idprod=<?= $produit["ID_PRODUIT"] ?>"><?= $produit["NOM_PRODUIT"] ?></a>
                            ////////
                            <a href="ajoutphoto.php?valeur=prod&idprod=<?= $produit["ID_PRODUIT"] ?>">Ajouter images</a>
                            ///////-> <?php echo $produit["STATUT_PROD"];
                            if($produit["STATUT_PROD"] === 'disponible'){
                            ?>
                            <a href="./traitementadmin.php?valeur=statutprod&idprod=<?=$produit["ID_PRODUIT"]?>&statut=Écartés">suspendre le produit</a>
                            <?php
                            
                        }else{
                            ?>
                             <a href="./traitementadmin.php?valeur=statutprod&idprod=<?=$produit["ID_PRODUIT"]?>&statut=disponible">remettre le produit</a>
                            <?php
                            
                        }?>
                        </div>
                    </li>

                <?php
                }
                ?>
            </ul>
        </section>

        <section id="addprod">
            <a href="#listprod">Liste des produits</a>
            <h2>Ajouter un nouveau produit</h2>
            <form action="traitementadmin.php?valeur=ajoutProduit" method="POST">
                <label for="nom_produit">Nom du produit:</label><br>
                <input type="text" id="nom_produit" name="nom_produit"><br>

                <label for="id_typeprod">Type du produit:</label><br>
                <select id="id_typeprod" name="id_typeprod">
                    <option value="1">Homme</option>
                    <option value="2">Femme</option>
                </select><br>

                <label for="id_categorieprod">Catégorie du produit:</label><br>
                <select id="id_categorieprod" name="id_categorieprod">
                    <option value="1">Bijoux</option>
                    <option value="2">Pendentifs</option>
                    <option value="3">Bracelet</option>
                    <option value="4">Porte-clé</option>
                    <option value="5">Accessoires</option>
                </select><br>

                <label for="description_produit">Description du produit:</label><br>
                <textarea id="description_produit" name="description_produit"></textarea><br>

                <label for="prix_produit">Prix du produit:</label><br>
                <input type="text" id="prix_produit" name="prix_produit"><br>

                <label for="stock_produit">Stock du produit:</label><br>
                <input type="text" id="stock_produit" name="stock_produit"><br><br>

                <input type="submit" value="Ajouter">
            </form>
        </section>

    <?php
}

?>


    </body>

</html>