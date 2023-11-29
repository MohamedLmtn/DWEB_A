<!-- modifier_profil.php -->
<?php session_start();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleModifierProfil.css">
    <title>Modifier Produit</title>
</head>

<body>
    <div class="container">
        <div class="profile">
            <?php
            require_once("./conn_phpmyadmin.php");
            $db = conn();


            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                $reqPROD = $db->prepare("SELECT * FROM PRODUIT WHERE ID_PRODUIT = :id");
                $reqPROD->execute([":id" => $_POST["idprod"]]);
                $lesproduit = $reqPROD->fetch();

                $prix_prod = $_POST["prix_prod"];
                $stock_prod = $_POST["stock_prod"];
                $decription_prod = $_POST["decription_prod"];
                $nom_prod = $_POST["nom_prod"];
                $type = $_POST["typeprod"];
                $categorie = $_POST["categorieprod"];

                $changement = false;

                if ($nom_prod !== $lesproduit["NOM_PRODUIT"] || 
                $decription_prod !== $lesproduit["DESCRIPTION_PRODUIT"] || 
                $prix_prod !== $lesproduit["PRIX_PRODUIT"] || 
                $stock_prod !== $lesproduit["STOCK_PRODUIT"] || 
                $type !== $lesproduit["ID_TYPEPROD"] || 
                $categorie !== $lesproduit["ID_CATEGORIEPROD"]) {

                    $changement = true;
                }

                if ($changement) {
                    $miseAJourProduit = $db->prepare("UPDATE PRODUIT SET NOM_PRODUIT = :nom, DESCRIPTION_PRODUIT = :description, PRIX_PRODUIT = :prix, STOCK_PRODUIT = :stock , ID_TYPEPROD = :types , ID_CATEGORIEPROD = :categorie WHERE ID_PRODUIT = :id");
                    $miseAJourProduit->execute([
                        ":prix" => $prix_prod,
                        ":stock" => $stock_prod,
                        ":description" => $decription_prod,
                        ":nom" => $nom_prod,
                        ":id" => $_POST["idprod"],
                        ":types"=>$type,
                        ":categorie"=>$categorie
                    ]); ?>
                    <script>
                        alert("Les données ont été mises à jour avec succès.")
                        window.location.href = "admine.php?requete=produit";
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("Aucun changement apporté...")
                        window.location.href = "admine.php?requete=produit";
                    </script>
            <?php
                }
            }

            $reqPROD = $db->prepare("SELECT * FROM PRODUIT WHERE ID_PRODUIT = :idprod");
            $reqPROD->execute([":idprod" => $_GET['idprod']]);
            $lesproduit = $reqPROD->fetch();

            $recuptypeprod = $db->prepare("SELECT * FROM `TYPE_PROD`");
            $recuptypeprod->execute();
            $typeprod = $recuptypeprod->fetchAll();

            $recupcategorieprod = $db->prepare("SELECT * FROM `CATEGORIE_PROD`");
            $recupcategorieprod->execute();
            $categorieprod = $recupcategorieprod->fetchAll();

            ?>
            <h2>Modifier le Produit</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="prix_prod">Prix du produit:</label>
                <input type="text" id="prix_prod" name="prix_prod" value="<?= $lesproduit["PRIX_PRODUIT"] ?>"><br>

                <label for="stock_prod">Stock du produit:</label>
                <input type="text" id="stock_prod" name="stock_prod" value="<?= $lesproduit["STOCK_PRODUIT"] ?>"><br>

                <label for="decription_prod">Description du produit:</label>
                <input type="text" id="decription_prod" name="decription_prod" value="<?= $lesproduit["DESCRIPTION_PRODUIT"] ?>"><br>

                <label for="decription_prod">Type du produit:</label>

                <select name="typeprod" id="typeprod"><?php
                foreach($typeprod as $type){
                    if($lesproduit["ID_TYPEPROD"] === $type["ID_TYPEPROD"]){
                        ?>
                        <option value="<?=$type["ID_TYPEPROD"]?>" selected><?=$type["NOM_TYPE"]?></option>
                        <?php
                    }else{
                        ?>
                        <option value="<?=$type["ID_TYPEPROD"]?>"><?=$type["NOM_TYPE"]?></option>
                        <?php
                    }
                    
                }
                ?>
                </select><br>

                <label for="decription_prod">Catégorie du Produit:</label>

                <select name="categorieprod" id="categorieprod"><?php
                foreach($categorieprod as $categorie){
                    if($lesproduit["ID_CATEGORIEPROD"] === $categorie["ID_CATEGORIEPROD"]){
                    ?>
                    <option value="<?=$categorie["ID_CATEGORIEPROD"]?>" selected><?=$categorie["NOM_CATEGORIE"]?></option>
                    <?php
                }else{
                    ?>
                    <option value="<?=$categorie["ID_CATEGORIEPROD"]?>"><?=$categorie["NOM_CATEGORIE"]?></option>
                    <?php
                }}
                ?></select>
                <label for="nom_prod">Nom du produit:</label>
                <input type="text" id="nom_prod" name="nom_prod" value="<?= $lesproduit["NOM_PRODUIT"] ?>"><br>
                <input type="hidden" name="idprod" value="<?= $_GET["idprod"] ?>">

                <button type="submit">Mettre à jour</button>
            </form>

            <?php

            ?>
        </div>
    </div>
</body>

</html>