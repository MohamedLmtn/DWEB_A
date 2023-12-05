<?php
require_once( 'requetesql.php' );

function testaddfavtrue()
 {
    echo addfav( 1, 1 ) . '<br>' ;

}

function testaddfavfalse()
 {
    echo addfav( 1, 11 )  ;

}

testaddfavtrue();
testaddfavfalse();
?>