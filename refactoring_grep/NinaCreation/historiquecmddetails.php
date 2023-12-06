<link rel="stylesheet" href="styleHistCom.css">
<div id="myModal<?=$index?>" class="modal">
                        <div class="modal-content">
                           
                            <?php
                            require_once("./conn_phpmyadmin.php");
                             $db = conn();
                             if(isset($_GET["idcmd"]) && !empty($_GET["idcmd"] )&& filter_var($_GET["idcmd"],FILTER_VALIDATE_INT)){

                                $vrifcmd = $db->prepare("select count(*) from COMMANDE where ID_COMMANDE = :idcmd");
                                $vrifcmd->execute([":idcmd"=>$_GET["idcmd"]]);
                                $count = $vrifcmd->fetch();

                                if($count["count(*)"] == 1){
                                    $panier = $db->prepare("select * from COMMANDE_PROD join PRODUIT on COMMANDE_PROD.ID_PRODUIT = PRODUIT.ID_PRODUIT where ID_COMMANDE = :idcmd");
                                    $panier->execute([":idcmd"=>$_GET['idcmd']]);
        
                                    /*SELECT sum(PRODUIT.PRIX_PRODUIT)from COMMANDE join COMMANDE_PROD on COMMANDE.ID_COMMANDE = COMMANDE_PROD.ID_COMMANDE JOIN PRODUIT ON COMMANDE_PROD.ID_PRODUIT = PRODUIT.ID_PRODUIT where ID_CLIENT = :id and STATUT_CMD = 'acheter' and COMMANDE.ID_COMMANDE = :idcmd" */
                                    $prixTot = 0;
                                    while ($prodpanier = $panier->fetch()) {
                                        $imgprod = $db->prepare("SELECT IMAGE_PROD from IMG_PROD where ID_PRODUIT = :id limit 1");
                                        $imgprod->execute(["id" => $prodpanier["ID_PRODUIT"]]);
                                        $IMG = $imgprod->fetch();
                                    ?>
                                        <div id="panierflex">
                                            <div class="fleximg"><img src="<?= $IMG['IMAGE_PROD'] ?>"></div>
                                        </div>
        
                                        <div id="modal-content-details">
                                            <div class="textflex">
                                                <p> Nom: <?= htmlspecialchars($prodpanier["NOM_PRODUIT"]) ?></p>
                                                <p> Prix: <?= htmlspecialchars($prodpanier["PRIX_PRODUIT"]) ?> CHF</p>
                                                <p> Quantité: <?= $prodpanier["QUANTITE_CMD"] ?></p>
        
                                            </div>
                                        </div>
                                    <?php
                                        $prixTot += $prodpanier["PRIX_PRODUIT"] * $prodpanier["QUANTITE_CMD"];
                                }
                                echo "Total de la commande: " . $prixTot . "CHF";
                                
                                }else{
                                    ?>
                                    <script>
                                        alert("Données erronée.")
                                        window.location.href = "commandes.php"
                                    </script>
                                <?php
                                }
                               
                             }else{
                                ?>
                                    <script>
                                        alert("Données erronée.")
                                        window.location.href = "commandes.php"
                                    </script>
                                <?php

                             }
                           
                            
                           
                            ?>
                 </div>