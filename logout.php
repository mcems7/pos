<?php
@session_start();
unset ($_SESSION['nombre']);
unset ($_SESSION['usuario']);
unset ($_SESSION['perfil']);
unset ($_SESSION['foto']);
unset ($_SESSION['estado']);
unset ($_SESSION['ultimo_login']);
@session_destroy();
header("Location: index.php");
?>