<?php
require_once 'functions.php';
require_once 'sql.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(21600);
    session_start();
}

Functions::checkSession();

$fecha = filter_input(INPUT_POST, 'fecha');

$all = array();
$data = array();
$background = array();
$label = array();

$estado = array();
$empleado = array();
$banco = array();

$pendiente = 0;
$valido = 0;
$novalido = 0;

$vendedor = 0;
$transporte = 0;
$supervisor = 0;

$monto = 0.0;
$mayor = 0.0;
$climayor = '';
$menor = 999999999999.99;
$climenor = '';

$session = $_SESSION['data'];
$bd = explode("-",$session)[1];//0->tipo,1->empresa,2->usuario

$cadena = Functions::conectBD($bd);
$sql = Query::getDatos($fecha);
$stm = sqlsrv_query($cadena,$sql);

while($v = sqlsrv_fetch_array($stm)){
	$monto += $v['monto'];

	if ($v['estado'] == 0) {
		$pendiente++;
	}else if ($v['estado'] == 1) {
		$valido++;
	}else if ($v['estado'] == 2) {
		$novalido++;
	}

	if ($v['tipo'] == 'V') {
		$vendedor++;
	}else if ($v['tipo'] == 'T') {
		$transporte++;
	}else if ($v['tipo'] == 'S') {
		$supervisor++;
	}

	if ($v['monto'] > $mayor) {
		$mayor = $v['monto'];
		$climayor = $v['cliente'].'-'.$v['nomcli'];
	}
	if ($v['monto'] < $menor) {
		$menor = $v['monto'];
		$climenor = $v['cliente'].'-'.$v['nomcli'];
	}
}

$sql = Query::getBancosCount($fecha);
$stm = sqlsrv_query($cadena,$sql);

while ($v = sqlsrv_fetch_array($stm)) {
	$row = array('cantidad'=>$v['cantidad'],'banco'=>$v['banco']);
	array_push($banco, $row);
}

if ($pendiente > 0) {
	array_push($data,$pendiente);
	array_push($background,'#6c757d');
	array_push($label,'PENDIENTE');
}
if ($valido > 0) {
	array_push($data,$valido);
	array_push($background,'#198754');
	array_push($label,'VALIDO');
}
if ($novalido > 0) {
	array_push($data,$novalido);
	array_push($background,'#dc3545');
	array_push($label,'NO VALIDO');
}

$row = array('data'=>$data,'background'=>$background,'label'=>$label);
array_push($all,array('estado'=>$row));

$data = array();
$background = array();
$label = array();

if ($vendedor > 0) {
	array_push($data,$vendedor);
	array_push($background,'#fd7e14');
	array_push($label,'VENDEDOR');
}
if ($transporte > 0) {
	array_push($data,$transporte);
	array_push($background,'#0d6efd');
	array_push($label,'TRANSPORTISTA');
}
if ($supervisor > 0) {
	array_push($data,$supervisor);
	array_push($background,'#6f42c1');
	array_push($label,'SUPERVISOR');
}

$row = array('data'=>$data,'background'=>$background,'label'=>$label);
array_push($all,array('personal'=>$row));

/*if ($pendiente > 0) {
	$row = array('tipo'=>'P','total'=>$pendiente,'color'=>'#6c757d');
	array_push($estado,$row);
}
if ($valido > 0) {
	$row = array('tipo'=>'V','total'=>$valido,'color'=>'#198754');
	array_push($estado,$row);
}
if ($novalido > 0) {
	$row = array('tipo'=>'N','total'=>$novalido,'color'=>'#dc3545');
	array_push($estado,$row);
}

if ($vendedor > 0) {
	$row = array('tipo'=>'V','total'=>$vendedor,'color'=>'#fd7e14');
	array_push($empleado,$row);
}
if ($transporte > 0) {
	$row = array('tipo'=>'T','total'=>$transporte,'color'=>'#0d6efd');
	array_push($empleado,$row);
}
if ($supervisor > 0) {
	$row = array('tipo'=>'S','total'=>$supervisor,'color'=>'#6f42c1');
	array_push($empleado,$row);
}*/
$first = new StdClass;
$second = new StdClass;
$thirdOne = new StdClass;
$thirdTwo = new StdClass;

$thirdOne->monto = round($mayor,2);
$thirdOne->cliente = $climayor;
$thirdTwo->monto = round($menor,2);
$thirdTwo->cliente = $climenor;
$second->voucher = $pendiente+$valido+$novalido;
$second->chart = $all;
$second->monto = round($monto,2);
$second->mayor = $thirdOne;
$second->menor = $thirdTwo;
$second->banco = $banco;
$first->datos = $second;

echo json_encode($first);
?>