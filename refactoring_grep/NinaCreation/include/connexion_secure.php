<?php


// function userName()
// {
//     return 'hhva_t24_2_v2';
// }

// function db_name()
// {
//     return 'hhva_t24_2_v2';
// }

// function hashage()
// {

//     $x = '5pK0tzJnF';
//     $hashed_password = password_hash($x, PASSWORD_BCRYPT);
//     $is_verified = password_verify($x, $hashed_password);

//     return $hashed_password;
// }
// function serveur()
// {
//     return 'hhva.myd.infomaniak.com';
// }
$x = '5pK0tzJnF';
define('DB_SERVER', 'hhva.myd.infomaniak.com');
define('DB_USERNAME', 'hhva_t24_2_v2');
define('DB_PASSWORD', $x);
define('DB_NAME', 'hhva_t24_2_v2');
