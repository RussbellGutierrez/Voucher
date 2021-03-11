<?php
require_once 'functions.php';
require_once 'sql.php';

/*if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(21600);
    session_start();
}else {
	session_regenerate_id();
}*/

if (session_status() === PHP_SESSION_ACTIVE) {
	session_regenerate_id();
}

$query = new Query;
$func = new Functions;

$data = $_SESSION['data'];
$user = explode("-",$data)[2];//0->tipo,1->empresa,2->usuario
$bd = explode("-",$data)[1];//0->tipo,1->empresa,2->usuario
$sid = session_id();

$cadena = $func->conectBD(3);
$sql = $query->checkUser($user,$bd,0);
$stm = sqlsrv_query($cadena,$sql);
sqlsrv_fetch($stm);
$existe = sqlsrv_get_field($stm,0);
$opt = ($existe == 0)? 0 : 1;

$sql = $query->logUser($opt,$user,$bd,$sid);
$add = sqlsrv_query($cadena,$sql);

if( $add === false ) {
    header('Location:/voucher/db');
}else{
	header('Location:/voucher/vp/0/pr');
}
?>