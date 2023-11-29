<?php
function connexion(){
    require_once("./conn.php");
    $db = conn();
    return $db;
}
function produit5etoile(){
    $db = connexion();
    $reqprods = $db->prepare("SELECT DISTINCT PRODUIT.ID_PRODUIT,NOM_PRODUIT FROM PRODUIT 
    JOIN TYPE_PROD ON TYPE_PROD.ID_TYPEPROD = PRODUIT.ID_TYPEPROD 
    JOIN CATEGORIE_PROD ON CATEGORIE_PROD.ID_CATEGORIEPROD = PRODUIT.ID_CATEGORIEPROD
    JOIN AVIS on AVIS.ID_PRODUIT = PRODUIT.ID_PRODUIT where NOTE_AVIS = 5  AND PRODUIT.STATUT_PROD = 'disponible' limit 6");
    
        $reqprods->execute();
        $resultat = $reqprods;
        return $resultat;
}
function imageprod($idprod){
    $db = connexion();
    $imgprod = $db->prepare("SELECT IMAGE_PROD from IMG_PROD where ID_PRODUIT = :id limit 1");
        $imgprod->execute(["id" => $idprod]);
        $IMG = $imgprod->fetch();
        return $IMG;
}

function existanceclivav($idcli,$idprod){
    $db = connexion();
    $favprodcli = $db->prepare("select count(*) from FAVORI where ID_CLIENT = :cli and ID_PRODUIT = :prod");
                $favprodcli->execute(["cli" => $idcli, "prod" => $idprod]);
                $fav = $favprodcli->fetch();
                return $fav;
}


function addfav($idprod,$idcli){
    try{
        $db = connexion();
        if (isset($idprod) && filter_var($idprod, FILTER_VALIDATE_INT)) {
            $existenceprod = $db->prepare("select count(*) from PRODUIT where ID_PRODUIT = :idprod");
            $existenceprod->execute([":idprod" => $idprod]);
            $prodexiste = $existenceprod->fetch();
            if ($prodexiste["count(*)"] == 1) {
                $favprod = $db->prepare("insert into FAVORI values(:idcli,:idprod)");
                $favprod->execute([":idcli" => $idcli, ":idprod" => $idprod]);
            }
            ?>
            <script>
                 alert("le produit n'existe pas")
            </script>
            <?php
            }
        return "valid";
    }catch(Exception $e){
        return "nonvalid";
    }
   
}

function delfav($idprod,$idcli){ 
    $db = connexion();
    if (isset($idprod) && filter_var($idprod, FILTER_VALIDATE_INT)) {
        $existenceprod = $db->prepare("select count(*) from PRODUIT where ID_PRODUIT = :idprod");
        $existenceprod->execute([":idprod" => $idprod]);
        $prodexiste = $existenceprod->fetch();
        if ($prodexiste["count(*)"] == 1) {
            if (isset($_GET["idprod"]) && filter_var($idprod, FILTER_VALIDATE_INT)) {
                $favprod = $db->prepare("delete from FAVORI where ID_PRODUIT = :idprod and ID_CLIENT = :idcli");
                $favprod->execute([":idprod" => $idprod,":idcli"=>$idcli]);

        }
    } else {
?>
<script>
alert("le produit n'existe pas")
</script>
<?php
    }
}}

function testaddfavtrue(){
    assertEquals("valid",addfav(2,1));
}
function testaddfavfalse(){
    assertEquals("nonvalid",addfav(12,11));
}
?>