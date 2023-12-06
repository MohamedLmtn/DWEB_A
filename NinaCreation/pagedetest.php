<?php
require_once( 'requetesql.php' );

function testaddfavtrue()
 {
    echo addfav( 1, 1, true ) . '<br>' ;

}

function testaddfavfalse()
 {
    echo addfav( 1, 11 ) ;

}

testaddfavtrue();
testaddfavfalse();
?>