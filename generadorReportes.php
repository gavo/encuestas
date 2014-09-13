<?php
	@header("Content-type:txt/php");		
	@header('Content-Disposition: attachment; filename= pregunta.php');	
	if(isset($_GET['pregunta'])){	
		echo "<?php echo 'hola mundo';?>";		
	}
?>