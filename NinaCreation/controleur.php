<?php
 require_once("requetesql.php");
if (isset($_GET["idprod"]) && !empty($_GET["idprod"])) {
    session_start();
    if ($_GET["action"] == "delfav") {
        delfav($_GET["idprod"],$_SESSION["idcli"]);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }else{
        addfav($_GET["idprod"],$_SESSION["idcli"]);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
function prod5etoile(){  
  
   $reqprods = produit5etoile();
    echo "<div id='prod'>";
    $index = 0;
    while ($prod = $reqprods->fetch()) {
        $IMG = imageprod($prod["ID_PRODUIT"]);
    ?>
        <div>
            <?php
            if (isset($_SESSION["idcli"]) && !empty($_SESSION["idcli"])) {
                $fav = existanceclivav($_SESSION["idcli"],$prod["ID_PRODUIT"]);
                $imgsrc = "./img/nonfav.png";
                if ($fav["count(*)"] == 1) {       
                     $imgsrc = "./img/fav.png";}?>
                    <div>
                        <a class="lienfav" href="javascript:void(0);" onclick="addfav(<?= $index ?>)">
                            <img id="imgfav<?= $index ?>" class="favnonfav" src=<?=$imgsrc?>>
                        </a>
                    </div>
                <input type="hidden" id="idprod<?= $index ?>" value="<?= $prod["ID_PRODUIT"] ?>">
            <?php } ?>
            <a style="z-index: 1;" href="./produit.php?idprod=<?= $prod["ID_PRODUIT"] ?>"><img src="<?= $IMG["IMAGE_PROD"] ?>"></a>
            <p><?= $prod["NOM_PRODUIT"] ?></p>
        </div>
    <?php
        $index++;
    }
    echo "</div>";}?> 