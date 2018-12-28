<?php 
ob_start();
echo '<center>';
require("conexion.php");
 /*require("funciones.php");*/ 
function buscar_usuarios($datos="",$reporte=""){
if ($reporte=="xls"){
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename=usuarios.xls");
}
require("conexion.php");

$sql = "SELECT `usuarios`.`id`, `usuarios`.`nombre`, `usuarios`.`usuario`, `usuarios`.`password`, `usuarios`.`perfil`, `perfil`.`perfil` as perfilperfil, `usuarios`.`foto`, `usuarios`.`estado`, `usuarios`.`ultimo_login`, `usuarios`.`fecha` FROM `usuarios`  inner join `perfil` on `usuarios`.`perfil` = `perfil`.`perfil`  ";
$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]="";
$cont =  0;
$sql .= ' WHERE ';
foreach ($datos as $id => $dato){
$sql .= ' concat(LOWER(`usuarios`.`id`)," ", LOWER(`usuarios`.`nombre`)," ", LOWER(`usuarios`.`usuario`)," ", LOWER(`usuarios`.`password`)," ", LOWER(`perfil`.`perfil`)," ", LOWER(`usuarios`.`foto`)," ", LOWER(`usuarios`.`estado`)," ", LOWER(`usuarios`.`ultimo_login`)," ", LOWER(`usuarios`.`fecha`)," ") LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"';
$cont ++;
if (count($datos)>1 and count($datos)<>$cont){
$sql .= " and ";
}
}
$sql .=  " ORDER BY `usuarios`.`id` desc LIMIT ";
if (isset($_COOKIE['numeroresultados_usuarios']) and $_COOKIE['numeroresultados_usuarios']!="") $sql .=$_COOKIE['numeroresultados_usuarios'];
else $sql .= "10";
/*echo $sql;*/ 

$consulta = $mysqli->query($sql);
 ?>
<div align="center">
<table border="1" id="tbusuarios">
<thead>
<tr>
<th>Id</th>
<th>Nombre</th>
<th>Usuario</th>
<th>Perfil</th>
<th>Foto</th>
<th>Estado</th>
<th>Ultimo Login</th>
<th>Fecha</th>
<?php if ($reporte==""){ ?>
<th><form id="formNuevo" name="formNuevo" method="post" action="usuarios.php">
<input name="cod" type="hidden" id="cod" value="0">
<input type="submit" name="submit" id="submit" value="Nuevo">
</form>
</th>
<th><form id="formNuevo" name="formNuevo" method="post" action="usuarios.php?xls">
<input name="cod" type="hidden" id="cod" value="0">
<input type="submit" name="submit" id="submit" value="XLS">
</form>
</th>
<?php } ?>
</tr>
</thead><tbody>
<?php 
while($row=$consulta->fetch_assoc()){
 ?>
<tr>
<td><?php echo $row['id']?></td>
<td><?php echo $row['nombre']?></td>
<td><?php echo $row['usuario']?></td>
<td><?php echo $row['perfilperfil']?></td>
<td><?php echo $row['foto']?></td>
<td><?php echo $row['estado']?></td>
<td><?php echo $row['ultimo_login']?></td>
<td><?php echo $row['fecha']?></td>
<?php if ($reporte==""){ ?>
<td>
<form id="formModificar" name="formModificar" method="post" action="usuarios.php">
<input name="cod" type="hidden" id="cod" value="<?php echo $row['id']?>">
<input type="submit" name="submit" id="submit" value="Modificar">
</form>
</td>
<td>
<input type="image" src="img/eliminar.png" onClick="confirmeliminar('usuarios.php',{'del':'<?php echo $row['id'];?>'},'<?php echo $row['id'];?>');" value="Eliminar">
</td>
<?php } ?>
</tr>
<?php 
}/*fin while*/
 ?>
</tbody>
</table></div>
<?php 
}/*fin function buscar*/
if (isset($_GET['buscar'])){
buscar_usuarios($_POST['datos']);
exit();
}
if (isset($_GET['xls'])){
buscar_usuarios('','xls');
exit();
}
if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
$sql = 'DELETE FROM usuarios WHERE id="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con éxito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url=usuarios.php" />
'; 
}else{
?>
Eliminación fallida, por favor compruebe que la usuario no esté en uso
<meta http-equiv="refresh" content="2; url=usuarios.php" />
<?php 
}
}
 ?>
