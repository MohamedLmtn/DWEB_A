<?php

$db = conn();
$sql = $db->prepare("select * from PARAMETRE");
$sql->execute();
$param = $sql->fetchAll();
$params = $param[0];
?>

<footer>



    <form action="traitement_newsletter.php?valeur=true" method="post">
        
            <button type="submit" name="newsletter_action">newsletter

              
        </button>
    </form>


    <div id="auto-email">
        <p>
            ATELIER NINA CREATIONS
        </p>
        <p>
            Contact :
            <a data-auto-recognition="true" href="mailto:ATELIERNINACREATIONS@GMAIL.COM">ATELIERNINACREATIONS@GMAIL.COM</a>
        </p>
        <p><?php echo $params['TEL_ENTREPRISE'] ?></p>
        </p>
    </div>
    <a href="../"></a>


</footer>