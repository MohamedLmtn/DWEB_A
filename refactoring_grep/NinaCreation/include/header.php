<link rel="stylesheet" href="./style.css">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<header>
    <span class="reseaux_sociaux">
        <a href="https://www.facebook.com/ninacreationgeneve"><img src="https://static.wixstatic.com/media/4057345bcf57474b96976284050c00df.png/v1/fill/w_39,h_39,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/4057345bcf57474b96976284050c00df.png"></a>
        <a href="https://www.instagram.com/ninacreationsgeneve/"><img src="https://static.wixstatic.com/media/e1aa082f7c0747168d9cf43e77046142.png/v1/fill/w_39,h_39,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/e1aa082f7c0747168d9cf43e77046142.png"></a>
        <a href="https://www.youtube.com/@ninacreationsgeneve4625"><img src="https://static.wixstatic.com/media/45bce1d726f64f1999c49feae57f6298.png/v1/fill/w_39,h_39,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/45bce1d726f64f1999c49feae57f6298.png"></a>
    </span>
    <ul id="borderblack">

    <!--  LE LOGO  -->
        <li>
            <div class="logo">
                <a href="./index.php"><img src="./img/logo_nina_animation.gif"></a>
            </div>
        </li>

    <!--  LE MENU HOMME FEMME  -->
        <li>
            <nav>
                <ul class="menu">
                    <li style="color:black" class="boutique">Boutique
                        <ul class="submenu">
                            <li><a href="Boutique.php?type=homme">Hommes</a></li>
                            <li><a href="Boutique.php?type=femme">Femmes</a></li> 


                        </ul>
                    </li>
                </ul>
            </nav>

        </li>

    <!--  A PROPOS  -->

        <li>
            <a href="./APropos.php">A Propos</a>
        </li>
    <!--  CONTACT  -->
        <li>
            <a href="./Contact.php">Contact</a>
        </li>
    <!--  CONNEXION/ADMIN/PROFIL   -->
        <li>
            <div class="divconnheader">
                <?php
                if (!isset($_SESSION["idcli"]) && empty($_SESSION["idcli"])) {
                ?>
                    <a href="LogIn/connexion.html?mode=conex">Connexion</a>
                    /
                    <a href="LogIn/connexion.html?mode=insc">Inscription</a>


                <?php

                } else {
                    require_once("./conn.php");
                    $db = conn();

                    $recupphotocli = $db->prepare("select PHOTO_PROFILE,STATUT_CLI from CLIENT where ID_CLIENT = :id");
                    $recupphotocli->execute([":id" => $_SESSION["idcli"]]);
                    $infocli = $recupphotocli->fetch()
                ?>
                    <a href="./profile.php"><img style="margin-right: 0.25cm;" class="imgprofile" src="<?= $infocli["PHOTO_PROFILE"] ?>"></a>
                    <a href="./traitement.php?value=deco">Deconnexion</a>
                    <?php
                    if ($infocli["STATUT_CLI"] == 'admin') {
                    ?>
                        <a href="./admine.php"><img class="imgprofile" src="./img/adminpic.jpg"></a>
                <?php
                    }
                }
                ?>

            </div>
        </li>
    </ul>
    <!--  PANIER  -->
    <span class="lePanier">
        <a href="./panier.php"><img src="./img/panier.jpg" alt="panier_shooping"></a>
    </span>



</header>