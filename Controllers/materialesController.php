<?php namespace Controllers;
use Models\Material as Material;
use Models\Proveedor as Proveedor;
use Models\CostosTemp as CostosTemp;
use Models\UnidadesMedida as UnidadesMedida;
use Models\Operaciones as Operaciones;

class materialesController{
	private $material;
	private $costostemp;
	private $unidadesmedida;
	private $operaciones;
	private $lastId;

	public function __construct(){
		$this->material = new Material();
		$this->proveedor = new Proveedor();
		$this->costostemp = new CostosTemp();
		$this->unidadesmedida = new UnidadesMedida();
		$this->operaciones = new Operaciones();

	}

	public function index(){
		$datos = $this->material->listar2();			
		$datos2 = $this->unidadesmedida->listar();
		return array('materiales'=>$datos, 'unidadesmedida'=>$datos2);

	}
	
	public function indexcostos(){
		$datos = $this->material->listar2();		
		return array('materiales'=> $datos );

}	

	public function agregar(){
		if ($_POST) {
			$this->material->set("descripcion", $_POST['descripcion']);
			$this->material->set("cod_unidad", $_POST['cod_unidad']);
			$this->material->set("precio_unitario", $_POST['precio_unitario']);
			if ($_POST['stock'] == "" || $_POST['stock'] == "0")
				$_POST['stock'] = "null";
			$this->material->set("stock", $_POST['stock']);

			$this->material->set("cod_proveedor", $_POST['cod_proveedor']);				
			$lastId = $this->material->add();
			//print $lastId;
			if($_POST["stock"]!="" || $_POST["stock"]!="0"){
				$this->operaciones->set("cod_material", $lastId);
				$this->operaciones->set("q", $_POST['stock']);
				$this->operaciones->set("cod_tipo_operacion", 1);
				$this->operaciones->add();
			}		

			header("Location: " . URL . "materiales");
		}
	}
	public function reabastecer(){
		$datos = $this->material->listar3();
		return array('materiales'=>$datos);
		if ($_POST) {
			$this->operaciones->set("cod_material", $id);
			$this->operaciones->set("q" , $_POST['stock']);
			$this->operaciones->set("cod_tipo_operacion", 1);
			$this->material->set("stock", $_POST['stock']);
			$this->lastId = $this->operaciones->add();


			
		}		

			//header("Location: " . URL . "materiales");
	}
	public function reabastecerAdd($id=""){
		$this->material->set("cod_material", $id);	
		$datos = $this->material->listar3();
		$datos2 = $this->material->view2();		
		//$row = mysqli_fetch_assoc($datos2);
		$stock = $datos2['stock']; //Stock antes de agregar
		echo $stock;
		if ($_POST) {
			$this->operaciones->set("cod_material", $id);
			$this->operaciones->set("q" , $_GET['stock']);
			$this->operaciones->set("cod_tipo_operacion", 1);
			$this->material->set("cod_material", $id);
			$this->material->set("stock", $stock + $_GET['stock']); //Se le suma el stock actual + el stock nuevo recibido por el formulario (POST)
			$this->lastId = $this->operaciones->add();
			$this->material->updateStock();
			header("Location: " . URL . "materiales/reabastecerComprobante/" . $this->lastId);
			
		}
		return $datos2;
	}

	public function reabastecerComprobante($id=""){ //Aca la wea que se imprime

		if ($id =="")
			$this->operaciones->set("cod_operacion", $this->lastId);
		$this->operaciones->set("cod_operacion", $id);

		$datos = $this->operaciones->view2();
		return $datos;
		
		
	}

	public function historial($id=""){
		if($id != ""){
			$this->operaciones->set("cod_material", $id);
			$datos = $this->operaciones->buscar();
			//$datos = mysqli_fetch_assoc($datos);
			if($_POST){
				$this->operaciones->set("desde", $_POST['fecha1']);
				$this->operaciones->set("hasta", $_POST['fecha2']);			
				$datos = $this->operaciones->buscarFecha();
				return $datos;
			}
			return $datos;
		} else{
			$datos = $this->operaciones->listar2();
			if($_POST){
				$this->operaciones->set("desde", $_POST['fecha1']);
				$this->operaciones->set("hasta", $_POST['fecha2']);			
				$datos = $this->operaciones->buscarFecha();
				return $datos;
			}
			return $datos;
		}		
	}

	public function materialesFrecuentes(){
		$datos = $this->operaciones->materialesFrecuentes();
		return $datos;

	}

	public function materialesGastos(){
		$datos = $this->operaciones->materialesGastos();
		return $datos;

	}

	public function eliminar($id){
		$this->material->set("cod_material", $id);
		$this->material->delete();
		$this->operaciones->set("cod_material", $id);
		$this->operaciones->deleteWhereMat(); //Al eliminar un material tambien todo su historial de operaciones. (Eliminar != Dejar Inactivo)
		header("Location: " . URL . "materiales");
	}

	public function editar($id){
		if(!$_POST){
			$this->material->set("cod_material", $id);
			$datos = $this->material->view2();
			return $datos;
		}else{				
			$this->material->set("cod_material", $_POST['cod_material']);
			$this->material->set("descripcion", $_POST['descripcion']);
			$this->material->set("cod_unidad", $_POST['cod_unidad']);				
			$this->material->set("precio_unitario", $_POST['precio_unitario']);			
			$this->material->set("cod_proveedor", $_POST['cod_proveedor']);				
			$this->material->edit();			
			header("Location: " . URL . "materiales");
		}
	}
	public function buscar(){
		if(is_numeric($_GET['buscar'])){
			$this->material->set("cod_material", $_GET['buscar']);
		}else{	
			$this->material->set("descripcion", $_GET['buscar']);
		}			

		$datos = $this->material->buscar2();
			//print_r($datos);		
		return $datos;
		header("Location: " . URL . "material");

	}

	public function comboBox(){
		$datos = $this->proveedor->comboBox();
		return $datos;
	}

	public function addMaterial($id){
		if($_POST){			
			$this->costostemp->set("cod_producto", $_SESSION['lastIdProducto']);
			$this->costostemp->set("cod_material", $id);
			$this->costostemp->set("cantidad", $_POST['cantidad']);
			$this->costostemp->add();
			//header("Location: " . URL . "materiales");	
		}		
		
	}

}



?>
