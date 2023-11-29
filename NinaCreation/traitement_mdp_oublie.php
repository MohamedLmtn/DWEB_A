<?php
session_start();
require_once("./conn_phpmyadmin.php");
$db = conn();



function generateRandomCode()
{
    if (!isset($_SESSION['code']) && empty($_SESSION['code'])) {
        $_SESSION['code'] = rand(pow(10, 6 - 1), pow(10, 6) - 1);
    }
}


if ($_GET["value"] == "envoi_du_mail") {

    $mailverif = $db->prepare("SELECT ID_CLIENT FROM CLIENT WHERE EMAIL_CLI = :mail");
    $mailverif->execute([":mail" => $_POST["mail"]]);
    $result = $mailverif->fetch();

    if (empty($result)) {
?>

        <script>
            alert("Vous n'Ãªtes pas inscrit.");
            setTimeout(function() {
                window.location.href = "LogIn/connexion.html?mode=conex";
            }, 0);
        </script>

        <?php
    } else {

        generateRandomCode();
        $_SESSION["mail"] = $_POST["mail"];
        $email = $_POST["mail"];
        $requeteverif = $db->prepare("SELECT EMAIL_CLI FROM CLIENT WHERE EMAIL_CLI = :mail");
        $requeteverif->execute([":mail" => $email]);
        $result = $requeteverif->fetch();


        $recipient_email = $_POST["mail"];

        // ParamÃ¨tres du serveur SMTP (le cas Ã©chÃ©ant)
        $smtp_server = 'smtp.gmail.com';
        $smtp_port = 587; // Port SMTP sÃ©curisÃ© (587 est gÃ©nÃ©ralement utilisÃ©)
        $utilisateur = "Info.Ninacreations@gmail.com";
        $mot_de_passe = "Info.Ninacreations206";

        ini_set("auth_username", $utilisateur);
        ini_set("auth_password", $mot_de_passe);

        ini_set("smtp_crypto", "tls");

        // Sujet et corps de l'e-mail (peuvent Ãªtre personnalisÃ©s)
        $subject = 'Réinitialisation de mot de passe';
        $message = "Votre code de réinitialisation de mot de passe est : " . $_SESSION['code'];

        // En-tÃªtes de l'e-mail
        // $headers = "From: $utilisateur\r\n";
        // $headers .= "Reply-To: $recipient_email\r\n";
        // $headers .= "Content-Type: text/plain\r\n"; // Utilisez text/plain pour le contenu texte

        // Utilisation de la fonction mail() pour envoyer l'e-mail
        if (mail($recipient_email, $subject, $message)) {
            header("Location: motdepasseoublier.php?code_fourni_mail=true&mailrec=" . $email);
        } else {
            echo "Erreur lors de l'envoi de l'e-mail.";
        }
    }
} elseif ($_GET["value"] == "nvmpd") {
    try {

        $requid = $db->prepare("SELECT ID_CLIENT from CLIENT where EMAIL_CLI = :email");
        $requid->execute([":email" => $_SESSION["mail"]]);
        $idcli = $requid->fetch();
        $updatemdp = $db->prepare("update CLIENT set MDP_CLI = :nvmdp where ID_CLIENT = :id");
        $updatemdp->execute([":nvmdp" => password_hash($_POST["newmdp"], PASSWORD_DEFAULT), ":id" => $idcli["ID_CLIENT"]]);
        ?>
        <script>
            alert("le mots de passe a été changé avec succès");
            setTimeout(function() {
                window.location.href = "LogIn/connexion.html?mode=conex";
            }, 0);
        </script>
        <?php
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} elseif ($_GET["value"] == "code_fourni_mail") {
    try {
        if ($_SESSION['code'] == $_POST['code_de_mail']) {
            unset($_SESSION['code']);
            header("Location: motdepasseoublier.php?nvmdp=true&mailrec=" . $_GET["mailrec"]);
        } else {
        ?>
            <script>
                alert("code invalide");
                setTimeout(window.location.href = "motdepasseoublier.php", 0);
            </script>
<?php

        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
