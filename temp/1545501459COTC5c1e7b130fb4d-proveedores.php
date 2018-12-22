<?php 
ob_start();
echo '<center>';
require("conexion.php");
 /*require("funciones.php");*/ 
function buscar_proveedores($datos="",$reporte=""){
if ($reporte=="xls"){
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename=proveedores.xls");
}
require("conexion.php");

$sql = "SELECT `proveedores`.`id`, `proveedores`.`nit`, `proveedores`.`empresa`, `proveedores`.`nombre`, `proveedores`.`telefono`, `proveedores`.`direccion`, `proveedores`.`correo`, `proveedores`.`pedido`, `proveedores`.`observacion` FROM `proveedores`   ";
$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]="";
$cont =  0;
$sql .= ' WHERE ';
foreach ($datos as $id => $dato){
$sql .= ' concat(LOWER(`proveedores`.`id`)," ", LOWER(`proveedores`.`nit`)," ", LOWER(`proveedores`.`empresa`)," ", LOWER(`proveedores`.`nombre`)," ", LOWER(`proveedores`.`telefono`)," ", LOWER(`proveedores`.`direccion`)," ", LOWER(`proveedores`.`correo`)," ", LOWER(`proveedores`.`pedido`)," ", LOWER(`proveedores`.`observacion`)," ") LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"';
$cont ++;
if (count($datos)>1 and count($datos)<>$cont){
$sql .= " and ";
}
}
$sql .=  " ORDER BY `proveedores`.`id` desc LIMIT ";
if (isset($_COOKIE['numeroresultados_proveedores']) and $_COOKIE['numeroresultados_proveedores']!="") $sql .=$_COOKIE['numeroresultados_proveedores'];
else $sql .= "10";
/*echo $sql;*/ 

$consulta = $mysqli->query($sql);
 ?>
<div align="center">
<table border="1" id="tbproveedores">
<thead>
<tr>
<th>Id</th>
<th>NIT</th>
<th>Empresa</th>
<th>Nombre</th>
<th>Teléfono</th>
<th>Dirección</th>
<th>Correo</th>
<th>Pedido</th>
<th>Observación</th>
<?php if ($reporte==""){ ?>
<th><form id="formNuevo" name="formNuevo" method="post" action="proveedores.php">
<input name="cod" type="hidden" id="cod" value="0">
<input type="submit" name="submit" id="submit" value="Nuevo">
</form>
</th>
<th><form id="formNuevo" name="formNuevo" method="post" action="proveedores.php?xls">
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
<td><?php echo $row['nit']?></td>
<td><?php echo $row['empresa']?></td>
<td><?php echo $row['nombre']?></td>
<td><?php echo $row['telefono']?></td>
<td><?php echo $row['direccion']?></td>
<td><?php echo $row['correo']?></td>
<td><?php echo $row['pedido']?></td>
<td><?php echo $row['observacion']?></td>
<?php if ($reporte==""){ ?>
<td>
<form id="formModificar" name="formModificar" method="post" action="proveedores.php">
<input name="cod" type="hidden" id="cod" value="<?php echo $row['id']?>">
<input type="submit" name="submit" id="submit" value="Modificar">
</form>
</td>
<td>
<input type="image" src="img/eliminar.png" onClick="confirmeliminar('proveedores.php',{'del':'<?php echo $row['id'];?>'},'<?php echo $row['id'];?>');" value="Eliminar">
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
buscar_proveedores($_POST['datos']);
exit();
}
if (isset($_GET['xls'])){
buscar_proveedores('','xls');
exit();
}
if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
$sql = 'DELETE FROM proveedores WHERE id="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con éxito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url=proveedores.php" />
'; 
}else{
?>
Eliminación fallida, por favor compruebe que la usuario no esté en uso
<meta http-equiv="refresh" content="2; url=proveedores.php" />
<?php 
}
}
 ?>
