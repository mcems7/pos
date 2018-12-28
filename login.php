<?php
ob_start();
$login= true;
@session_start();
if (isset($_POST['usuario'])){
    require("conexion.php");
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $clave = $mysqli->real_escape_string($_POST['clave']);
   $sql = "SELECT * FROM `usuarios` WHERE `password` = '$clave' and `usuario` = '$usuario'";
   $consulta = $mysqli->query($sql);
   if ($row=$consulta->fetch_assoc()){
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['perfil'] = $row['perfil'];
        $_SESSION['foto'] = $row['foto'];
        $_SESSION['estado'] = $row['estado'];
        $_SESSION['ultimo_login'] = $row['ultimo_login'];
       header("Location: index.php");
   }
}
?>
<center>
    <h1>Iniciar Sesión</h1>
<form method="post">
    <label for="usuario">Usuario</label>
    <input type="text" name="usuario">
    <br>
    <label for="clave">Clave</label>
    <input type="password" name="clave">
    <br>
    <input type="submit" name="Iniciar">
</form>
</center>
<?php $contenido = ob_get_contents();
ob_clean();
include ("plantilla.php");
?>