<?php
session_start();
require_once( './conn.php' );
$db = conn();

if ( isset( $_GET[ 'value' ] ) && !empty( $_GET[ 'value' ] ) ) {
    $value = $_GET[ 'value' ];

    if ( $value == 'conn' ) {
        try {

            if ( isset( $_POST[ 'email' ] ) && !empty( $_POST[ 'email' ] ) && filter_var( $_POST[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
                $mailcli = $_POST[ 'email' ];
                $cmd = $db->prepare( "SELECT count(*) as 'existance' from CLIENT where EMAIL_CLI = :mail" );
                $cmd->execute( [ ':mail' => $mailcli ] );
                $rep = $cmd->fetchAll();

                $desactive = $db->prepare( 'SELECT STATUT_CLI from CLIENT where EMAIL_CLI = :mail' );
                $desactive->execute( [ ':mail' => $mailcli ] );
                $inactif = $desactive->fetch();

                if ( $rep[ 0 ][ 'existance' ] < 1 ) {
                    ?>
                    <script>
                    alert( "le mail utilisé <?php echo $mailcli; ?> n'a jamais été inscrit!" );
                    setTimeout( function() {
                        window.location.href = 'LogIn/connexion.html?mode=insc';
                    }
                    , 0 );
                    </script>
                    <?php
                } else if ( $inactif[ 'STATUT_CLI' ] == 'inactif' ) {
                    ?>
                    <script>
                    alert( 'votre compte a été desactivé' );
                    setTimeout( function() {
                        window.location.href = 'LogIn/connexion.html?mode=insc';
                    }
                    , 0 );
                    </script>
                    <?php

                } else {
                    $cmdmdp = $db->prepare( 'SELECT MDP_CLI from CLIENT where EMAIL_CLI = :mail' );
                    $cmdmdp->execute( [ ':mail' => $mailcli ] );
                    $mdphash = $cmdmdp->fetchAll();
                    $password = htmlspecialchars( $_POST[ 'password' ] );

                    if ( password_verify( $password, $mdphash[ 0 ][ 'MDP_CLI' ] ) ) {
                        ?>
                        <script>
                        alert( 'Bienvenue' );
                        </script>
                        <?php
                        $cmdid = $db->prepare( 'SELECT ID_CLIENT from CLIENT where EMAIL_CLI = :mail' );
                        $cmdid->execute( [ ':mail' => $mailcli ] );
                        $id = $cmdid->fetchAll();
                        $_SESSION[ 'idcli' ] = $id[ 0 ][ 'ID_CLIENT' ];

                        header( 'location:index.php' );
                    } else {
                        ?>
                        <script>
                        alert( 'mot de passe incorrect' );
                        setTimeout( function() {
                            window.location.href = 'LogIn/connexion.html?mode=conex';
                        }
                        , 0 );
                        </script>
                        <?php
                    }
                }
            } else {
                ?>
                <script>
                alert( 'Mail incorrect' );
                </script>
                <?php
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } catch ( Exception $e ) {

            echo $e->getMessage();
        }
    } else if ( $value == 'inscription' ) {

        try {
            if (
                isset( $_POST[ 'nom' ] ) && !empty( $_POST[ 'nom' ] ) &&
                isset( $_POST[ 'prenom' ] ) && !empty( $_POST[ 'prenom' ] ) &&
                isset( $_POST[ 'numtel' ] ) && !empty( $_POST[ 'numtel' ] ) &&
                filter_var( $_POST[ 'email' ], FILTER_VALIDATE_EMAIL ) &&
                isset( $_POST[ 'password' ] ) && !empty( $_POST[ 'password' ] )
            ) {
                $mailcli = $_POST[ 'email' ];
                $cmd = $db->prepare( 'SELECT * from CLIENT where EMAIL_CLI = :mail' );
                $cmd->execute( [ ':mail' => $mailcli ] );
                $rep = $cmd->rowCount();

                if ( $rep == 1 ) {
                    ?>
                    <script>
                    alert( 'vous êtes déjà inscrit' );
                    setTimeout( function() {
                        window.location.href = 'LogIn/connexion.html?mode=conex';
                    }
                    , 0 );
                    </script>
                    <?php
                } else if ( $rep < 1 ) {

                    $cmdinscr = $db->prepare( 'insert into CLIENT (NOM_CLI,PRENOM_CLI,TEL_CLI,EMAIL_CLI,MDP_CLI) values (:nom,:prenom,:tel,:mail,:pwd)' );
                    $cmdinscr->execute( [ ':nom' => $_POST[ 'nom' ], ':prenom' => $_POST[ 'prenom' ], 'tel' => $_POST[ 'numtel' ], 'mail' => $_POST[ 'email' ], ':pwd' => password_hash( $_POST[ 'password' ], PASSWORD_DEFAULT ) ] );

                    if ( isset( $_FILES[ 'profilpic' ] ) && $_FILES[ 'profilpic' ][ 'error' ] == 0 ) {
                        $imgname = $_FILES[ 'profilpic' ][ 'name' ];
                        $contenuFichier = file_get_contents( $_FILES[ 'profilpic' ][ 'tmp_name' ] );

                        $cheminDestination = './photos/profilpic/' . $imgname;

                        if ( file_put_contents( $cheminDestination, $contenuFichier ) ) {
                            $updateprofilpic = $db->prepare( 'update CLIENT set PHOTO_PROFILE = :cheminphoto where ID_CLIENT = LAST_INSERT_ID()' );
                            $updateprofilpic->execute( [ ':cheminphoto' => $cheminDestination ] );
                        }
                    }
                    ?>
                    <script>
                    alert( 'Vous etes inscrits !' );
                    setTimeout( function() {
                        window.location.href = 'LogIn/connexion.html?mode=conex';
                    }
                    , 0 );
                    </script>
                    <?php
                }

            } else {
                ?>
                <script>
                alert( 'données invalides !' );
                </script>
                <?php
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    } else if ( $value == 'mail' ) {
        $destinataire = filter_var( $_POST[ 'maildest' ], FILTER_SANITIZE_EMAIL );
        $sujet = htmlspecialchars( $_POST[ 'objectmail' ] );
        $message = htmlspecialchars( $_POST[ 'message' ] );
        $message .= ' ' . $destinataire;

        ini_set( 'SMTP', 'smtp.gmail.com' );
        ini_set( 'smtp_port', 587 );

        $utilisateur = 'Info.Ninacreations@gmail.com';
        $mot_de_passe = 'Info.Ninacreations206';

        ini_set( 'auth_username', $utilisateur );
        ini_set( 'auth_password', $mot_de_passe );

        ini_set( 'smtp_crypto', 'tls' );

        if ( filter_var( $destinataire, FILTER_VALIDATE_EMAIL ) && mail( 'Info.Ninacreations@gmail.com', $sujet, $message ) ) {
            ?><script>
            alert( 'Message envoyé avec succés.' )
            window.location.href = 'index.php';
            </script> <?php
        } else {
            ?><script>
            alert( "Erreur lors de l'envoi du message." );
            window.location.href = 'Contact.php';
            </script> <?php

        }
    } else if ( $value == 'notation' ) {

        if (
            isset( $_POST[ 'produit' ] ) && filter_var( $_POST[ 'produit' ], FILTER_VALIDATE_INT ) &&
            isset( $_POST[ 'note' ] ) && filter_var( $_POST[ 'note' ], FILTER_VALIDATE_INT, $option = array( 'option' => array( 'min' => 1, 'max' => 5 ) ) ) &&
            filter_var( $_POST[ 'produit' ], FILTER_VALIDATE_INT ) &&
            isset( $_POST[ 'titre' ] ) && !empty( $_POST[ 'titre' ] )
        ) {

            $existenceprod = $db->prepare( 'select count(*) from PRODUIT where ID_PRODUIT = :idprod' );
            $existenceprod->execute( [ ':idprod' => $_POST[ 'produit' ] ] );
            $prodexiste = $existenceprod->fetch();
            if ( $prodexiste[ 'count(*)' ] == 1 ) {

                $notationprod = $db->prepare( 'INSERT INTO AVIS (ID_CLIENT, ID_PRODUIT, NOTE_AVIS, DESCRIPTION_AVIS, DATE_AVIS, TYPE_AVIS,titre_avis) VALUES (:idcli, :idprod, :note, :descriptionprod, NOW(), :typeavis,:titre)' );
                $notationprod->execute( [
                    ':idcli' => $_SESSION[ 'idcli' ],
                    ':idprod' => $_POST[ 'produit' ],
                    ':note' => $_POST[ 'note' ],
                    ':descriptionprod' => $_POST[ 'comment' ],
                    ':typeavis' => 'public',
                    ':titre' => $_POST[ 'titre' ]
                ] );

                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            } else {
                ?>
                <script>
                alert( 'données erroné' );
                </script>
                <?php
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } else {
            ?>
            <script>
            alert( 'données erroné' );
            </script>
            <?php
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
        }
    } else if ( $value == 'ajoutpanier' ) {
        if (
            isset( $_GET[ 'idprod' ] ) && filter_var( $_GET[ 'idprod' ], FILTER_VALIDATE_INT ) &&
            isset( $_GET[ 'quantitercmd' ] ) && filter_var( $_GET[ 'quantitercmd' ], FILTER_VALIDATE_INT, $option = array( 'option' => array( 'min_range' => 1 ) ) )
        ) {

            $existenceprod = $db->prepare( 'select count(*) from PRODUIT where ID_PRODUIT = :idprod' );
            $existenceprod->execute( [ ':idprod' => $_GET[ 'idprod' ] ] );
            $prodexiste = $existenceprod->fetch();
            if ( $prodexiste[ 'count(*)' ] == 1 ) {

                $verifprodpanier = $db->prepare( "SELECT * from COMMANDE where ID_CLIENT = :id and STATUT_CMD = 'en cours'" );
                $verifprodpanier->execute( [ ':id' => $_SESSION[ 'idcli' ] ] );
                $count = $verifprodpanier->fetch();
                $conteur = $verifprodpanier->rowCount();

                if ( $conteur == 0 ) {

                    $recupstockprod = $db->prepare( 'SELECT STOCK_PRODUIT FROM PRODUIT where ID_PRODUIT = :idprod' );
                    $recupstockprod->execute( [ ':idprod' => $_GET[ 'idprod' ] ] );
                    $stockprod = $recupstockprod->fetch();

                    if ( $_GET[ 'quantitercmd' ] <= $stockprod[ 'STOCK_PRODUIT' ] ) {

                        $ajoutpanier = $db->prepare( 'INSERT into COMMANDE(ID_CLIENT,DATE_CMD) values(:idcli,now())' );
                        $ajoutpanier->execute( [ ':idcli' => $_SESSION[ 'idcli' ] ] );

                        $ajoutpanierprod = $db->prepare( 'INSERT into COMMANDE_PROD(ID_PRODUIT,ID_COMMANDE,QUANTITE_CMD) values(:idprod,LAST_INSERT_ID(),:quant)' );
                        $ajoutpanierprod->execute( [ ':idprod' => $_GET[ 'idprod' ], ':quant' => $_GET[ 'quantitercmd' ] ] );
                        header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
                    } else {
                        ?>
                        <script>
                        alert( 'la quantité de la commande peut pas dépasser le stock maximal du produit' );
                        window.location.href = "produit.php?idprod=<?= $IDCMD["ID_PRODUIT"] ?>"
                        </script>
                        <?php
                    }
                } else {
                    $verifprodcommandprod = $db->prepare( "SELECT * from COMMANDE join COMMANDE_PROD ON COMMANDE_PROD.ID_COMMANDE = COMMANDE.ID_COMMANDE
                                 where COMMANDE_PROD.ID_PRODUIT= :idprod and COMMANDE_PROD.ID_COMMANDE = :idcmd and STATUT_CMD = 'en cours'" );
                    $verifprodcommandprod->execute( [ ':idcmd' => $count[ 'ID_COMMANDE' ], ':idprod' => $_GET[ 'idprod' ] ] );
                    $countcmdprod = $verifprodcommandprod->rowCount();

                    if ( $countcmdprod == 1 ) {

                        $recupidcmd = $db->prepare( "SELECT COMMANDE_PROD.ID_COMMANDE,COMMANDE_PROD.QUANTITE_CMD,COMMANDE_PROD.ID_PRODUIT from COMMANDE 
                                join COMMANDE_PROD ON COMMANDE_PROD.ID_COMMANDE = COMMANDE.ID_COMMANDE where ID_CLIENT = :idcli AND COMMANDE_PROD.ID_PRODUIT = :idprod" );
                        $recupidcmd->execute( [ ':idcli' => $_SESSION[ 'idcli' ], 'idprod' => $_GET[ 'idprod' ] ] );
                        $IDCMD = $recupidcmd->fetch();

                        $recupstockprod = $db->prepare( 'SELECT STOCK_PRODUIT FROM PRODUIT where ID_PRODUIT = :idprod' );
                        $recupstockprod->execute( [ ':idprod' => $IDCMD[ 'ID_PRODUIT' ] ] );
                        $stockprod = $recupstockprod->fetch();

                        $stockapresajout = $IDCMD[ 'QUANTITE_CMD' ] + $_GET[ 'quantitercmd' ];

                        if ( $stockapresajout <= $stockprod[ 'STOCK_PRODUIT' ] ) {
                            $updatequantitercmd = $db->prepare( 'UPDATE COMMANDE_PROD set QUANTITE_CMD = QUANTITE_CMD + :quantite where ID_COMMANDE = :idcmd' );
                            $updatequantitercmd->execute( [ ':idcmd' => $IDCMD[ 'ID_COMMANDE' ], ':quantite' => $_GET[ 'quantitercmd' ] ] );

                            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
                        } else {
                            ?>
                            <script>
                            alert( 'la quantité de la commande peut pas dépasser le stock maximal du produit' );
                            window.location.href = "produit.php?idprod=<?= $IDCMD["ID_PRODUIT"] ?>"
                            </script>
                            <?php

                        }
                    } else {
                        $recupidcmd = $db->prepare( "SELECT ID_COMMANDE FROM COMMANDE WHERE COMMANDE.ID_CLIENT = :idcli and STATUT_CMD = 'en cours'" );
                        $recupidcmd->execute( [ ':idcli' => $_SESSION[ 'idcli' ] ] );
                        $IDCMD = $recupidcmd->fetch();

                        $recupstockprod = $db->prepare( 'SELECT STOCK_PRODUIT FROM PRODUIT where ID_PRODUIT = :idprod' );
                        $recupstockprod->execute( [ ':idprod' => $_GET[ 'idprod' ] ] );
                        $stockprod = $recupstockprod->fetch();

                        if ( $_GET[ 'quantitercmd' ]  <= $stockprod[ 'STOCK_PRODUIT' ] ) {
                            $insertnvprodcmd = $db->prepare( 'INSERT INTO `COMMANDE_PROD`(`ID_PRODUIT`, `ID_COMMANDE`, `QUANTITE_CMD`) VALUES (:idprod,:idcmd,:quantitercmd)' );
                            $insertnvprodcmd->execute( [ ':idprod' => $_GET[ 'idprod' ], 'idcmd' => $count[ 'ID_COMMANDE' ], ':quantitercmd' => $_GET[ 'quantitercmd' ] ] );
                            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
                        } else {
                            ?>
                            <script>
                            alert( 'la quantité de la commande peut pas dépasser le stock maximal du produit' );
                            window.location.href = "produit.php?idprod=<?= $_GET["idprod"] ?>"
                            </script>
                            <?php
                        }
                    }
                }
            } else {
                ?>
                <script>
                alert( "le produit n'existe pas" )
                </script>
                <?php
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } else {
            ?>
            <script>
            alert( 'données erroné' )
            </script>
            <?php
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
        }
    } else if ( $value == 'deco' ) {
        session_destroy();
        header( 'location:index.php' );
    } else if ( $value == 'qntchng' ) {

        if ( isset( $_POST[ 'qntcmd' ] ) && filter_var( $_POST[ 'qntcmd' ], FILTER_VALIDATE_INT ) && isset( $_POST[ 'idcmd' ] ) && filter_var( $_POST[ 'idcmd' ], FILTER_VALIDATE_INT ) ) {
            try {
                $existancecmd = $db->prepare( 'select count(*) from COMMANDE_PROD where ID_COMMANDE = :idcmd' );
                $existancecmd->execute( [ 'idcmd' => $_POST[ 'idcmd' ] ] );
                $cmd = $existancecmd->fetch();

                if ( $cmd[ 'count(*)' ] == 1 ) {
                    $ajoutquant = $db->prepare( 'update COMMANDE_PROD set QUANTITE_CMD = :quant where ID_COMMANDE = :idcmd' );
                    $ajoutquant->execute( [ ':quant' => $_POST[ 'qntcmd' ], 'idcmd' => $_POST[ 'idcmd' ] ] );
                    ?>
                    <script>
                    alert( 'le panier a été mis a jour' );
                    </script>
                    <?php
                    header( 'Location:panier.php' );
                } else {
                    ?>
                    <script>
                    alert( 'la commande existe pas' )
                    </script>
                    <?php
                    header( 'Location:panier.php' );
                }
            } catch ( PDOException $e ) {
                echo $e->getMessage();
            }
        }
    } else if ( $value == 'delprodpan' ) {
        try {

            $existancecmd = $db->prepare( 'select count(*) from COMMANDE_PROD where ID_COMMANDE = :idcmd' );
            $existancecmd->execute( [ 'idcmd' => $_GET[ 'idcmd' ] ] );
            $cmd = $existancecmd->fetch();

            if ( $cmd[ 'count(*)' ] == 1 ) {
                $delprodpan = $db->prepare( 'DELETE FROM COMMANDE_PROD WHERE ID_COMMANDE = :IDCOMMANDE and ID_PRODUIT = :idprod' );
                $delprodpan->execute( [ ':IDCOMMANDE' => $_GET[ 'idcmd' ], ':idprod'=> $_GET[ 'idprod' ] ] );

                header( 'Location:panier.php' );
            } else {
                ?>
                <script>
                alert( 'la commande existe pas' )
                </script>
                <?php
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } catch ( PDOException $e ) {
            header( 'Location:panier.php' );
        }
        ;
    } else if ( $value == 'ajoutfav' ) {

        if ( isset( $_GET[ 'idprod' ] ) && filter_var( $_GET[ 'idprod' ], FILTER_VALIDATE_INT ) ) {
            $existenceprod = $db->prepare( 'select count(*) from PRODUIT where ID_PRODUIT = :idprod' );
            $existenceprod->execute( [ ':idprod' => $_GET[ 'idprod' ] ] );
            $prodexiste = $existenceprod->fetch();
            if ( $prodexiste[ 'count(*)' ] == 1 ) {

                $favprod = $db->prepare( 'insert into FAVORI values(:idcli,:idprod)' );
                $favprod->execute( [ ':idcli' => $_SESSION[ 'idcli' ], ':idprod' => $_GET[ 'idprod' ] ] );
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } else {
            ?>
            <script>
            alert( "le produit n'existe pas" )
            </script>
            <?php
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
        }
    } else if ( $value == 'delfav' ) {

        $existenceprod = $db->prepare( 'select count(*) from PRODUIT where ID_PRODUIT = :idprod' );
        $existenceprod->execute( [ ':idprod' => $_GET[ 'idprod' ] ] );
        $prodexiste = $existenceprod->fetch();
        if ( $prodexiste[ 'count(*)' ] == 1 ) {
            if ( isset( $_GET[ 'idprod' ] ) && filter_var( $_GET[ 'idprod' ], FILTER_VALIDATE_INT ) ) {
                $favprod = $db->prepare( 'delete from FAVORI where ID_PRODUIT = :idprod' );
                $favprod->execute( [ ':idprod' => $_GET[ 'idprod' ] ] );
                header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            }
        } else {
            ?>
            <script>
            alert( "le produit n'existe pas" )
            </script>
            <?php
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
        }
    }
}

?>