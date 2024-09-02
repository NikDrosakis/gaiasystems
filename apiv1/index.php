<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: GET,POST,PUT,DELETE");
header("Access-Control-Allow-Credentials: true");
header("Authorization: Basic " . base64_encode('nikos:130177')); //bXlzZWNyZXR0b2tlbjE3

define('TEMPLATE', 'vivalibro');
define('ADMIN_ROOT', '/var/www/gs/admin/');
include ADMIN_ROOT."autoload.php";
    use Core\Vivalibro;
    $gaia = new Vivalibro();
?>