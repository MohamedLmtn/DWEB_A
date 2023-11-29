<!DOCTYPE html>
<html lang="fr">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" sizes="192x192" href="img/logo_sans_fond.png">
    <link rel="stylesheet" href="./styleapropos.css">
    <link rel="stylesheet" href="./stylefooter.css">

    <title>A Propos</title>
</head>
<?php
require_once("./include/header.php");
require_once("./conn_phpmyadmin.php");
$db = conn();

?>

<body>
    <div class="section_apropos">
        <div class="left-section">
            <?php
            $photos = array();

            for ($i = 1; $i <= 4; $i++) {
                $sql = $db->prepare("SELECT IMAGE_PROD FROM IMG_PROD WHERE ID_IMGPROD = :id");
                $sql->execute(["id" => $i]);
                $photo = $sql->fetch();

                $photos[$i] = '<img src="' . $photo['IMAGE_PROD'] . '">';
            }
            ?>
            <div class="photo_apropos">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <div><?php echo $photos[$i] ?></div>
                <?php endfor; ?>
            </div>

        </div>

        <div class="middle-section">
            <p class="description_prpops">
                <?php
                $sql = $db->prepare("SELECT DESCRIPTION_WEBSITE FROM PARAMETRE");
                $sql->execute();
                $description = $sql->fetch();

                echo $description['DESCRIPTION_WEBSITE'];
                ?>
            </p>
        </div>

        <div class="right-section">
            <?php
            $photos = array();

            for ($i = 5; $i <= 8; $i++) {
                $sql = $db->prepare("SELECT IMAGE_PROD FROM IMG_PROD WHERE ID_IMGPROD = :id");
                $sql->execute(["id" => $i]);
                $photo = $sql->fetch();

                $photos[$i] = '<img src="' . $photo['IMAGE_PROD'] . '">';
            }
            ?>
            <div class="photo_apropos">
                <?php for ($i = 5; $i <= 8; $i++) : ?>
                    <div><?php echo $photos[$i] ?></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>


<div>
    <?php require_once("./include/footer.php") ?>
</div>

</html>