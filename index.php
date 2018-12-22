<?php 
ob_start();
?>
<?php $contenido = ob_get_contents();
ob_clean();
include ("plantilla.php");
 ?>