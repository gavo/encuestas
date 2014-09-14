<?php	
	include('common.php');
	include "charts.php";
	if(isset($_GET['id'])){
		$id_pre = $_GET['id'];
		$mysqli=conectar();
		$consulta = "SELECT pregunta FROM pregunta WHERE id_pre = '".$id_pre."'";
		$resultado = $mysqli->query($consulta);
		$pregunta[]="";
		if($resultado){
			while($fila=$resultado->fetch_assoc()){
				$pregunta[] = $fila['pregunta'];
			}
		}
		$consulta = "SELECT respuesta.`respuesta`, COUNT(guarda.`entry`) votos
					FROM respuesta INNER JOIN guarda
					ON respuesta.`id_res` = guarda.`id_res`
					WHERE `id_pre` = '".$id_pre."'GROUP BY respuesta.id_res;";
		$resultado = $mysqli->query($consulta);
		$salida[] = $pregunta; 
		
		if($resultado){
			while($fila=$resultado->fetch_assoc()){
				$salida[]= array($fila['respuesta'],$fila['votos']);
			}
		}
		$mysqli->close();
		$chart['chart_data'] = $salida;
		SendChartData($chart);
	}
?>