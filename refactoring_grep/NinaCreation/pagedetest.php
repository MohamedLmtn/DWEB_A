<?php
require_once( 'requetesql.php' );

function testaddfavtrue()
 {
    echo "Test avec l'id client 1 et le produit numero 1 est : ".addfav( 1, 1, true ) . '<br>' ;

}

function testaddfavfalse()
 {
    echo "Test avec l'id client 11 et le produit numero 1 est : ".addfav( 1, 11, false ) ;

}

testaddfavtrue();
testaddfavfalse();
?>