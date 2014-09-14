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
	if(isset($_GET['enc']) && isset($_GET['tipo'])){
		if($_GET['tipo']==0){
			$titulo = array('','Visitantes por Colegio');
		}
		if($_GET['tipo']==1){
			$titulo = array('','Visitantes por Carrera');
		}
		$mysqli = conectar();
		if($_GET['tipo']==2){
			$titulo = array('','Visitantes Totales');
			$sql = "SELECT estudia.`flag`, COUNT(participa.entry) n
					FROM participa INNER JOIN visitante INNER JOIN estudia
					ON participa.`entry` = visitante.`entry` AND estudia.`estudia` = visitante.`estudia`
					WHERE participa.`id_enc` = '".$_GET['enc']."' AND estudia.`flag`< 3
					GROUP BY estudia.`flag`";
			$salida[]= $titulo;
			$resultado = $mysqli->query($sql);
			if($resultado){
				while($fila = $resultado->fetch_assoc()){
					if($fila['flag']==0){
						$salida[]= array('Universitario',$fila['n']);
					}else{
						$salida[]= array('Visitante',$fila['n']);
					}
				}
			}
		}else{
			$sql = "SELECT visitante.`estudia`, COUNT(participa.entry) n
					FROM participa INNER JOIN visitante INNER JOIN estudia
					ON participa.`entry` = visitante.`entry` AND estudia.`estudia` = visitante.`estudia`
					WHERE participa.`id_enc` = ".$_GET['enc']." AND estudia.`flag`= ".$_GET['tipo']."
					GROUP BY visitante.`estudia`";
			$salida[]= $titulo;
			$resultado = $mysqli->query($sql);
			if($resultado){
				while($fila = $resultado->fetch_assoc()){
					$salida[]= array($fila['estudia'],$fila['n']);
				}
			}
		}
		$mysqli->close();
		$chart['chart_data'] = $salida;
		SendChartData($chart);
	}
	
	
?>