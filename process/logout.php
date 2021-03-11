<?php
require_once 'functions.php';
require_once 'sql.php';

if (session_status() === PHP_SESSION_ACTIVE) {

	if (isset($_SESSION['data']) && !empty($_SESSION['data'])) {
		
		$query = new Query;
		$func = new Functions;

		$data = $_SESSION['data'];
		$user = explode("-",$data)[2];//0->tipo,1->empresa,2->usuario
		$bd = explode("-",$data)[1];//0->tipo,1->empresa,2->usuario
		$sid = session_id();

		$cadena = $func->conectBD(3);
		$sql = $query->checkUser($user,$bd,$sid);
		$stm = sqlsrv_query($cadena,$sql);
		sqlsrv_fetch($stm);
		$existe = sqlsrv_get_field($stm,0);

		if ($existe == 0) {
			header('Location:/voucher/advertencia');
		}else {
			$sql = $query->logUser(2,$user,$bd,$sid);
			$out = sqlsrv_query($cadena,$sql);

			if( $out === false ) {
			    header('Location:/voucher/db');
			}else{
				session_destroy();
				$_SESSION = [];
				header('Location:/voucher/');
			}
		}
	}else {
		header('Location:/voucher/nd');
	}
}else {
	header('Location:/voucher/exp');
}
?>