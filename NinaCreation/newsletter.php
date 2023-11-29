<!DOCTYPE html>
<html>

<head>
    <title>Formulaire de Newsletter</title>
    <link rel="stylesheet" href="stylenewsletter.css">
</head>

<body>
    <h1>Formulaire de Newsletter</h1>
    <form action="traitement_newsletter.php" method="post">
        <label for="objet">Objet :</label>
        <input type="text" id="objet" name="objet" required>
        <br><br>
        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea>
        <br><br>
        <input type="submit" value="Envoyer">
    </form>
    <a href="admine.php"><button class="retour-button">Retour</button></a>
</body>

</html>