<center>
<h1>Usuarios</h1>
</center><?php 
if (isset($_POST['submit'])){
if ($_POST['submit']=="Registrar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$sql = "INSERT INTO usuarios (`id`, `nombre`, `usuario`, `password`, `perfil`, `foto`, `estado`, `ultimo_login`, `fecha`) VALUES ('".$_POST['id']."', '".$_POST['nombre']."', '".$_POST['usuario']."', '".$_POST['password']."', '".$_POST['perfil']."', '".$_POST['foto']."', '".$_POST['estado']."', '".$_POST['ultimo_login']."', '".$_POST['fecha']."')";
 /*echo $sql;*/
if ($insertar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/ 
echo 'Registro exitoso';
echo '<meta http-equiv="refresh" content="1; url=usuarios.php" />';
 }else{ 
echo 'Registro fallido';
echo '<meta http-equiv="refresh" content="1; url=usuarios.php" />';
}
} /*fin Registrar*/ 
if ($_POST['submit']=="Nuevo"){

$textoh1 ="Registrar";
$textobtn ="Registrar";

echo '<form id="form1" name="form1" method="post" action="usuarios.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="nombre">Nombre:</label><input class=""name="nombre"type="text" id="nombre" value="';if (isset($row['nombre'])) echo $row['nombre'];echo '"';echo ' required ></p>';
echo '<p><label for="usuario">Usuario:</label><input class=""name="usuario"type="text" id="usuario" value="';if (isset($row['usuario'])) echo $row['usuario'];echo '"';echo ' required ></p>';
echo '<p><label for="password">Password:</label><input class=""name="password"type="password" id="password" value="';if (isset($row['password'])) echo $row['password'];echo '"';echo ' required ><input type="checkbox" onclick="document.getElementById(\'password\').type = document.getElementById(\'password\').type == \'text\' ? \'password\' : \'text\'"/><label>Ver</label></p>';
echo '<p><label for="perfil">Perfil:</label>';
$sql5= "SELECT perfil,perfil FROM perfil;";
echo '<select class="" name="perfil" id="perfil"  required>';
echo '<option value="">Seleccione una opci&oacute;n</option>';
$consulta5 = $mysqli->query($sql5);
while($row5=$consulta5->fetch_assoc()){
echo '<option value="'.$row5['perfil'].'"';if (isset($row['perfil']) and $row['perfil'] == $row5['perfil']) echo " selected ";echo '>'.$row5['perfil'].'</option>';
}
echo '</select></p>';
echo '<p><label for="foto">Foto:</label><input class=""name="foto"type="text" id="foto" value="';if (isset($row['foto'])) echo $row['foto'];echo '"';echo ' required ></p>';
echo '<p><label for="estado">Estado:</label><input class=""name="estado"type="number"  min="0" id="estado" value="';if (isset($row['estado'])) echo $row['estado'];echo '"';echo ' required ></p>';
echo '<p><label for="ultimo_login">Ultimo Login:</label><input class=""name="ultimo_login"type="text" id="ultimo_login" value="';if (isset($row['ultimo_login'])) echo $row['ultimo_login'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha">Fecha:</label><input class=""name="fecha"type="text" id="fecha" value="';if (isset($row['fecha'])) echo $row['fecha'];echo '"';echo ' required ></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin nuevo*/ 
if ($_POST['submit']=="Modificar"){

$sql = "SELECT `id`, `nombre`, `usuario`, `password`, `perfil`, `foto`, `estado`, `ultimo_login`, `fecha` FROM `usuarios` WHERE id ='".$_POST['cod']."' Limit 1"; 
$consulta = $mysqli->query($sql);
 /*echo $sql;*/ 
$row=$consulta->fetch_assoc();

$textoh1 ="Modificar";
$textobtn ="Actualizar";
echo '<form id="form1" name="form1" method="post" action="usuarios.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="nombre">Nombre:</label><input class=""name="nombre"type="text" id="nombre" value="';if (isset($row['nombre'])) echo $row['nombre'];echo '"';echo ' required ></p>';
echo '<p><label for="usuario">Usuario:</label><input class=""name="usuario"type="text" id="usuario" value="';if (isset($row['usuario'])) echo $row['usuario'];echo '"';echo ' required ></p>';
echo '<p><label for="password">Password:</label><input class=""name="password"type="password" id="password" value="';if (isset($row['password'])) echo $row['password'];echo '"';echo ' required ><input type="checkbox" onclick="document.getElementById(\'password\').type = document.getElementById(\'password\').type == \'text\' ? \'password\' : \'text\'"/><label>Ver</label></p>';
echo '<p><label for="perfil">Perfil:</label>';
$sql5= "SELECT perfil,perfil FROM perfil;";
echo '<select class="" name="perfil" id="perfil"  required>';
echo '<option value="">Seleccione una opci&oacute;n</option>';
$consulta5 = $mysqli->query($sql5);
while($row5=$consulta5->fetch_assoc()){
echo '<option value="'.$row5['perfil'].'"';if (isset($row['perfil']) and $row['perfil'] == $row5['perfil']) echo " selected ";echo '>'.$row5['perfil'].'</option>';
}
echo '</select></p>';
echo '<p><label for="foto">Foto:</label><input class=""name="foto"type="text" id="foto" value="';if (isset($row['foto'])) echo $row['foto'];echo '"';echo ' required ></p>';
echo '<p><label for="estado">Estado:</label><input class=""name="estado"type="number"  min="0" id="estado" value="';if (isset($row['estado'])) echo $row['estado'];echo '"';echo ' required ></p>';
echo '<p><label for="ultimo_login">Ultimo Login:</label><input class=""name="ultimo_login"type="text" id="ultimo_login" value="';if (isset($row['ultimo_login'])) echo $row['ultimo_login'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha">Fecha:</label><input class=""name="fecha"type="text" id="fecha" value="';if (isset($row['fecha'])) echo $row['fecha'];echo '"';echo ' required ></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin modificar*/ 
if ($_POST['submit']=="Actualizar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$cod = $_POST['cod'];
 /*Instrucción SQL que permite insertar en la BD */ 
$sql = "UPDATE usuarios SET id='".$_POST['id']."', nombre='".$_POST['nombre']."', usuario='".$_POST['usuario']."', password='".$_POST['password']."', perfil='".$_POST['perfil']."', foto='".$_POST['foto']."', estado='".$_POST['estado']."', ultimo_login='".$_POST['ultimo_login']."', fecha='".$_POST['fecha']."'WHERE  id = '".$cod."';";
/* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/
echo 'Modificación exitosa';
echo '<meta http-equiv="refresh" content="1; url=usuarios.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2; url=usuarios.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
<center>
<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultados_usuarios" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultados_usuarios',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultados_usuarios',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultados_usuarios',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>
<span id="txtsugerencias">
<?php 
buscar_usuarios();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById('menu_usuarios').className ='active '+document.getElementById('menu_usuarios').className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ("plantilla.php");
 ?>
