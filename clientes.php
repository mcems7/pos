<?php 
ob_start();
echo '<center>';
require("conexion.php");
 /*require("funciones.php");*/ 
function buscar_clientes($datos="",$reporte=""){
if ($reporte=="xls"){
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename=clientes.xls");
}
require("conexion.php");

$sql = "SELECT `clientes`.`id`, `clientes`.`nombre`, `clientes`.`documento`, `clientes`.`email`, `clientes`.`telefono`, `clientes`.`direccion`, `clientes`.`fecha_nacimiento`, `clientes`.`compras`, `clientes`.`ultima_compra`, `clientes`.`fecha` FROM `clientes`   ";
$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]="";
$cont =  0;
$sql .= ' WHERE ';
foreach ($datos as $id => $dato){
$sql .= ' concat(LOWER(`clientes`.`id`)," ", LOWER(`clientes`.`nombre`)," ", LOWER(`clientes`.`documento`)," ", LOWER(`clientes`.`email`)," ", LOWER(`clientes`.`telefono`)," ", LOWER(`clientes`.`direccion`)," ", LOWER(`clientes`.`fecha_nacimiento`)," ", LOWER(`clientes`.`compras`)," ", LOWER(`clientes`.`ultima_compra`)," ", LOWER(`clientes`.`fecha`)," ") LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"';
$cont ++;
if (count($datos)>1 and count($datos)<>$cont){
$sql .= " and ";
}
}
$sql .=  " ORDER BY `clientes`.`id` desc LIMIT ";
if (isset($_COOKIE['numeroresultados_clientes']) and $_COOKIE['numeroresultados_clientes']!="") $sql .=$_COOKIE['numeroresultados_clientes'];
else $sql .= "10";
/*echo $sql;*/ 

$consulta = $mysqli->query($sql);
 ?>
<div align="center">
<table border="1" id="tbclientes">
<thead>
<tr>
<th>Id</th>
<th>Nombre</th>
<th>Documento de Identidad</th>
<th>Email</th>
<th>Teléfono</th>
<th>Dirección</th>
<th>Fecha de Nacimiento</th>
<th>Compras</th>
<th>Ultima Compra</th>
<th>Fecha</th>
<?php if ($reporte==""){ ?>
<th><form id="formNuevo" name="formNuevo" method="post" action="clientes.php">
<input name="cod" type="hidden" id="cod" value="0">
<input type="submit" name="submit" id="submit" value="Nuevo">
</form>
</th>
<th><form id="formNuevo" name="formNuevo" method="post" action="clientes.php?xls">
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
<td><?php echo $row['documento']?></td>
<td><?php echo $row['email']?></td>
<td><?php echo $row['telefono']?></td>
<td><?php echo $row['direccion']?></td>
<?php $meses = array ('','\\E\\n\\e\\r\\o','\\F\\e\\b\\r\\e\\r\\o','\\M\\a\\r\\z\\o','\\A\\b\\r\\i\\l','\\M\\a\\y\\o','\\J\\u\\n\\i\\o','\\J\\u\\l\\i\\o','\\A\\g\\o\\s\\t\\o','\\S\\e\\p\\t\\i\\e\\m\\b\\r\\e','\\O\\c\\t\\u\\b\\r\\e','\\N\\o\\v\\i\\e\\m\\b\\r\\e','\\D\\i\\c\\i\\e\\m\\b\\r\\e'); ?>

<td><?php echo  date("d \\d\\e ".$meses[date("n",strtotime($row['fecha_nacimiento']))]."  \\d\\e\\l \\a\\ñ\\o Y ",strtotime($row['fecha_nacimiento'])); ?></td>
<td><?php echo $row['compras']?></td>
<?php $meses = array ('','\\E\\n\\e\\r\\o','\\F\\e\\b\\r\\e\\r\\o','\\M\\a\\r\\z\\o','\\A\\b\\r\\i\\l','\\M\\a\\y\\o','\\J\\u\\n\\i\\o','\\J\\u\\l\\i\\o','\\A\\g\\o\\s\\t\\o','\\S\\e\\p\\t\\i\\e\\m\\b\\r\\e','\\O\\c\\t\\u\\b\\r\\e','\\N\\o\\v\\i\\e\\m\\b\\r\\e','\\D\\i\\c\\i\\e\\m\\b\\r\\e'); ?>

<td><?php echo  date("d \\d\\e ".$meses[date("n",strtotime($row['ultima_compra']))]."  \\d\\e\\l \\a\\ñ\\o Y ",strtotime($row['ultima_compra'])); ?></td>
<?php $meses = array ('','\\E\\n\\e\\r\\o','\\F\\e\\b\\r\\e\\r\\o','\\M\\a\\r\\z\\o','\\A\\b\\r\\i\\l','\\M\\a\\y\\o','\\J\\u\\n\\i\\o','\\J\\u\\l\\i\\o','\\A\\g\\o\\s\\t\\o','\\S\\e\\p\\t\\i\\e\\m\\b\\r\\e','\\O\\c\\t\\u\\b\\r\\e','\\N\\o\\v\\i\\e\\m\\b\\r\\e','\\D\\i\\c\\i\\e\\m\\b\\r\\e'); ?>

<td><?php echo  date("d \\d\\e ".$meses[date("n",strtotime($row['fecha']))]."  \\d\\e\\l \\a\\ñ\\o Y ",strtotime($row['fecha'])); ?></td>
<?php if ($reporte==""){ ?>
<td>
<form id="formModificar" name="formModificar" method="post" action="clientes.php">
<input name="cod" type="hidden" id="cod" value="<?php echo $row['id']?>">
<input type="submit" name="submit" id="submit" value="Modificar">
</form>
</td>
<td>
<input type="image" src="img/eliminar.png" onClick="confirmeliminar('clientes.php',{'del':'<?php echo $row['id'];?>'},'<?php echo $row['id'];?>');" value="Eliminar">
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
buscar_clientes($_POST['datos']);
exit();
}
if (isset($_GET['xls'])){
buscar_clientes('','xls');
exit();
}
if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
$sql = 'DELETE FROM clientes WHERE id="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con éxito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url=clientes.php" />
'; 
}else{
?>
Eliminación fallida, por favor compruebe que la usuario no esté en uso
<meta http-equiv="refresh" content="2; url=clientes.php" />
<?php 
}
}
 ?>
