<?php

function conn()
 {
    try {

        $connexion = new PDO( 'mysql:host=localhost;dbname=ninacreation', 'Admin_Nina', '' );
        $connexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $connexion->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
        return $connexion;
    } catch ( PDOException $e ) {
        echo 'Erreur de connexion : ' . $e->getMessage();
    }
}

