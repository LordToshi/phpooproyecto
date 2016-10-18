<div class="box-principal">
	<h3 class="titulo">Listado de Productos<br></h3>	

	<div class="panel panel-success">		
		<div class="panel-heading">
			<h3 class="panel-title">Listado de Productos</h3>
		</div>
		<div class="panel-body">
			<h3 class="panel-title"><label for="inputEmail" class="control-label">Buscar Material</label></h3>
			<form class="navbar-form navbar-left" action="<?php echo URL; ?>materiales/buscar" method="GET">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Buscar" name="buscar" required>
				</div>
				<button type="submit" class="btn btn-default">Buscar</button>
			</form>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-hover ">
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Descripcion</th>
						<th>Precio Unitario</th>
						<th>Stock</th>			     
						<th colspan="2">Accion</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_array($datos['productos']) ) { ?>				
						<tr>			      
							<td><?php echo $row['cod_producto'];?></td>
							<td><?php echo $row['descripcion'];?></td>
							<td><?php echo $row['precio_unitario'];?></td>
							<td><?php echo $row['stock'];?></td>				

							<td><a class="btn btn-primary" onClick="javascript: return proRes('<?php echo URL; ?>','<?php echo $row['cod_producto'];?>');">Reabastecer</a></td>

							<td><a class="btn btn-warning" onClick="javascript: return proEditar('<?php echo URL; ?>','<?php echo $row['cod_producto'];?>');">Editar</a></td>

							<td><a class="btn btn-danger" onClick="javascript: return proEliminar('<?php echo URL; ?>','<?php echo $row['cod_producto'];?>');">Eliminar</a></td>
			
					
						</tr>
						<?php } ?>
					</div>
				</div>

			</div>