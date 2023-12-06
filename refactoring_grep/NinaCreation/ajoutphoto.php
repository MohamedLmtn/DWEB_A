<!DOCTYPE html>
<html lang="fr">
<?php
require_once("./conn.php");
$db = conn();
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_ajoute_photo.css">
    <title>add photo</title>
</head>
<header>
    <a href="admine.php"><img src="./img/flechretour.svg"></a>
</header>

<body>

    <?php
    if ($_GET["valeur"] == "prod" && !empty($_GET["valeur"])) {

        $cmdid = $db->prepare("SELECT IMAGE_PROD, ID_IMGPROD FROM IMG_PROD where ID_PRODUIT = :id");
        $cmdid->execute([":id" => $_GET["idprod"]]);
        $i = 0;

        if (isset($_GET["idprod"]) && filter_var($_GET["idprod"], FILTER_VALIDATE_INT)) {
            $existenceprod = $db->prepare("select count(*) from PRODUIT where ID_PRODUIT = :idprod");
            $existenceprod->execute([":idprod" => $_GET["idprod"]]);
            $prodexiste = $existenceprod->fetch();

            if ($prodexiste["count(*)"] == 1) {
    ?>
                <form class="form_photo" action="traitementadmin.php?valeur=imgprod" method="post" enctype="multipart/form-data">
                    <div class="photo-container">
                        <?php
                        while ($id = $cmdid->fetch()) {
                            $i++;
                        ?>
                            <div class="photo-item">
                                <img src="<?php echo $id["IMAGE_PROD"] ?>" class="imgprod">
                                <a href="traitement_ajoutph.php?valeur=deleteimage&le_id=<?= $id["ID_IMGPROD"] ?>">Supprimer</a>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </form>


                <form class="insertion-photo" action="traitementadmin.php?valeur=imgprod" method="post" enctype="multipart/form-data">
                    <label for="image">veuillez insérer une ou plusieur image</label>
                    <input type="file" name="image[]" accept="image/*" multiple>
                    <input type="hidden" name="idprod" value="<?= $_GET["idprod"] ?>">
                    <input type="submit" value="Ajouter">

                </form>

            <?php

            } else {
            ?>
                <script>
                    alert("le produit n'existe pas")
                </script>
        <?php
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
        ?>


        </form>
    <?php
    } else if ($_GET["valeur"] == "imgcli") {
    ?>
        <form action="traitementadmin.php?valeur=imgcli" method="post" enctype="multipart/form-data">
            <label for="image">changer la photo de profil</label>
            <input type="file" name="image" accept="image/*">
            <input type="submit" value="Ajouter">

        </form>

    <?php
    }
    ?>
</body>

</html>