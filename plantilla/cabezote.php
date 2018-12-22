<header class="main-header">
 	
	<!--=====================================
	LOGOTIPO
	======================================-->
	<a href="inicio" class="logo">
		
		<!-- logo mini -->
		<span class="logo-mini">
			
			<!--img src="vistas/img/plantilla/icono-blanco.png" class="img-responsive" style="padding:10px"-->
			<span>MB</span>
		</span>

		<!-- logo normal -->

		<span class="logo-lg">
			<span>MerkaBueno</span>
			<!--img src="vistas/img/plantilla/logo-blanco-lineal.png" class="img-responsive" style="padding:10px 0px"-->

		</span>

	</a>

	<!--=====================================
	BARRA DE NAVEGACIÓN
	======================================-->
	<nav class="navbar navbar-static-top" role="navigation">
		
		<!-- Botón de navegación -->

	 	<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        	
        	<span class="sr-only">Toggle navigation</span>
      	
      	</a>

		<!-- perfil de usuario -->

		<div class="navbar-custom-menu">
				
			<ul class="nav navbar-nav">
				<?php
#c = ob_get_clean();

		$listar_productos_stock = [];//ModeloProductos::listar_productos_stock();
#ar_dump($listar_productos_stock);
#ie();
				?>
				<li class="dropdown">
					<a href="#">
						<i class="fa fa-bell"></i>
						<span>Actualizar</span>
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<i class="fa fa-bell"></i>
					<span>Stock</span><span class="badge"><?php if (isset($listar_productos_stock)) echo count($listar_productos_stock); else echo "0"; ?></span>

				</a>
					<ul class="dropdown-menu">
						<?php foreach($listar_productos_stock as $lista_producto_stock): ?>
						<li class="user-body"><button title="Producto por comprar pronto" class="btn btn-warning"><?php echo $lista_producto_stock['descripcion']; ?>
							<span class="badge"><?php if (isset($lista_producto_stock['stock'])) echo $lista_producto_stock['stock']; else echo "0"; ?></span>

						</button></li>
						<?php endforeach; ?>
					</ul>
				</li>
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<i class="fa fa-bell"></i>
					<span>Alertas</span><span class="badge"><?php if (isset($listar_productos_por_vencer)) echo count($listar_productos_por_vencer); else echo "0"; ?></span>

				</a>
					<ul class="dropdown-menu">
						<?php foreach($listar_productos_por_vencer as $lista_producto): ?>
						<li class="user-body"><button <?php
						if ($lista_producto['tipo']=="vencidos") echo 'title="Producto Vencido" class="btn btn-danger "';
						if ($lista_producto['tipo']=="vencer_pronto") echo 'title="Producto por vencer pronto" class="btn btn-warning "';
						?>><?php echo $lista_producto['descripcion']; ?></button></li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li class="dropdown user user-menu">
					
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<?php
					if($_SESSION["foto"] != ""){

						echo '<img src="'.$_SESSION["foto"].'" class="user-image">';

					}else{


						echo '<img src="vistas/img/usuarios/default/anonymous.png" class="user-image">';
						

					}


					?>
						
						<span class="hidden-xs"><?php  echo $_SESSION["nombre"]; ?></span>

					</a>

					<!-- Dropdown-toggle -->

					<ul class="dropdown-menu">
						
						<li class="user-body">
							
							<div class="pull-right">
								
								<a href="salir" class="btn btn-default btn-flat">Salir</a>

							</div>

						</li>

					</ul>

				</li>

			</ul>

		</div>

	</nav>

 </header>

