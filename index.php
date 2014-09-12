<?php
	session_start();
	include('common.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Encuesta</title>
<link rel="stylesheet" type"text/css" href="miestilo.css">
<script>
	function validacion(){
		cntreg = document.getElementsByName('id').item(0).value;
		_rad = document.getElementsByName('rad');
		_pass = document.getElementsByName('pass').item(0).value;
		if(cntreg == ""){
			alert('ERROR: Debe poner un numero de Carnet o Registro si es Universitario');
			return false;
		}
		if(isNaN(cntreg)){
			alert('ERROR: En el campo "C.I o Nº Reg:" sólo se permiten valores numéricos');
			return false;
		}
		var seleccionado = false;
		var n=0;
		for(var i=0; i<_rad.length; i++) {    
		  if(_rad[i].checked) {
			seleccionado = true;
			n=i;
			break;
		  }
		}
		if(_rad.item(n).value == "univ"){
			if(_pass==""){
				alert('Error: Ha seleccionado universitario y no puso su contraseña');
				return false;
			}
		}		
		return true;
	}
</script>
</head>
<body>
<header><center><img src="img/Header.png"/></center>
</header><center>
<nav>
<ul>
    <li><a title="Opcion 1" href="Index.php">Inicio</a></li>
    <li><a title="Opcion 2" href="#">Encuestar</a></li>
    <li><a title="Opcion 3" href="#">Resultados</a></li>  
</ul>
</nav>

<?php
	if(isset($_POST['registrar'])){// AQUI SE REGISTRA A LOS NUEVOS VISITANTES
		$mysqli = conectar();
		$consulta = "INSERT INTO visitante(entry,nombre,estudia)VALUE('".
					strtoupper($_POST['newID'])."','".
					strtoupper($_POST['newNombre'])."','".
					strtoupper($_POST['newEstudia'])."');";
		$mysqli->query($consulta);
		$mysqli->close();
		$_SESSION['entry']=strtoupper($_POST['newID']);
		$_SESSION['nombre']=strtoupper($_POST['newNombre']);
		$_SESSION['estudia']=strtoupper($_POST['newEstudia']);
		if(isset($_SESSION['entry']) && isset($_SESSION['nombre']) && isset($_SESSION['estudia'])){
			header('Location: encuesta.php');
		}else{
			die('Error al registrar los Datos');
		}
	}else{// SI NO SE VA REGISTRAR 
	if(!isset($_POST['id'])){ // SI NO SE HA ENVIAADO UN ID PARA VERIFICAR
		if(isset($_GET['tipo'])){
			if($_GET['tipo']=='univ'){// SI ES UNIVERSITARIO
?>
				<div align="center" title="login"><br>
				<h3>Formulario de Inicio de Sesion para Universitarios</h3>
				  <form name="login" method="post" action="" onSubmit="return validacion()">
					Nro. Registro :<input type="text" name="id"><br>
					<label onClick="alert('Su Contraseña es cualquiera de sus Nombres o Apellidos')"> 
					&nbsp;&nbsp; Contraseña :</label><input type="password" name="pass" disabled><br>					
					<input type="submit" value="Siguiente"><br>
				  </form>
				</div>
				<br>
<?php	
			}else{  // SI NO ES UNIVERSITARIO
?>

				<div align="center" title="login"><br>
				<h3>Formulario de Inicio de Sesion para Visitantes</h3>
					<form name="registro" action="index.php" method="post">
					<br>Nombre:<input name="newNombre" type="text"><br>
					Colegio:<select name="newEstudia">
					  <?php
								$mysqli=conectar();
								$consulta="SELECT estudia FROM estudia WHERE flag<1 ORDER BY estudia";
								$resultado = $mysqli->query($consulta);
								if($resultado){
									while($fila=$resultado->fetch_assoc()){
										echo "<option value='".utf8_encode($fila['estudia'])."'>".utf8_encode($fila['estudia'])."</option>";
									}
								}
							?>
					</select>
					<br>
					<input type="submit" value="Registrar" name="registrar">
				  </form>
				</div>
<?php
			}
		}else{
?>
			<div title="login" align="center">
				// aqui van los botones YEYO
			</div>
<?php
		}
	}else{  // ENVIADO EL POST, SE PROCEDE A VERIFICAR SI EL USUARIO ESTÁ REGISTRADO EN LA BASE DE DATOS
		if(!isset($_POST['pass'])){// Si no tiene contraseña, se crea el valor en blanco para hacer la consulta
			$pass = "x";
		}else{// caso contrario se asigna el valor pasado por post
			$pass = sha1(strtoupper($_POST['pass']));
		}
		session_destroy();
		session_start();
		$mysqli = conectar();
		$consulta = "SELECT * FROM visitante WHERE entry='".$_POST['id']."' AND pass = '".$pass."';";
		$resultado = $mysqli->query($consulta);
		if($resultado){
			while($fila=$resultado->fetch_assoc()){
				$_SESSION['entry']=strtoupper($fila['entry']);
				$_SESSION['nombre']=strtoupper(utf8_encode($fila['nombre']));
				$_SESSION['estudia']=strtoupper(utf8_encode($fila['estudia']));
			}			
		}
		$mysqli->close();
		// si se optuvieron los datos de sesion, se redirecciona a la pagina de encuestas
		if(isset($_SESSION['entry']) && isset($_SESSION['nombre']) && isset($_SESSION['estudia'])){
			header('Location: encuesta.php');
		}
	?>
  	
<?php
    	}
	}
	?>
<footer>
<center><img src="img/footer.jpg" /></center>
</footer>
</body>
</html>