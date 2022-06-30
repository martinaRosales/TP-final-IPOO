<?php

class Empresa {
    private $idempresa; //clave primaria
    private $enombre;
    private $edireccion;
    private $msjBaseDatos;
	private $arrayObjViajes;
    
    //se implementa el constructor de la clase Empresa

    public function __construct()
    {
        $this -> idempresa = "";
        $this -> enombre = "";
        $this -> edireccion = "";
    }

    public function cargar ( $enombre, $edireccion){//$idempresa, 
        //$this -> setIdempresa ($idempresa);
        $this -> setEnombre ($enombre);
        $this -> setEdireccion ($edireccion);
    }

    //se implementan los métodos de acceso

    public function getIdempresa()
    {
        return $this->idempresa;
    }

    public function setIdempresa($idempresa)
    {
        $this->idempresa = $idempresa;

        return $this;
    }

    public function getEnombre()
    {
        return $this->enombre;
    }


    public function setEnombre($enombre)
    {
        $this->enombre = $enombre;

        return $this;
    }

    public function getEdireccion()
    {
        return $this->edireccion;
    }

    public function setEdireccion($edireccion)
    {
        $this->edireccion = $edireccion;

        return $this;
    }

    public function getMsjBaseDatos()
    {
        return $this->msjBaseDatos;
    }

    public function setMsjBaseDatos($msjBaseDatos)
    {
        $this->msjBaseDatos = $msjBaseDatos;

        return $this;
    }

	public function getArrayObjViajes()
	{
		return $this->arrayObjViajes;
	}

	public function setArrayObjViajes($arrayObjViajes)
	{
		$this->arrayObjViajes = $arrayObjViajes;

		return $this;
	}
    //Se implementa el méodo __toString

    public function __toString()
    {
        $idempresa = $this -> getIdempresa();
        $enombre = $this -> getEnombre();
        $edireccion = $this -> getEdireccion();

        $infoEmpresa = "Empresa ". $enombre. "\n". $idempresa. "\nDirección: ". $edireccion. "\nViajes registrados:\n".$this->infoViajes();
        return $infoEmpresa; 
    }

	public function arregloViajes (){
		$objViaje = new Viaje (); 
		$arrayViajes = $objViaje->listar("idempresa='{$this->getIdempresa()}'");
		$this -> setArrayObjViajes($arrayViajes);
		return $arrayViajes;
	}

	public function infoViajes (){
		$arrayViajes = $this->arregloViajes();
		$infoViajes = "\n";
		for ($i=0; $i<count($arrayViajes); $i++){
			$infoViajes=$infoViajes."---------------------------------------------------------------\n".$arrayViajes[$i]->__toString(). "\n";	
		}
		return $infoViajes;
	}
    /**
	 * Recupera los datos de un responsable por número de empleado
	 * @param int $pdocumento
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idempresa){ 
		$base=new BaseDatos();  
		$consultaEmpresa="Select * from empresa where idempresa=".$idempresa;
		$resp= false; 
		if($base->Iniciar()){ 
			if($base->Ejecutar($consultaEmpresa)){
				if($row2=$base->Registro()){					 
				    $this->setIdempresa($idempresa);
					$this->setEnombre($row2["enombre"]);
					$this->setEdireccion($row2["edireccion"]);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setMsjBaseDatos($base->getError());
		 		
			}
		 }	else {
		 		$this->setMsjBaseDatos($base->getError());
		 	
		 }		
		 return $resp;
	}	
    
	public function listar($condicion=""){
	    $arregloEmpresas = null;
		$base=new BaseDatos(); 
		$consultaEmpresa="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresa=$consultaEmpresa.' where '.$condicion;
		}
		$consultaEmpresa.=" order by enombre ";
		//echo $consultaEmpresa;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){				
				$arregloEmpresas= array();
				while($row2=$base->Registro()){
					
					$idempresa=$row2['idempresa'];
					$enombre=$row2['enombre'];
					$edireccion=$row2['edireccion'];
				
					$empresa=new Empresa();
					$empresa->cargar($idempresa,$enombre,$edireccion);
					array_push($arregloEmpresas,$empresa);
	
				}
				
			
		 	}	else {
		 			$this->setMsjBaseDatos($base->getError());
		 		
			}
		 }	else {
		 		$this->setMsjBaseDatos($base->getError());
		 	
		 }	
		 return $arregloEmpresas;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion) VALUES ('{$this->getEnombre()}','{$this->getEdireccion()}')";//idempresa, ".$this->getIdempresa().",'
		
		if($base->Iniciar()){ 

			if($base->Ejecutar($consultaInsertar)){

			    $resp=  true;

			}	else {
					$this->setMsjBaseDatos($base->getError());
					
			}

		} else {
				$this->setMsjBaseDatos($base->getError());
			
		}
		return $resp;
	}
	
	public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE empresa SET enombre='".$this->getEnombre()."',edireccion='".$this->getEdireccion()."' WHERE idempresa=". $this->getIdempresa();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setMsjBaseDatos($base->getError());
				
			}
		}else{
				$this->setMsjBaseDatos($base->getError());
			
		}
		return $resp;
	}

    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdempresa();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setMsjBaseDatos($base->getError());
					
				}
		}else{
				$this->setMsjBaseDatos($base->getError());
			
		}
		return $resp; 
	}

}