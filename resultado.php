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
<header><center><img src="img/Header.png"/></center>
</header><center>
<nav>
<ul>
    <li><a title="Opcion 1" href="Index.php">Inicio</a></li>
    <li><a title="Opcion 2" href="#">Resultado</a></li>
    <li><a title="Opcion 3" href="#">Salir</a></li>  
</ul>
</nav>
<?php

//include charts.php to access the InsertChart function
include "charts.php";

echo InsertChart ( "charts.swf", "charts_library", "sample.php", 400, 250 );

?>

<footer>
<center><img src="img/footer.jpg" /></center>
</footer>
</body>
</html>