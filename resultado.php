<?php
	session_start();
	include('common.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Encuesta</title>
<link rel="stylesheet" type"text/css" href="miestilo.css">
</head>
<body>
<?php 
	include('includes/header.php');
?>
<div >
<br><br>
<?php
if(isset($_POST['encuesta'])){
	include "charts.php";
	echo InsertChart( "charts.swf", "charts_library", "generadorReportes.php?tipo=0&enc=".$_POST['encuesta'], 500, 300 );
	echo InsertChart( "charts.swf", "charts_library", "generadorReportes.php?tipo=1&enc=".$_POST['encuesta'], 500, 300 );
	echo InsertChart( "charts.swf", "charts_library", "generadorReportes.php?tipo=2&enc=".$_POST['encuesta'], 1000, 500 );
	$consulta = "SELECT id_pre FROM pregunta WHERE id_enc = '".$_POST['encuesta']."';";

	$mysqli = conectar();
	$resultado = $mysqli->query($consulta);
	if($resultado){
		while($fila=$resultado->fetch_assoc()){
			echo InsertChart ( "charts.swf", "charts_library", "generadorReportes.php?id=".$fila['id_pre'], 500, 300 );
		}
	}
	$mysqli->close();
}else{
	$consulta = "SELECT * FROM encuesta";
	$mysqli = conectar();
	$resultado = $mysqli->query($consulta);
	if($resultado){
		while($fila=$resultado->fetch_assoc()){
			echo '<form method="POST" action="resultado.php">';			
			echo '<input type="hidden" name=encuesta value="'.$fila['id_enc'].'">'.
				  '<input type="submit" value="'.$fila['titulo'].'"></form>';
		}
	}
	$mysqli->close();
}
?><br>
</div>
<?php
	include('includes/footer.php');
?>