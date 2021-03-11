<?php
require_once 'conexion.php';
require_once 'sql.php';

class Functions {

	function conectBD($basedatos) {
		switch($basedatos) {
			case 1:
				$bd = Conexion::conectOri();
				break;
			case 2:
				$bd = Conexion::conectTerra();
				break;
			default:
				$bd = Conexion::conectUsers();
				break;
		}
		return $bd;
	}

	function checkSession() {

		if (session_status() === PHP_SESSION_ACTIVE) {

			if (isset($_SESSION['data']) && !empty($_SESSION['data'])) {

				$data = $_SESSION['data'];
				$user = explode("-",$data)[2];//0->tipo,1->empresa,2->usuario
				$bd = explode("-",$data)[1];//0->tipo,1->empresa,2->usuario
				$sid = session_id();

				$cadena = self::conectBD(3);
				$sql = Query::checkUser($user,$bd,$sid);
				$stm = sqlsrv_query($cadena,$sql);
				sqlsrv_fetch($stm);
				$existe = sqlsrv_get_field($stm,0);
				
				if ($existe == 0) {
					header('Location:/voucher/advertencia');
				}
			}else {
				header('Location:/voucher/nd');
			}
		}else {
			header('Location:/voucher/exp');
		}
	}
}
?>