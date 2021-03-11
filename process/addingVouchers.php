<?php
require_once 'functions.php';
require_once 'sql.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(21600);
    session_start();
}

Functions::checkSession();

$array = array();

$data = $_SESSION['data'];
$bd = explode("-",$data)[1];//0->tipo,1->empresa,2->usuario

$cadena = Functions::conectBD($bd);
$sql = Query::checkVoucher();
$stm = sqlsrv_query($cadena,$sql);

while($v = sqlsrv_fetch_array($stm)){
	$row = array('empleado'=>$v['empleado_id'],'fecha'=>$v['fecha'],'cliente'=>$v['cliente'],'id'=>$v['id']);
	array_push($array, $row);
}

if (count($array) > 0) {

	foreach ($array as $a) {
		$sql = Query::addVoucher($a['empleado'],$a['fecha'],$a['cliente'],$a['id']);
		$stm = sqlsrv_query($cadena,$sql);
		if ($stm === false) {
			$resp = 'error';
		}else {
			$resp = 'success';
		}
	}

	echo $resp;
}else {
	echo 'vacio';
}
?>