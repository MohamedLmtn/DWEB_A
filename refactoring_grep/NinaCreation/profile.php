<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="styleProfil.css">
    <title>Votre Profil</title>
</head>

<header>
    <span class="reseaux_sociaux">
        <a href="https://www.facebook.com/ninacreationgeneve"><img src="https://static.wixstatic.com/media/4057345bcf57474b96976284050c00df.png/v1/fill/w_39,h_39,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/4057345bcf57474b96976284050c00df.png"></a>
        <a href="https://www.instagram.com/ninacreationsgeneve/"><img src="https://static.wixstatic.com/media/e1aa082f7c0747168d9cf43e77046142.png/v1/fill/w_39,h_39,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/e1aa082f7c0747168d9cf43e77046142.png"></a>
        <a href="https://www.youtube.com/@ninacreationsgeneve4625"><img src="https://static.wixstatic.com/media/45bce1d726f64f1999c49feae57f6298.png/v1/fill/w_39,h_39,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/45bce1d726f64f1999c49feae57f6298.png"></a>
    </span>

</header>

<body>
    <div class="container">
        <div class="profile">
            <?php
            require_once("./conn.php");
            $db = conn();
            $requeteProfile = $db->prepare("SELECT * FROM CLIENT WHERE ID_CLIENT = :id");
            $requeteProfile->execute([":id" => $_SESSION["idcli"]]);
            $infoClient = $requeteProfile->fetch();
            ?>
            <div class="profile-header">
                <?php
                require_once("./conn.php");
                $db = conn();

                $recupphotocli = $db->prepare("select PHOTO_PROFILE from CLIENT where ID_CLIENT = :id");
                $recupphotocli->execute([":id" => $_SESSION["idcli"]]);
                $profilpic = $recupphotocli->fetch()
                ?>
                <h2>Votre Profil</h2>
                <a href="#" onclick="modifierPhotoProfil()"><img src="<?= $profilpic["PHOTO_PROFILE"] ?>" alt="Photo de Profil"></a>
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <form action="traitementadmin.php?valeur=imgcli" method="post" enctype="multipart/form-data">
                            <label for="image" style="display: block; color: #b82994; margin-bottom: 10px; font-weight: bold;">Changer la photo de profil</label>
                            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box;">
                            <input type="submit" value="Ajouter" style="width: 100%; padding: 10px; background-color: #b82994; color: white; border: none; cursor: pointer; box-sizing: border-box;">
                        </form>
                    </div>
                </div>
                <script>
                    function modifierPhotoProfil() {
                        var modal = document.getElementById('modal');
                        modal.style.display = 'block';
                    }

                    function closeModal() {
                        var modal = document.getElementById('modal');
                        modal.style.display = 'none';
                    }
                </script>
            </div>
            <ul>
                <li><span>Nom:</span> <?=htmlspecialchars( $infoClient["NOM_CLI"]) ?></li>
                <li><span>Prénom:</span> <?= htmlspecialchars($infoClient["PRENOM_CLI"]) ?></li>
                <li><span>Email:</span> <?= htmlspecialchars($infoClient["EMAIL_CLI"] )?></li>
                <li><span>Numéro de téléphone:</span> <?=htmlspecialchars( $infoClient["TEL_CLI"]) ?></li>
            </ul>
            <a href="modifier_profil.php" class="btn-modifier">Modifier votre profil</a>
            <br>
            <a href="index.php" class="btn-modifier">Revenir à l'accueil</a>
            <br>
            <a href="commandes.php" class="btn-modifier">Historique des commandes</a>
            <br>
            <a href="produitFav.php" class="btn-modifier">Produits Favoris</a>
        </div>
    </div>


</body>

</html>