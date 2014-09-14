<?php
	session_start();
	include('common.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?PHP header("Content-Type: text/html;charset=utf-8");?>
<meta http-equiv="Refresh" content="5;url=encuesta.php">
<title>Encuestas Electronicas</title>
<link rel="stylesheet" type"text/css" href="miestilo.css">
</head>
<body>
<?php
	include('includes/header.php');
	if(!isset($_POST['encuesta'])){
		die("Usted no esta autorizado para votar");
	}
	$consulta = "SELECT id_pre FROM pregunta WHERE id_enc ='".$_POST['encuesta']."' ORDER BY id_pre";
	$mysqli = conectar();
	$resultado = $mysqli->query($consulta);
	$verificacion = NULL;
	if($resultado){
		while($fila = $resultado->fetch_assoc()){
			$verificacion[] = $fila['id_pre'];
		}
	}
	$mysqli->close();
	for($i = 0;$i< count($verificacion);$i++){
		if(!isset($_POST['p'.$verificacion[$i]]))
			die('FALTAN PREGUNTAS POR RESPONDER EN SU ENCUESTA');	
	}
	if(!isset($_SESSION['entry'])){
		die('Usted no esta autorizado para votar');
	}
	$enc = $_POST['encuesta'];
	$vot = $_SESSION['entry'];
	$mysqli = conectar();
	$consulta = "SELECT MAX(entry)+1 entry FROM guarda";
	$respuesta = $mysqli->query($consulta);
	$num = 1;
	if($respuesta){
		while($fila = $respuesta->fetch_assoc()){
			if($fila['entry']!= NULL)
				$num = $fila['entry'];
		}
	}
	$mysqli->close();
	$r = NULL;
	$mysqli = conectar();
	$consulta = "SELECT fecha FROM responde WHERE id_enc = '".$enc."' AND ci = '".$vot."'"; 
	$flag = false;
	$respuesta = $mysqli->query($consulta);
	if($respuesta){
		while($fila = $respuesta->fetch_assoc()){
			$fecha = $fila['fecha'];
			$flag = true;
		}
	}
	
	if(!$flag){
		for($i = 0;$i< count($verificacion);$i++){
			$r = $_POST['p'.$verificacion[$i]];	
			$consulta = "INSERT INTO guarda(entry,id_pre,id_res)VALUES('".$_SESSION['entry']."','".$verificacion[$i]."','".$r."');";
			$mysqli->query($consulta);
		}
		$consulta = "INSERT INTO participa(id_enc,entry)VALUES('".$enc."','".$vot."');";
		$mysqli->query($consulta);
		$mysqli->close();?>
	<div id="divError">
	<br><br><br>
	<label id="labelError">Su encuesta esta siendo procesada...<br> Gracias por participar ...<br> sera redireccionado... la pagina de selección de encuestas...<br> por si desea participar de otra encuesta</label>
	</div>	
	   	
		<?php 
	}else{
		die('ERROR: Usted ya participo de esta encuesta en esta fecha:"'.$fecha.'"');
	}
	include('includes/footer.php');
?>

