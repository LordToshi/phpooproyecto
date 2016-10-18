<div class="box-principal">
	<h3 class="titulo">Editar Productos<hr></h3>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">Editar Producto</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<input name="cod_producto" type="hidden" value="<?php echo $datos['cod_producto'];?>">				   	  
							<label for="inputEmail" class="control-label">Descripcion</label>
							<input class="form-control" name="descripcion" type="text" value="<?php echo $datos['descripcion'];?>" required>
														
								<label for="inputEmail" class="control-label">Precio Unitario</label>
								<input class="form-control" name="precio_unitario" type="text" value="<?php echo $datos['precio_unitario'];?>" required>  
																
								<input type="hidden" name="fecha_stock" value="<?php echo date("Y-m-d") ?>">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-success">Editar</button>
									<button type="reset" class="btn btn-warning">Borrar</button>
								</div>
							</form>
						</div>
						<div class="col-md-1"></div>
					</div>
				</div>
			</div>
		</div>