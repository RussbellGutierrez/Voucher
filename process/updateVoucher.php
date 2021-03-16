<?php
require_once 'functions.php';
require_once 'sql.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(21600);
    session_start();
}

Functions::checkSession();

$estado = filter_input(INPUT_POST, 'estado');
$usuario = filter_input(INPUT_POST, 'usuario');
$empleado = filter_input(INPUT_POST, 'empleado');
$fecha = filter_input(INPUT_POST, 'fecha');
$cliente = filter_input(INPUT_POST, 'cliente');
$id = filter_input(INPUT_POST, 'id');
$banco = filter_input(INPUT_POST, 'banco');
$operacion = filter_input(INPUT_POST, 'operacion');
$monto = filter_input(INPUT_POST, 'monto');
$observacion = filter_input(INPUT_POST, 'observacion');

$array = array();

$data = $_SESSION['data'];
$bd = explode("-",$data)[1];//0->tipo,1->empresa,2->usuario

$cadena = Functions::conectBD($bd);
$sql = Query::updateVoucher($estado,$usuario,$empleado,$fecha,$cliente,$id,$banco,$monto,$operacion,$observacion);
$stm = sqlsrv_query($cadena,$sql);

if ($stm === false) {
	$resp = 'error';
}else {
	$resp = 'success';
}

echo $resp;
?>