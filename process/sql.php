<?php
class Query{

	function checkUser($usuario,$emp,$sid) {
		return "select count(*) 
				from oltp.userv
				where usuario = '".$usuario."'  
				and empresa = ".$emp." 
				and (('" . $sid . "' <> '0' AND sid = '" . $sid . "') OR '" . $sid . "' = '0') ";
	}

	function logUser($opt,$usuario,$emp,$sid) {
		if ($opt == 0) {
			$sql = "insert into oltp.userv(usuario,empresa,sid,activo) values ('".$usuario."',".$emp.",'".$sid."',1)";
		}else if ($opt == 1) {
			$sql = "update oltp.userv set sid= '".$sid."', activo= 1 where usuario= '".$usuario."' and empresa= ".$emp." ";
		}else {
			$sql = "update oltp.userv set activo= 0 where sid= '".$sid."' and usuario= '".$usuario."' and empresa= ".$emp." ";
		}
		return $sql;
	}

	function getDatos($fecha) {
		return "select rc.empleado_id as emp,p.d_perso as nom,rc.empleado_tipo as tipo,convert(varchar,rc.fecha,120) as fecha,rc.id,rc.cliente,c.nomcli,rc.banco,rc.movimiento,rc.monto,rv.estado,ev.descrip,rv.usercheck,convert(varchar,rv.fechacheck,20) as fechacheck,rv.observacion 
				from oltp.recibo_cliente rc
				inner join oltp.recibo_verificado rv on rc.empleado_id=rv.empleado_id and rc.fecha=rv.fecha and rc.cliente=rv.cliente and rc.id=rv.id 
				inner join chess.perscom p on rc.empleado_id=p.c_perso 
				inner join oltp.estado_voucher ev on ev.estado=rv.estado
				inner join chess.clialias c on rc.cliente=c.idcliente 
				and rc.fecha like '".$fecha."' ";
	}

	function getBancosCount($fecha) {
		return "select count(banco) cantidad,banco 
				from oltp.recibo_cliente 
				where fecha like '".$fecha."' 
				group by banco ";
	}

	function checkVoucher() {
		return "select rc.empleado_id,convert(varchar,rc.fecha,120) as fecha,rc.cliente,rc.id
				from oltp.recibo_cliente rc
				where not exists (select 1 
				from oltp.recibo_verificado rv 
				where rc.empleado_id=rv.empleado_id 
				and rc.fecha=rv.fecha 
				and rc.cliente=rv.cliente 
				and rc.id=rv.id) ";
	}

	function addVoucher($empleado,$fecha,$cliente,$id) {
		return "insert into oltp.recibo_verificado(empleado_id,fecha,cliente,id,estado,observacion) values (".$empleado.",'".$fecha."',".$cliente.",".$id.",1,'')";
	}

	function updateVoucher($estado,$usuario,$empleado,$fecha,$cliente,$id,$banco,$monto,$operacion,$observacion) {
		return "update oltp.recibo_verificado set estado =".$estado.", usercheck ='".$usuario."', fechacheck =convert(varchar,getdate(),20), observacion='".$observacion."' where empleado_id=".$empleado." and fecha='".$fecha."' and cliente=".$cliente." and id=".$id."; 
			update oltp.recibo_cliente set banco='".$banco."', monto=".$monto.", movimiento='".$operacion."' where empleado_id=".$empleado." and fecha='".$fecha."' and cliente=".$cliente." and id=".$id." ";
	}
}