<center>
<h1>Proveedores</h1>
</center><?php 
if (isset($_POST['submit'])){
if ($_POST['submit']=="Registrar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$sql = "INSERT INTO proveedores (`id`, `nit`, `empresa`, `nombre`, `telefono`, `direccion`, `correo`, `pedido`, `observacion`) VALUES ('".$_POST['id']."', '".$_POST['nit']."', '".$_POST['empresa']."', '".$_POST['nombre']."', '".$_POST['telefono']."', '".$_POST['direccion']."', '".$_POST['correo']."', '".$_POST['pedido']."', '".$_POST['observacion']."')";
 /*echo $sql;*/
if ($insertar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/ 
echo 'Registro exitoso';
echo '<meta http-equiv="refresh" content="1; url=proveedores.php" />';
 }else{ 
echo 'Registro fallido';
echo '<meta http-equiv="refresh" content="1; url=proveedores.php" />';
}
} /*fin Registrar*/ 
if ($_POST['submit']=="Nuevo"){

$textoh1 ="Registrar";
$textobtn ="Registrar";

echo '<form id="form1" name="form1" method="post" action="proveedores.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="nit">NIT:</label><input class=""name="nit"type="text" id="nit" value="';if (isset($row['nit'])) echo $row['nit'];echo '"';echo ' required ></p>';
echo '<p><label for="empresa">Empresa:</label><input class=""name="empresa"type="text" id="empresa" value="';if (isset($row['empresa'])) echo $row['empresa'];echo '"';echo ' required ></p>';
echo '<p><label for="nombre">Nombre:</label></p><p><textarea class="" name="nombre" cols="60" rows="10"id="nombre"  required>';if (isset($row['nombre'])) echo $row['nombre'];echo '</textarea></p>';
echo '<p><label for="telefono">Teléfono:</label><input class=""name="telefono"type="text" id="telefono" value="';if (isset($row['telefono'])) echo $row['telefono'];echo '"';echo ' required ></p>';
echo '<p><label for="direccion">Dirección:</label><input class=""name="direccion"type="text" id="direccion" value="';if (isset($row['direccion'])) echo $row['direccion'];echo '"';echo ' required ></p>';
echo '<p><label for="correo">Correo:</label><input class=""name="correo"type="text" id="correo" value="';if (isset($row['correo'])) echo $row['correo'];echo '"';echo ' required ></p>';
echo '<p><label for="pedido">Pedido:</label><input class=""name="pedido"type="text" id="pedido" value="';if (isset($row['pedido'])) echo $row['pedido'];echo '"';echo ' required ></p>';
echo '<p><label for="observacion">Observación:</label></p><p><textarea class="" name="observacion" cols="60" rows="10"id="observacion"  required>';if (isset($row['observacion'])) echo $row['observacion'];echo '</textarea></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin nuevo*/ 
if ($_POST['submit']=="Modificar"){

$sql = "SELECT `id`, `nit`, `empresa`, `nombre`, `telefono`, `direccion`, `correo`, `pedido`, `observacion` FROM `proveedores` WHERE id ='".$_POST['cod']."' Limit 1"; 
$consulta = $mysqli->query($sql);
 /*echo $sql;*/ 
$row=$consulta->fetch_assoc();

$textoh1 ="Modificar";
$textobtn ="Actualizar";
echo '<form id="form1" name="form1" method="post" action="proveedores.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="nit">NIT:</label><input class=""name="nit"type="text" id="nit" value="';if (isset($row['nit'])) echo $row['nit'];echo '"';echo ' required ></p>';
echo '<p><label for="empresa">Empresa:</label><input class=""name="empresa"type="text" id="empresa" value="';if (isset($row['empresa'])) echo $row['empresa'];echo '"';echo ' required ></p>';
echo '<p><label for="nombre">Nombre:</label></p><p><textarea class="" name="nombre" cols="60" rows="10"id="nombre"  required>';if (isset($row['nombre'])) echo $row['nombre'];echo '</textarea></p>';
echo '<p><label for="telefono">Teléfono:</label><input class=""name="telefono"type="text" id="telefono" value="';if (isset($row['telefono'])) echo $row['telefono'];echo '"';echo ' required ></p>';
echo '<p><label for="direccion">Dirección:</label><input class=""name="direccion"type="text" id="direccion" value="';if (isset($row['direccion'])) echo $row['direccion'];echo '"';echo ' required ></p>';
echo '<p><label for="correo">Correo:</label><input class=""name="correo"type="text" id="correo" value="';if (isset($row['correo'])) echo $row['correo'];echo '"';echo ' required ></p>';
echo '<p><label for="pedido">Pedido:</label><input class=""name="pedido"type="text" id="pedido" value="';if (isset($row['pedido'])) echo $row['pedido'];echo '"';echo ' required ></p>';
echo '<p><label for="observacion">Observación:</label></p><p><textarea class="" name="observacion" cols="60" rows="10"id="observacion"  required>';if (isset($row['observacion'])) echo $row['observacion'];echo '</textarea></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin modificar*/ 
if ($_POST['submit']=="Actualizar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$cod = $_POST['cod'];
 /*Instrucción SQL que permite insertar en la BD */ 
$sql = "UPDATE proveedores SET id='".$_POST['id']."', nit='".$_POST['nit']."', empresa='".$_POST['empresa']."', nombre='".$_POST['nombre']."', telefono='".$_POST['telefono']."', direccion='".$_POST['direccion']."', correo='".$_POST['correo']."', pedido='".$_POST['pedido']."', observacion='".$_POST['observacion']."'WHERE  id = '".$cod."';";
/* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/
echo 'Modificación exitosa';
echo '<meta http-equiv="refresh" content="1; url=proveedores.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2; url=proveedores.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
<center>
<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultados_proveedores" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultados_proveedores',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultados_proveedores',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultados_proveedores',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>
<span id="txtsugerencias">
<?php 
buscar_proveedores();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById('menu_proveedores').className ='active '+document.getElementById('menu_proveedores').className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ("plantilla.php");
 ?>
