<?php namespace Controllers;	
use Models\Productos as Productos;
use Models\Costos as Costos;
use Models\CostosFijos as CostosFijos;
use Models\CostosVariables as CostosVariables;
use Models\CostosTemp as CostosTemp;
use Models\Operaciones as Operaciones;


class productosController{
	private $productos;
	private $costos;
	private $costosfijos;
	private $costosvariables;
	private $costostemp;
	private $operaciones;

	public function __construct(){			
		$this->productos = new Productos();
		$this->costos = new Costos();
		$this->costosfijos = new CostosFijos();
		$this->costosvariables = new CostosVariables();
		$this->costostemp = new CostosTemp();
		$this->operaciones = new Operaciones();
	}

	public function index(){
		$datos = $this->productos->listar();
		$datos2 = $this->costostemp->listar();
		return array('productos'=>$datos, 'costostemp'=>$datos2);		
	}

	public function agregar(){
		
	//Si se presiona Calcular act = 1, si se presiona Registrar act = 2 
		if(isset($_POST['act']) && $_POST['act'] == 1){
			//Paso 1: se crea un nuevo producto con su nombre y se pasa a la vista de costos para calcularlo.
			$this->productos->set("descripcion", $_POST['descripcion']);
			$_SESSION['lastIdProducto'] = $this->productos->add();
			header("Location: " . URL . "costos");
			
		}
		if(isset($_POST['act']) && $_POST['act'] == 2){
			$this->productos->set("cod_producto", $_SESSION['lastIdProducto']);			
			$this->productos->set("precio_unitario", $_POST['precio_unitario']);
			$this->productos->set("stock", $_POST['stock']);
			if($_POST['stock'] != 0 || $_POST['stock'] != '')  {
				$this->operaciones->set("cod_producto", $_SESSION['lastIdProducto']);
				$this->operaciones->set("q", $_POST['stock']);
				$this->operaciones->set("cod_tipo_operacion", 1);
				$this->operaciones->add();
			}
			$this->productos->edit2();
			unset($_SESSION['lastIdProducto']);
			header("Location: " . URL . "productos");

		}
	
		
	//Si no se recibe nada por post, hay dos posibilidades. 1. Que se este agregando un nuevo producto, entonces el form debe esta vacio, o 2. Que se este en el Paso 5, que es donde recibe el precio sugerido del form de costos.

	//Al finalizar el proceso de agregar un producto completamente, la variable $_SESSION['lastIdProducto'], queda destruida. Si no es asi, es porque no finalizo el proceso de agregado, y se debe terminar.
		if(!isset($_SESSION['lastIdProducto'])){
			

		}else{ //Paso 5: Una vez aca, se obtiene el precio sugerido.
			
			$this->productos->set("cod_producto", $_SESSION['lastIdProducto']);
			$datos = $this->productos->view();
			return $datos;
			
		}
		

		
	}

	public function eliminar($id){
		$this->productos->set("cod_producto", $id);
		$this->productos->delete();
		header("Location: " . URL . "productos");
	}

	public function editar($id){
		if(!$_POST){
			$this->productos->set("cod_producto", $id);
			$datos = $this->productos->view();
			return $datos;
		}else{				
			$this->productos->set("cod_producto", $_POST['cod_producto']);
			$this->productos->set("descripcion", $_POST['descripcion']);							
			$this->productos->set("precio_unitario", $_POST['precio_unitario']);							
			$this->productos->edit();			
			header("Location: " . URL . "productos");
		}

	}
	public function buscar(){
		if(is_numeric($_GET['buscar'])){
			$this->productos->set("cod_producto", $_GET['buscar']);
		}else{	
			$this->productos->set("razon_social", $_GET['buscar']);
		}			

		$datos = $this->productos->buscar();			
		return $datos;
		header("Location: " . URL . "productos");

	}

	public function calcular(){
		$this->costosfijos->listarActual();

	}
	
	public function reabastecerAdd($id=""){
		$this->productos->set("cod_producto", $id);		
		$datos = $this->productos->view();		
		//$row = mysqli_fetch_assoc($datos2);
		$stock = $datos['stock'];//Stock antes de agregar
		
		
		if ($_POST) {
			$this->operaciones->set("cod_producto", $id);
			$this->operaciones->set("q" , $_POST['stock']);
			$this->operaciones->set("cod_tipo_operacion", 1);
			$this->productos->set("cod_producto", $id);
			$this->productos->set("stock", $stock + $_POST['stock']); //Se le suma el stock actual + el stock nuevo recibido por el formulario (POST)
			$this->lastId = $this->operaciones->add();
			$this->productos->updateStock();
			header("Location: " . URL . "productos");
			
		}
		return $datos;
	}



}



?>