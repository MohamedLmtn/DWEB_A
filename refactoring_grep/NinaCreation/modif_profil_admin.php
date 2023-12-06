<!-- modifier_profil.php -->
<?php session_start();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleModifierProfil.css">
    <title>Modifier Profil Admin</title>
</head>

<body>
    <div class="container">
        <div class="profile">
            <?php
            require_once("./conn_phpmyadmin.php");
            $db = conn();


            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $requeteProfile = $db->prepare("SELECT * FROM CLIENT WHERE ID_CLIENT = :id");
                $requeteProfile->execute([":id" => $_POST["idcli"]]);
                $infoClient = $requeteProfile->fetch();

                $nouveauNom = $_POST["nouveau_nom"];
                $nouveauPrenom = $_POST["nouveau_prenom"];
                $nouvelEmail = $_POST["nouvel_email"];
                $nouveauNumero = $_POST["nouveau_numero"];
                $nouveauStatut = $_POST["nouveau_statut"];

                $changement = false;
                if ($nouveauStatut !== $infoClient["STATUT_CLI"] || $nouveauNom !== $infoClient["NOM_CLI"] || $nouveauPrenom !== $infoClient["PRENOM_CLI"] || $nouvelEmail !== $infoClient["EMAIL_CLI"] || $nouveauNumero !== $infoClient["TEL_CLI"]) {
                    $changement = true;
                }

                if ($changement) {
                    try {
                        $miseAJourProfile = $db->prepare("UPDATE CLIENT SET STATUT_CLI = :stat,NOM_CLI = :nom, PRENOM_CLI = :prenom, EMAIL_CLI = :email, TEL_CLI = :tel WHERE ID_CLIENT = :id");
                        $miseAJourProfile->execute([
                            ":stat" => $nouveauStatut,
                            ":nom" => $nouveauNom,
                            ":prenom" => $nouveauPrenom,
                            ":email" => $nouvelEmail,
                            ":tel" => $nouveauNumero,
                            ":id" => $_POST["idcli"]
                        ]);
                    } catch (PDOException $e) {
                        $e->getMessage();
                    }
            ?>
                    <script>
                        alert("Les données ont été mises à  jour avec succés.")
                        window.location.href = "admine.php?requete=client";
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("Aucun changement apporté...")
                        window.location.href = "admine.php?requete=client";
                    </script>
                <?php
                }
            } else {
                $requeteProfile = $db->prepare("SELECT * FROM CLIENT WHERE ID_CLIENT = :id");
                $requeteProfile->execute([":id" => $_GET["client_id"]]);
                $infoClient = $requeteProfile->fetch();
                ?>
                <h2>Modifier votre Profil</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="nouveau_nom">Statut du client:</label>
                    <select id="nouveau_statut" name="nouveau_statut">
                        <option value="admin" <?= ($infoClient["STATUT_CLI"] === "admin") ? "selected" : "" ?>>Admin</option>
                        <option value="normal" <?= ($infoClient["STATUT_CLI"] === "normal") ? "selected" : "" ?>>Normal</option>
                        <option value="inactif" <?= ($infoClient["STATUT_CLI"] === "inactif") ? "selected" : "" ?>>Inactif</option>
                    </select><br>
                    <label for="nouveau_nom">Nouveau Nom:</label>
                    <input type="text" id="nouveau_nom" name="nouveau_nom" value="<?= htmlspecialchars($infoClient["NOM_CLI"]) ?>"><br>

                    <label for="nouveau_prenom">Nouveau Prénom:</label>
                    <input type="text" id="nouveau_prenom" name="nouveau_prenom" value="<?= htmlspecialchars($infoClient["PRENOM_CLI"]) ?>"><br>

                    <label for="nouvel_email">Nouvel Email:</label>
                    <input type="email" id="nouvel_email" name="nouvel_email" value="<?= htmlspecialchars($infoClient["EMAIL_CLI"]) ?>"><br>

                    <label for="nouveau_numero">Nouveau Numéro de téléphone:</label>
                    <input type="text" id="nouveau_numero" name="nouveau_numero" value="<?= htmlspecialchars($infoClient["TEL_CLI"]) ?>"><br>

                    <label for="typecli">Type client</label>



                    <input type="hidden" name="idcli" value="<?= $_GET["client_id"] ?>">

                    <button type="submit">Mettre à jour</button>
                </form>
            <?php
            }

            ?>
        </div>
    </div>
</body>

</html>