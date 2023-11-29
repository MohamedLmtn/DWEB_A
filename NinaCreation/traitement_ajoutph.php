<?php
require_once("./conn.php");
$db = conn();

if (isset($_GET["valeur"]) && $_GET["valeur"] == "deleteimage") {

    try {
        $delete_stmt = $db->prepare("DELETE FROM IMG_PROD WHERE :image_id = ID_IMGPROD");
        $delete_stmt->execute([":image_id" => $_GET["le_id"]]);

        if ($delete_stmt->rowCount() > 0) {
            echo "L'image a été supprimée avec succès.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "Erreur lors de la suppression de l'image.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
