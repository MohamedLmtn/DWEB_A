<?php
session_start();
require_once("./conn.php");
$db = conn();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_GET["valeur"]) && $_GET["valeur"] === "imgcli") {

        if (isset($_SESSION["idcli"]) && !empty($_SESSION["idcli"])) {

            $idcli = $_SESSION["idcli"];

            $suppimg = $db->prepare("SELECT PHOTO_PROFILE FROM CLIENT WHERE ID_CLIENT = :id");
            $suppimg->execute([":id" => $idcli]);

            $image = $suppimg->fetch();

            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

                if ($image["PHOTO_PROFILE"] == "./photos/profilpic/profiledefault.jpg") {

                    insertimage($db, $idcli);
                } else if (file_exists($image["PHOTO_PROFILE"])) {

                    if (unlink($image["PHOTO_PROFILE"])) {

                        insertimage($db, $idcli);
                    } else {
                        echo "Erreur lors de la suppression de l'image existante.";
                    }
                } else {
                    echo "L'image existante n'a pas pu être trouvée.";
                }
            } else {
                echo "Aucune image insérée.";
            }
        } else {
            echo "L'identifiant du client n'a pas été spécifié.";
        }
    } else if (isset($_GET["valeur"]) && $_GET["valeur"] === "imgprod") {

        if (isset($_POST["idprod"]) && !empty($_POST["idprod"])) {

            var_dump($_FILES["image"]);


            foreach ($_FILES["image"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["image"]["tmp_name"][$key];
                    $name = basename($_FILES["image"]["name"][$key]);
                    $destination = "./photos/" . $name;

                    if (move_uploaded_file($tmp_name, $destination)) {
                        $insertimageprod = $db->prepare("INSERT INTO IMG_PROD (ID_PRODUIT, IMAGE_PROD) VALUES (:idprod, :images)");
                        $insertimageprod->execute([":idprod" => $_POST["idprod"], ":images" => $destination]);
                    } else {
                        echo "Une erreur s'est produite lors de l'upload du fichier $name.<br>";
                    }
                } else {
                    echo "Une erreur s'est produite lors de l'upload du fichier.<br>";
                }
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "L'ID du produit n'a pas été spécifié.";
        }
    } 
}
function insertimage($db, $idcli)
{

    $imgname = basename($_FILES["image"]["name"]);
    $imgchemin = $_FILES["image"]["tmp_name"];

    $cheminDestination = "./photos/profilpic/" . $imgname;

    if (move_uploaded_file($imgchemin, $cheminDestination)) {

        $updateprofilpic = $db->prepare("UPDATE CLIENT SET PHOTO_PROFILE = :cheminphoto WHERE ID_CLIENT = :id");
        $updateprofilpic->execute([":cheminphoto" => $cheminDestination, ":id" => $idcli]);
        header("Location: index.php");
    } else {
        echo "Erreur lors de l'insertion de l'image.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_GET["valeur"]) && $_GET["valeur"] == "ajoutProduit") {
        // Récupérer les données du formulaire
        $nom_produit = $_POST["nom_produit"];
        $id_typeprod = $_POST["id_typeprod"];
        $id_categorieprod = $_POST["id_categorieprod"];
        $description_produit = $_POST["description_produit"];
        $prix_produit = $_POST["prix_produit"];
        $stock_produit = $_POST["stock_produit"];

        // Insertion du nouveau produit dans la base de données
        $sql = "INSERT INTO PRODUIT (ID_TYPEPROD, ID_CATEGORIEPROD, NOM_PRODUIT, DESCRIPTION_PRODUIT, PRIX_PRODUIT, STOCK_PRODUIT)
            VALUES (:id_typeprod, :id_categorieprod, :nom_produit, :description_produit, :prix_produit, :stock_produit)";

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id_typeprod', $id_typeprod, PDO::PARAM_INT);
        $stmt->bindParam(':id_categorieprod', $id_categorieprod, PDO::PARAM_INT);
        $stmt->bindParam(':nom_produit', $nom_produit, PDO::PARAM_STR);
        $stmt->bindParam(':description_produit', $description_produit, PDO::PARAM_STR);
        $stmt->bindParam(':prix_produit', $prix_produit, PDO::PARAM_STR);
        $stmt->bindParam(':stock_produit', $stock_produit, PDO::PARAM_INT);

        if ($stmt->execute()) { ?>
            <script>
                alert("Le produit a bien été ajouté")
                window.location.href = "admine.php";
            </script> <?php

                    } else { ?>
            <script>
                alert("Erreur lors de l'ajout d'un produit ")
                window.location.href = "admine.php";
            </script> <?php
                    }
                }
            }
if($_GET["valeur"] == "statutcpmt"){
    require_once("./conn.php");
    $db = conn();
    try{
        $updatestatutcli = $db->prepare("UPDATE CLIENT set STATUT_CLI = :statut where ID_CLIENT = :idcli");
        $updatestatutcli->execute([":idcli"=>$_GET["idcli"],":statut"=>$_GET["statut"]]);
        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }catch(PDOException $e){
        echo $e->getMessage();
    }


}else if($_GET["valeur"] == "statutprod"){
    require_once("./conn.php");
    $db = conn();
    try{
        $updatestatutprod = $db->prepare("UPDATE PRODUIT set STATUT_PROD = :statut where ID_PRODUIT = :IDPROD");
        $updatestatutprod->execute([":IDPROD"=>$_GET["idprod"],":statut"=>$_GET["statut"]]);
        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

