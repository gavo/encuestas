<header><center><img src="img/Header.png"/></center>
</header><center>
<nav>
<ul>
    <li><a title="Opcion 1" href="Index.php">Inicio</a></li>
    <?PHP if (isset($_SESSION['nombre']))echo '<li><a title="Opcion 3" href="Encuesta.php">Encuestas</a></li>';?> 
    <li><a title="Opcion 2" href="Resultado.php">Resultado</a></li> 
</ul>
</nav>