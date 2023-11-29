<?php
require_once('./include/connexion_secure.php');

function conn()
{
    require_once('./include/connexion_secure.php');
    $server = DB_SERVER;
    $nomUtilisateur = DB_USERNAME;
    $pass = DB_PASSWORD;
    $DBname = DB_NAME;

    // Connexion à la base de données
    static $dbb = null;
    if ($dbb === null) {
        try {
            $connection = new PDO("mysql:host=$server;dbname=$DBname", $nomUtilisateur, $pass);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $connection;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
