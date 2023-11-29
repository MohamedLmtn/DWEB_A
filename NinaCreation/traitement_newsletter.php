<?php
session_start();

require_once("./conn_phpmyadmin.php");
$db = conn();


if (isset($_POST['objet']) && !empty($_POST['objet']) && isset($_POST['message']) && !empty($_POST['message'])) {


    $req = $db->prepare("SELECT EMAIL_CLI , NEWSLETTER_CLI FROM CLIENT WHERE NEWSLETTER_CLI = 'true'");
    $req->execute();

    while ($res = $req->fetch()) {

        echo "bonjour";
        echo $res["EMAIL_CLI"];
        $mail = $res["EMAIL_CLI"];


        $smtp_server = 'smtp.gmail.com';
        $smtp_port = 587;
        $utilisateur = "Info.Ninacreations@gmail.com";
        $mot_de_passe = "Info.Ninacreations206";

        ini_set("auth_username", $utilisateur);
        ini_set("auth_password", $mot_de_passe);

        ini_set("smtp_crypto", "tls");


        $subject = $_POST['objet'];
        $message = $_POST["message"];


        if (mail($mail, $subject, $message)) {
            header("Location: newsletter.php");
        } else {
            echo "Erreur lors de l'envoi de l'e-mail.";
        }
    }
} else if ($_GET["valeur"] == 'true') {

    if (!isset($_SESSION['is_subscribed'])) {
        $_SESSION['is_subscribed'] = false; // Par défaut, l'utilisateur n'est pas abonné
    }

    $le_id = $db->prepare("SELECT NEWSLETTER_CLI FROM CLIENT where ID_CLIENT = :id");
    $le_id->execute([":id" => $_SESSION["idcli"]]);
    $ses_id = $le_id->fetch();


    if ($ses_id["NEWSLETTER_CLI"] == 'true' && isset($_POST['newsletter_action'])) {
        $req = $db->prepare("UPDATE CLIENT set NEWSLETTER_CLI = 'false' where ID_CLIENT = :id");
        $req->execute([":id" => $_SESSION["idcli"]]);
        $_SESSION['is_subscribed'] = false; // Mettez à jour l'état d'abonnement

    } else {

        $req = $db->prepare("UPDATE CLIENT set NEWSLETTER_CLI = 'true' where ID_CLIENT = :id");
        $req->execute([":id" => $_SESSION["idcli"]]);
        $_SESSION['is_subscribed'] = true; // Mettez à jour l'état d'abonnement
    }


    header("location: index.php");
}else{
    
}