<center>
<h1>Clientes</h1>
</center><?php 
if (isset($_POST['submit'])){
if ($_POST['submit']=="Registrar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$sql = "INSERT INTO clientes (`id`, `nombre`, `documento`, `email`, `telefono`, `direccion`, `fecha_nacimiento`, `compras`, `ultima_compra`, `fecha`) VALUES ('".$_POST['id']."', '".$_POST['nombre']."', '".$_POST['documento']."', '".$_POST['email']."', '".$_POST['telefono']."', '".$_POST['direccion']."', '".$_POST['fecha_nacimiento']."', '".$_POST['compras']."', '".$_POST['ultima_compra']."', '".$_POST['fecha']."')";
 /*echo $sql;*/
if ($insertar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/ 
echo 'Registro exitoso';
echo '<meta http-equiv="refresh" content="1; url=clientes.php" />';
 }else{ 
echo 'Registro fallido';
echo '<meta http-equiv="refresh" content="1; url=clientes.php" />';
}
} /*fin Registrar*/ 
if ($_POST['submit']=="Nuevo"){

$textoh1 ="Registrar";
$textobtn ="Registrar";

echo '<form id="form1" name="form1" method="post" action="clientes.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="nombre">Nombre:</label><input class=""name="nombre"type="text" id="nombre" value="';if (isset($row['nombre'])) echo $row['nombre'];echo '"';echo ' required ></p>';
echo '<p><label for="documento">Documento de Identidad:</label><input class=""name="documento"type="number"  min="0" id="documento" value="';if (isset($row['documento'])) echo $row['documento'];echo '"';echo ' required ></p>';
echo '<p><label for="email">Email:</label><input class=""name="email"type="email" id="email" value="';if (isset($row['email'])) echo $row['email'];echo '"';echo ' required ></p>';
echo '<p><label for="telefono">Teléfono:</label><input class=""name="telefono"type="tel" id="telefono" value="';if (isset($row['telefono'])) echo $row['telefono'];echo '"';echo ' required ></p>';
echo '<p><label for="direccion">Dirección:</label><input class=""name="direccion"type="text" id="direccion" value="';if (isset($row['direccion'])) echo $row['direccion'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha_nacimiento">Fecha de Nacimiento:</label><input class=""name="fecha_nacimiento"type="date" id="fecha_nacimiento" value="';if (isset($row['fecha_nacimiento'])) echo $row['fecha_nacimiento'];echo '"';echo ' required ></p>';
echo '<p><label for="compras">Compras:</label><input class=""name="compras"type="text" id="compras" value="';if (isset($row['compras'])) echo $row['compras'];echo '"';echo ' required ></p>';
echo '<p><label for="ultima_compra">Ultima Compra:</label><input class=""name="ultima_compra"type="date" id="ultima_compra" value="';if (isset($row['ultima_compra'])) echo $row['ultima_compra'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha">Fecha:</label><input class=""name="fecha"type="date" id="fecha" value="';if (isset($row['fecha'])) echo $row['fecha'];echo '"';echo ' required ></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin nuevo*/ 
if ($_POST['submit']=="Modificar"){

$sql = "SELECT `id`, `nombre`, `documento`, `email`, `telefono`, `direccion`, `fecha_nacimiento`, `compras`, `ultima_compra`, `fecha` FROM `clientes` WHERE id ='".$_POST['cod']."' Limit 1"; 
$consulta = $mysqli->query($sql);
 /*echo $sql;*/ 
$row=$consulta->fetch_assoc();

$textoh1 ="Modificar";
$textobtn ="Actualizar";
echo '<form id="form1" name="form1" method="post" action="clientes.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="nombre">Nombre:</label><input class=""name="nombre"type="text" id="nombre" value="';if (isset($row['nombre'])) echo $row['nombre'];echo '"';echo ' required ></p>';
echo '<p><label for="documento">Documento de Identidad:</label><input class=""name="documento"type="number"  min="0" id="documento" value="';if (isset($row['documento'])) echo $row['documento'];echo '"';echo ' required ></p>';
echo '<p><label for="email">Email:</label><input class=""name="email"type="email" id="email" value="';if (isset($row['email'])) echo $row['email'];echo '"';echo ' required ></p>';
echo '<p><label for="telefono">Teléfono:</label><input class=""name="telefono"type="tel" id="telefono" value="';if (isset($row['telefono'])) echo $row['telefono'];echo '"';echo ' required ></p>';
echo '<p><label for="direccion">Dirección:</label><input class=""name="direccion"type="text" id="direccion" value="';if (isset($row['direccion'])) echo $row['direccion'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha_nacimiento">Fecha de Nacimiento:</label><input class=""name="fecha_nacimiento"type="date" id="fecha_nacimiento" value="';if (isset($row['fecha_nacimiento'])) echo $row['fecha_nacimiento'];echo '"';echo ' required ></p>';
echo '<p><label for="compras">Compras:</label><input class=""name="compras"type="text" id="compras" value="';if (isset($row['compras'])) echo $row['compras'];echo '"';echo ' required ></p>';
echo '<p><label for="ultima_compra">Ultima Compra:</label><input class=""name="ultima_compra"type="date" id="ultima_compra" value="';if (isset($row['ultima_compra'])) echo $row['ultima_compra'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha">Fecha:</label><input class=""name="fecha"type="date" id="fecha" value="';if (isset($row['fecha'])) echo $row['fecha'];echo '"';echo ' required ></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin modificar*/ 
if ($_POST['submit']=="Actualizar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$cod = $_POST['cod'];
 /*Instrucción SQL que permite insertar en la BD */ 
$sql = "UPDATE clientes SET id='".$_POST['id']."', nombre='".$_POST['nombre']."', documento='".$_POST['documento']."', email='".$_POST['email']."', telefono='".$_POST['telefono']."', direccion='".$_POST['direccion']."', fecha_nacimiento='".$_POST['fecha_nacimiento']."', compras='".$_POST['compras']."', ultima_compra='".$_POST['ultima_compra']."', fecha='".$_POST['fecha']."'WHERE  id = '".$cod."';";
/* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/
echo 'Modificación exitosa';
echo '<meta http-equiv="refresh" content="1; url=clientes.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2; url=clientes.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
<center>
<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultados_clientes" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultados_clientes',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultados_clientes',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultados_clientes',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>
<span id="txtsugerencias">
<?php 
buscar_clientes();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById('menu_clientes').className ='active '+document.getElementById('menu_clientes').className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ("plantilla.php");
 ?>
