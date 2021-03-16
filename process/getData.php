<?php
require_once 'functions.php';
require_once 'sql.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(21600);
    session_start();
}

Functions::checkSession();

$fecha = filter_input(INPUT_POST, 'fecha');

$array = array();

$data = $_SESSION['data'];
$bd = explode("-",$data)[1];//0->tipo,1->empresa,2->usuario

$cadena = Functions::conectBD($bd);
$sql = Query::getDatos($fecha);
$stm = sqlsrv_query($cadena,$sql);

while($v = sqlsrv_fetch_array($stm)){
	$monto = round($v['monto'],2);
	$row = array('empleado'=>$v['emp'],'nombre'=>$v['nom'],'tipo'=>$v['tipo'],'fecha'=>$v['fecha'],'id'=>$v['id'],'cliente'=>$v['cliente'],'nomcli'=>$v['nomcli'],'banco'=>$v['banco'],'movimiento'=>$v['movimiento'],'monto'=>$monto,'estado'=>$v['estado'],'descripcion'=>$v['descrip'],'usercheck'=>$v['usercheck'],'fechacheck'=>$v['fechacheck'],'observacion'=>$v['observacion']);
	array_push($array, $row);
}

echo json_encode(array('data'=>$array));
?>