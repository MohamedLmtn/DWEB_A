<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylefooter.css">
    <link rel="stylesheet" href="./styleprod.css">
    <link rel="stylesheet" href="./styleslick.css">
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">
    <title>Produit</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body>
    <?php
    include("./include/header.php");
    ?>

    <section-produit>

        <?php
        require_once("./conn.php");
        $db = conn();
        $cmdprod = $db->prepare("SELECT PRODUIT.`ID_PRODUIT`,`NOM_PRODUIT`,`DESCRIPTION_PRODUIT`,`PRIX_PRODUIT`,`STOCK_PRODUIT`,`NOM_TYPE`,`NOM_CATEGORIE` FROM PRODUIT   
                                JOIN TYPE_PROD ON TYPE_PROD.ID_TYPEPROD = PRODUIT.ID_TYPEPROD 
                                JOIN CATEGORIE_PROD ON CATEGORIE_PROD.ID_CATEGORIEPROD = PRODUIT.ID_CATEGORIEPROD where PRODUIT.ID_PRODUIT = :idprod");
        $cmdprod->execute([":idprod" => $_GET["idprod"]]);
        $produit = $cmdprod->fetch();

        $images = $db->prepare("select IMAGE_PROD from IMG_PROD where ID_PRODUIT = :IDPROD");
        $images->execute([":IDPROD" => $_GET["idprod"]]);

        ?>

        <section style="
    width: 400px;
    height: 495px;
" class="vertical-center slider">
            <?php
            while ($imgprod = $images->fetch()) {
            ?>
                <div id="imgProd">
                    <img src="<?= $imgprod["IMAGE_PROD"] ?>"></a>
                </div>
            <?php
            }
            ?>
        </section>
        <div id="infosProd">
            <p> Nom: <?= htmlspecialchars($produit["NOM_PRODUIT"]) ?></p>
            <p> description: <?= htmlspecialchars($produit["DESCRIPTION_PRODUIT"]) ?></p>
            <p> prix: <?= $produit["PRIX_PRODUIT"]  ?> CHF</p>
            <p>type: <?= $produit["NOM_TYPE"] ?></p>
            <p>categorie: <?= htmlspecialchars($produit["NOM_CATEGORIE"]) ?></p>
            <?php if ($produit["STOCK_PRODUIT"] == 0) {
            ?>
                <div>
                    <p style="color:red">Produit en Rupture de Stock</p>
                </div>
            <?php
            } else {
            ?>
                <input type="number" id="qntcmd" value="1" min="1" max="<?= $produit["STOCK_PRODUIT"] ?>">
                <button onclick="connexion()" id="btnpanier"> Panier </button>

            <?php
            }
            ?>
        </div>
    </section-produit>

    <section-avis>

        <?php
        $REQavis = $db->prepare("select * from AVIS join CLIENT on AVIS.ID_CLIENT = CLIENT.ID_CLIENT WHERE ID_PRODUIT = :idprod");
        $REQavis->execute([":idprod" => $produit["ID_PRODUIT"]]);

        $avis = $REQavis->fetchAll();

        foreach ($avis as $avi) {
        ?>
            <div>
                <h2><?= htmlspecialchars($avi["NOM_CLI"]) . ' ' . htmlspecialchars($avi["PRENOM_CLI"]) ?></h2>
                <h3><?= htmlspecialchars($avi["titre_avis"]) ?></h3>
                <p><?= htmlspecialchars($avi["DESCRIPTION_AVIS"]) ?></p>
                <?php
                $note = $avi["NOTE_AVIS"];

                for ($val = 1; $val <= 5; $val++) {
                    if ($val <= $note) {
                ?>
                        <img id="etoile" src="./img/etoilerempli.jpg">
                    <?php
                    } else {
                    ?>
                        <img id="etoile" src="./img/etoilevide.png">
            <?php
                    }
                }
            }
            ?>

            </div>




    </section-avis>
    <div id="box-avis">
        <h3>Rediger un avis</h1>
            <form id="form-avis" action="./traitement.php?value=notation" method="post">
                <div>
                    <label for="titre">Titre</label>
                    <input type="text" name="titre">
                </div>
                <div>
                    <label for="commentaire">Commentaire</label>
                    <textarea name="comment" id="commentaire"></textarea>
                </div>
                <div class="stars">
                    <i class="lar la-star" data-value="1"></i>
                    <i class="lar la-star" data-value="2"></i>
                    <i class="lar la-star" data-value="3"></i>
                    <i class="lar la-star" data-value="4"></i>
                    <i class="lar la-star" data-value="5"></i>
                </div>
                <input type="hidden" name="produit" value="<?= $_GET["idprod"] ?>">
                <input type="hidden" name="note" id="note" value="0">
                <button class="btnVal" type="button" id="btnote" onclick="submitComment()">Valider</button>
            </form>
    </div>
    <?php include("./include/footer.php"); ?>

    <script>
        function connexion() {
            try {
                <?php if (isset($_SESSION['idcli']) && !empty($_SESSION['idcli'])) { ?>
                    var quantiter = document.getElementById("qntcmd");
                    var quntcmd = quantiter.value;
                    if(quntcmd <= 0){
                        alert("donnée erronée!");
                    }else{
                        window.location.href = "./traitement.php?value=ajoutpanier&idprod=<?= $_GET["idprod"] ?>&quantitercmd=" + quntcmd;
                        
                    }
                   
                <?php } else { ?>
                    alert("Vous devez être connecté pour pouvoir ajouter un produit au panier.");
                <?php } ?>
            } catch (error) {
                alert("Une erreur s'est produite.");
            }
        }

        function submitComment() {
            try {
                <?php if (isset($_SESSION['idcli']) && !empty($_SESSION['idcli'])) { ?>
                    document.querySelector('form').submit();
                <?php } else { ?>
                    alert("Vous devez être connecté pour pouvoir soumettre un commentaire.");
                <?php } ?>
            } catch (error) {
                alert("Une erreur s'est produite.");
            }
        };

        const stars = document.querySelectorAll(".la-star");
        const note = document.querySelector("#note");

        for (const star of stars) {
            star.addEventListener("mouseover", function() {
                resetStars();
                this.style.color = "red";
                this.classList.add("las");
                this.classList.remove("lar");
                let previousStar = this.previousElementSibling;
                while (previousStar) {
                    previousStar.style.color = "red";
                    previousStar.classList.add("las");
                    previousStar.classList.remove("lar");
                    previousStar = previousStar.previousElementSibling;
                }
            });

            star.addEventListener("click", function() {
                note.value = this.dataset.value;
            });

            star.addEventListener("mouseout", function() {
                resetStars(note.value);
            });
        }

        function resetStars(note = 0) {
            for (const star of stars) {
                if (star.dataset.value > note) {
                    star.style.color = "black";
                    star.classList.add("lar");
                    star.classList.remove("las");
                } else {
                    star.style.color = "red";
                    star.classList.add("las");
                    star.classList.remove("lar");
                }
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).on('ready', function() {
            $(".vertical-center-4").slick({
                dots: true,
                vertical: true,
                centerMode: true,
                slidesToShow: 4,
                slidesToScroll: 2
            });
            $(".vertical-center-3").slick({
                dots: true,
                vertical: true,
                centerMode: true,
                slidesToShow: 3,
                slidesToScroll: 3
            });
            $(".vertical-center-2").slick({
                dots: true,
                vertical: true,
                centerMode: true,
                slidesToShow: 2,
                slidesToScroll: 2
            });
            $(".vertical-center").slick({
                dots: true,
                vertical: true,
                centerMode: true,
            });
            $(".vertical").slick({
                dots: true,
                vertical: true,
                slidesToShow: 3,
                slidesToScroll: 3
            });
            $(".regular").slick({
                dots: true,
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 3
            });
            $(".center").slick({
                dots: true,
                infinite: true,
                centerMode: true,
                slidesToShow: 5,
                slidesToScroll: 3
            });
            $(".variable").slick({
                dots: true,
                infinite: true,
                variableWidth: true
            });
            $(".lazy").slick({
                lazyLoad: 'ondemand', // ondemand progressive anticipated
                infinite: true
            });
        });
    </script>
</body>

</html>