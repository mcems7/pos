<?php 
$servidorbd = 'localhost';
$usuariobd = 'root';
$passwordbd = '';
$basededatos = 'merkabueno';
$mysqli = new mysqli ($servidorbd,$usuariobd,$passwordbd,$basededatos);
if (mysqli_connect_errno()){
echo "error".mysqli_connect_errno();}else{
if($mysqli){
mysqli_set_charset($mysqli,'utf8');
}
}
 ini_set('date.timezone', 'America/Bogota');
 date_default_timezone_set('America/Bogota');
 ?>