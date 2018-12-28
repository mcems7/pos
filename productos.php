<?php 
ob_start();
echo '<center>';
require("conexion.php");
 /*require("funciones.php");*/ 
function buscar_productos($datos="",$reporte=""){
if ($reporte=="xls"){
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; Filename=productos.xls");
}
require("conexion.php");

$sql = "SELECT `productos`.`id`, `productos`.`codigo`, `productos`.`descripcion`, `productos`.`imagen`, `productos`.`stock`, `productos`.`precio_compra`, `productos`.`precio_venta`, `productos`.`ventas`, `productos`.`fecha`, `productos`.`id_categoria`, `categorias`.`categoria` as categoriascategoria FROM `productos`  inner join `categorias` on `productos`.`id_categoria` = `categorias`.`id`  ";
$datosrecibidos = $datos;
$datos = explode(" ",$datosrecibidos);
$datos[]="";
$cont =  0;
$sql .= ' WHERE ';
foreach ($datos as $id => $dato){
$sql .= ' concat(LOWER(`productos`.`id`)," ", LOWER(`productos`.`codigo`)," ", LOWER(`productos`.`descripcion`)," ", LOWER(`productos`.`imagen`)," ", LOWER(`productos`.`stock`)," ", LOWER(`productos`.`precio_compra`)," ", LOWER(`productos`.`precio_venta`)," ", LOWER(`productos`.`ventas`)," ", LOWER(`productos`.`fecha`)," ", LOWER(`categorias`.`categoria`)," ") LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"';
$cont ++;
if (count($datos)>1 and count($datos)<>$cont){
$sql .= " and ";
}
}
$sql .=  " ORDER BY `productos`.`id` desc LIMIT ";
if (isset($_COOKIE['numeroresultados_productos']) and $_COOKIE['numeroresultados_productos']!="") $sql .=$_COOKIE['numeroresultados_productos'];
else $sql .= "10";
/*echo $sql;*/ 

$consulta = $mysqli->query($sql);
 ?>
<div align="center">
<table border="1" id="tbproductos">
<thead>
<tr>
<th>Id</th>
<th>Código</th>
<th>Descripción</th>
<th>Imagen</th>
<th>Stock</th>
<th>Precio Compra</th>
<th>Precio Venta</th>
<th>Ventas</th>
<th>Fecha</th>
<th>Categoría</th>
<?php if ($reporte==""){ ?>
<th><form id="formNuevo" name="formNuevo" method="post" action="productos.php">
<input name="cod" type="hidden" id="cod" value="0">
<input type="submit" name="submit" id="submit" value="Nuevo">
</form>
</th>
<th><form id="formNuevo" name="formNuevo" method="post" action="productos.php?xls">
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
<td><?php echo $row['codigo']?></td>
<td><?php echo $row['descripcion']?></td>
<td><?php echo $row['imagen']?></td>
<td><?php echo $row['stock']?></td>
<td><?php echo $row['precio_compra']?></td>
<td><?php echo $row['precio_venta']?></td>
<td><?php echo $row['ventas']?></td>
<?php $meses = array ('','\\E\\n\\e\\r\\o','\\F\\e\\b\\r\\e\\r\\o','\\M\\a\\r\\z\\o','\\A\\b\\r\\i\\l','\\M\\a\\y\\o','\\J\\u\\n\\i\\o','\\J\\u\\l\\i\\o','\\A\\g\\o\\s\\t\\o','\\S\\e\\p\\t\\i\\e\\m\\b\\r\\e','\\O\\c\\t\\u\\b\\r\\e','\\N\\o\\v\\i\\e\\m\\b\\r\\e','\\D\\i\\c\\i\\e\\m\\b\\r\\e'); ?>

<td><?php echo  date("d \\d\\e ".$meses[date("n",strtotime($row['fecha']))]."  \\d\\e\\l \\a\\ñ\\o Y ",strtotime($row['fecha'])); ?></td>
<td><?php echo $row['categoriascategoria']?></td>
<?php if ($reporte==""){ ?>
<td>
<form id="formModificar" name="formModificar" method="post" action="productos.php">
<input name="cod" type="hidden" id="cod" value="<?php echo $row['id']?>">
<input type="submit" name="submit" id="submit" value="Modificar">
</form>
</td>
<td>
<input type="image" src="img/eliminar.png" onClick="confirmeliminar('productos.php',{'del':'<?php echo $row['id'];?>'},'<?php echo $row['id'];?>');" value="Eliminar">
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
buscar_productos($_POST['datos']);
exit();
}
if (isset($_GET['xls'])){
buscar_productos('','xls');
exit();
}
if (isset($_POST['del'])){
 /*Instrucción SQL que permite eliminar en la BD*/ 
$sql = 'DELETE FROM productos WHERE id="'.$_POST['del'].'"';
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/
if ($eliminar = $mysqli->query($sql)){
 /*Validamos si el registro fue eliminado con éxito*/ 
echo '
Registro eliminado
<meta http-equiv="refresh" content="1; url=productos.php" />
'; 
}else{
?>
Eliminación fallida, por favor compruebe que la usuario no esté en uso
<meta http-equiv="refresh" content="2; url=productos.php" />
<?php 
}
}
 ?>
<center>
<h1>Productos</h1>
</center><?php 
if (isset($_POST['submit'])){
if ($_POST['submit']=="Registrar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$sql = "INSERT INTO productos (`id`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_compra`, `precio_venta`, `ventas`, `fecha`, `id_categoria`) VALUES ('".$_POST['id']."', '".$_POST['codigo']."', '".$_POST['descripcion']."', '".$_POST['imagen']."', '".$_POST['stock']."', '".$_POST['precio_compra']."', '".$_POST['precio_venta']."', '".$_POST['ventas']."', '".$_POST['fecha']."', '".$_POST['id_categoria']."')";
 /*echo $sql;*/
if ($insertar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/ 
echo 'Registro exitoso';
echo '<meta http-equiv="refresh" content="1; url=productos.php" />';
 }else{ 
echo 'Registro fallido';
echo '<meta http-equiv="refresh" content="1; url=productos.php" />';
}
} /*fin Registrar*/ 
if ($_POST['submit']=="Nuevo"){

$textoh1 ="Registrar";
$textobtn ="Registrar";

echo '<form id="form1" name="form1" method="post" action="productos.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="codigo">Código:</label><input class=""name="codigo"type="text" id="codigo" value="';if (isset($row['codigo'])) echo $row['codigo'];echo '"';echo ' required ></p>';
echo '<p><label for="descripcion">Descripción:</label></p><p><textarea class="" name="descripcion" cols="60" rows="10"id="descripcion"  required>';if (isset($row['descripcion'])) echo $row['descripcion'];echo '</textarea></p>';
echo '<p><label for="imagen">Imagen:</label><input class=""name="imagen"type="file" id="imagen" value="';if (isset($row['imagen'])) echo $row['imagen'];echo '"';echo ' required ></p>';
echo '<p><label for="stock">Stock:</label><input class=""name="stock"type="number"  min="0" id="stock" value="';if (isset($row['stock'])) echo $row['stock'];echo '"';echo ' required ></p>';
echo '<p><label for="precio_compra">Precio Compra:</label><input class=""name="precio_compra"type="number"  step="any"  min="0" id="precio_compra" value="';if (isset($row['precio_compra'])) echo $row['precio_compra'];echo '"';echo ' required ></p>';
echo '<p><label for="precio_venta">Precio Venta:</label><input class=""name="precio_venta"type="number"  step="any"  min="0" id="precio_venta" value="';if (isset($row['precio_venta'])) echo $row['precio_venta'];echo '"';echo ' required ></p>';
echo '<p><label for="ventas">Ventas:</label><input class=""name="ventas"type="number"  min="0" id="ventas" value="';if (isset($row['ventas'])) echo $row['ventas'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha">Fecha:</label><input class=""name="fecha"type="date" id="fecha" value="';if (isset($row['fecha'])) echo $row['fecha'];echo '"';echo ' required ></p>';
echo '<p><label for="id_categoria">Categoría:</label>';
$sql10= "SELECT id,categoria FROM categorias;";
echo '<select class="" name="id_categoria" id="id_categoria"  required>';
echo '<option value="">Seleccione una opci&oacute;n</option>';
$consulta10 = $mysqli->query($sql10);
while($row10=$consulta10->fetch_assoc()){
echo '<option value="'.$row10['id'].'"';if (isset($row['id_categoria']) and $row['id_categoria'] == $row10['id']) echo " selected ";echo '>'.$row10['categoria'].'</option>';
}
echo '</select></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin nuevo*/ 
if ($_POST['submit']=="Modificar"){

$sql = "SELECT `id`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_compra`, `precio_venta`, `ventas`, `fecha`, `id_categoria` FROM `productos` WHERE id ='".$_POST['cod']."' Limit 1"; 
$consulta = $mysqli->query($sql);
 /*echo $sql;*/ 
$row=$consulta->fetch_assoc();

$textoh1 ="Modificar";
$textobtn ="Actualizar";
echo '<form id="form1" name="form1" method="post" action="productos.php">
<h1>'.$textoh1.'</h1>';
?>

<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id']))  echo $row['id'] ?>" size="120" required></p>
<?php 
echo '<p><input class=""name="id"type="hidden" id="id" value="';if (isset($row['id'])) echo $row['id'];echo '"';echo '></p>';
echo '<p><label for="codigo">Código:</label><input class=""name="codigo"type="text" id="codigo" value="';if (isset($row['codigo'])) echo $row['codigo'];echo '"';echo ' required ></p>';
echo '<p><label for="descripcion">Descripción:</label></p><p><textarea class="" name="descripcion" cols="60" rows="10"id="descripcion"  required>';if (isset($row['descripcion'])) echo $row['descripcion'];echo '</textarea></p>';
echo '<p><label for="imagen">Imagen:</label><input class=""name="imagen"type="file" id="imagen" value="';if (isset($row['imagen'])) echo $row['imagen'];echo '"';echo ' required ></p>';
echo '<p><label for="stock">Stock:</label><input class=""name="stock"type="number"  min="0" id="stock" value="';if (isset($row['stock'])) echo $row['stock'];echo '"';echo ' required ></p>';
echo '<p><label for="precio_compra">Precio Compra:</label><input class=""name="precio_compra"type="number"  step="any"  min="0" id="precio_compra" value="';if (isset($row['precio_compra'])) echo $row['precio_compra'];echo '"';echo ' required ></p>';
echo '<p><label for="precio_venta">Precio Venta:</label><input class=""name="precio_venta"type="number"  step="any"  min="0" id="precio_venta" value="';if (isset($row['precio_venta'])) echo $row['precio_venta'];echo '"';echo ' required ></p>';
echo '<p><label for="ventas">Ventas:</label><input class=""name="ventas"type="number"  min="0" id="ventas" value="';if (isset($row['ventas'])) echo $row['ventas'];echo '"';echo ' required ></p>';
echo '<p><label for="fecha">Fecha:</label><input class=""name="fecha"type="date" id="fecha" value="';if (isset($row['fecha'])) echo $row['fecha'];echo '"';echo ' required ></p>';
echo '<p><label for="id_categoria">Categoría:</label>';
$sql10= "SELECT id,categoria FROM categorias;";
echo '<select class="" name="id_categoria" id="id_categoria"  required>';
echo '<option value="">Seleccione una opci&oacute;n</option>';
$consulta10 = $mysqli->query($sql10);
while($row10=$consulta10->fetch_assoc()){
echo '<option value="'.$row10['id'].'"';if (isset($row['id_categoria']) and $row['id_categoria'] == $row10['id']) echo " selected ";echo '>'.$row10['categoria'].'</option>';
}
echo '</select></p>';

echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>
</form>';
} /*fin modificar*/ 
if ($_POST['submit']=="Actualizar"){
 /*recibo los campos del formulario proveniente con el método POST*/ 
$cod = $_POST['cod'];
 /*Instrucción SQL que permite insertar en la BD */ 
$sql = "UPDATE productos SET id='".$_POST['id']."', codigo='".$_POST['codigo']."', descripcion='".$_POST['descripcion']."', imagen='".$_POST['imagen']."', stock='".$_POST['stock']."', precio_compra='".$_POST['precio_compra']."', precio_venta='".$_POST['precio_venta']."', ventas='".$_POST['ventas']."', fecha='".$_POST['fecha']."', id_categoria='".$_POST['id_categoria']."'WHERE  id = '".$cod."';";
/* echo $sql;*/ 
 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
 /*Validamos si el registro fue ingresado con éxito*/
echo 'Modificación exitosa';
echo '<meta http-equiv="refresh" content="1; url=productos.php" />';
 }else{ 
echo 'Modificacion fallida';
}
echo '<meta http-equiv="refresh" content="2; url=productos.php" />';
} /*fin Actualizar*/ 
 }else{ 
 ?>
<center>
<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">
<b><label>N° de Resultados:</label></b>
<input type="number" min="0" id="numeroresultados_productos" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultados_productos',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultados_productos',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultados_productos',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">
</center>
<span id="txtsugerencias">
<?php 
buscar_productos();
 ?>
</span>
<?php 
}/*fin else if isset cod*/
echo '</center>';
 ?>
<script>
document.getElementById('menu_productos').className ='active '+document.getElementById('menu_productos').className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ("plantilla.php");
 ?>
