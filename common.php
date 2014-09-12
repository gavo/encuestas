<?php 
	
	function conectar(){
		$mysqli = mysqli_connect('localhost','root','','enc_sis');
		if($mysqli->connect_errno)
			die('No se pudo establecer conexion con la base de datos');
		else
			return $mysqli;	
	}
	class pregunta{
		var $id_pre;
		var $pregunta;
		var $respuesta;
		var $id_res;
		var $id_enc;
		
		function instanciar($id_p,$id_e){
			$this->id_pre = $id_p;
			$this->id_enc = $id_e;
			$mysqli = conectar();
		
			$consulta = "SELECT pregunta.id_pre,pregunta.pregunta, respuesta.respuesta,respuesta.`id_res`
						FROM pregunta INNER JOIN oferta INNER JOIN respuesta
						ON pregunta.`id_pre` = oferta.`id_pre` AND respuesta.`id_res` = oferta.`id_res`
						WHERE pregunta.`id_enc` = '".$this->id_enc."' AND pregunta.id_pre = '".$this->id_pre."'";
			$resultado = $mysqli->query($consulta);
			if($resultado){
				while($fila = $resultado->fetch_assoc()){	
					$this->pregunta = utf8_encode($fila['pregunta']);
					$this->id_res[] = $fila['id_res'];
					$this->respuesta[] = utf8_encode($fila['respuesta']);
				}
			}
			$mysqli->close();
		}
		
		function listar($id_enc){
			$consulta = "SELECT id_pre FROM pregunta WHERE id_enc = '".$id_enc."'";
			$mysqli = conectar();
			$r = NULL;
			$resultado = $mysqli->query($consulta);
			if($resultado){
				while($fila = $resultado->fetch_assoc()){
					$r[] = $fila['id_pre'];	
				}
			}
			$mysqli->close();
			$preguntas = NULL;
			echo '<form method="POST" action="encuestar.php" onsubmit="return validar()">'."\n";
			echo '<input type="hidden" name=encuesta value="'.$id_enc.'">';
			for($i=0;$i<count($r);$i++){
				$P = new pregunta();
				$P->instanciar($r[$i],$id_enc);
				$preguntas[] = $P;	
				$P->mostrarPreguntas();
			}
			echo '<input type="submit" name="votar" value="Votar"></form>'."\n";
			return $preguntas;				
		}
		
		function mostrarPreguntas(){
			echo '<div id="pregunta""><label>'.$this->pregunta.'</label><br>';	
			$Salida = NULL;
			for($i=0;$i<count($this->respuesta);$i++){
				echo '<input type="radio" name="p'.	$this->id_pre.'" value="'.$this->id_res[$i].'"><LABEL>'.$this->respuesta[$i].'</LABEL><br>'."\n";
				$salida[]='p'.$this->id_pre;
			}
			echo '</div>';
		}
	}


?>