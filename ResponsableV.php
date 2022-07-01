<?php

use ResponsableV as GlobalResponsableV;

class ResponsableV {
    private $rnumeroempleado; //clave primaria
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $msjBaseDatos;

    //se implementa el método constructor de la clase ResponsableV

    public function __construct()
    {
        $this -> rnumeroempleado = "";
        $this -> rnumerolicencia = "";
        $this -> rnombre = "";
        $this -> rapellido = "";
    }

    public function cargar ( $numLic, $nombre, $apellido){ //$numEmpleado,
       // $this -> setRnumeroempleado ($numEmpleado);
        $this -> setRnumerolicencia  ($numLic);
        $this -> setRnombre($nombre);
        $this -> setRapellido ($apellido);
    }

    //se implementan los métodos de acceso

    public function getRnumeroempleado()
    {
        return $this->rnumeroempleado;
    }

    public function setRnumeroempleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;

        return $this;
    }

    public function getRnumerolicencia()
    {
        return $this->rnumerolicencia;
    }

    public function setRnumerolicencia($rnumerolicencia)
    {
        $this->rnumerolicencia = $rnumerolicencia;

        return $this;
    }
    public function getRnombre()
    {
        return $this->rnombre;
    }

    public function setRnombre($rnombre)
    {
        $this->rnombre = $rnombre;

        return $this;
    }
 
    public function getRapellido()
    {
        return $this->rapellido;
    }

    public function setRapellido($rapellido)
    {
        $this->rapellido = $rapellido;

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

    //se implementa el método __toString

    public function __toString()
    {
        $numEmpleado = $this->getRnumeroempleado();
        $numLic = $this ->getRnumerolicencia();
        $nombre = $this -> getRnombre();
        $apellido = $this -> getRapellido();
        $infoResponsable = "\nNombre:  $apellido \nApellido: $nombre
        \nNúmero de empleado:  $numEmpleado\nNúmero de licencia:  $numLic";
        return $infoResponsable;
    }

    	/**
	 * Recupera los datos de un responsable por número de empleado
	 * @param int $pdocumento
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($numEmpleado){ 
		$base=new BaseDatos();  
		$consultaResponsable="Select * from responsable where rnumeroempleado=".$numEmpleado;
		$resp= false; 
		if($base->Iniciar()){ 
			if($base->Ejecutar($consultaResponsable)){
				if($row2=$base->Registro()){					 
				    $this->setRnumeroempleado($numEmpleado);
					$this->setRnumerolicencia($row2["rnumerolicencia"]);
					$this->setRnombre($row2["rnombre"]);
					$this->setRapellido($row2["rapellido"]);
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
	    $arregloResponsables = null;
		$base=new BaseDatos(); 
		$consultaResponsable="Select * from responsable ";
		if ($condicion!=""){
		    $consultaResponsable=$consultaResponsable.' where '.$condicion;
		}
		$consultaResponsable.=" order by papellido ";
		//echo $consultaResponsable;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){				
				$arregloResponsables= array();
				while($row2=$base->Registro()){
					
					$numEmpleado=$row2['rnumeroempleado'];
					$numLic=$row2['rnumerolicencia'];
					$nombre=$row2['rnombre'];
					$apellido=$row2['rapellido'];
				
					$responsable=new ResponsableV();
					$responsable->cargar($numEmpleado,$numLic,$nombre,$apellido);
					array_push($arregloResponsables,$responsable);
	
				}
				
			
		 	}	else {
		 			$this->setMsjBaseDatos($base->getError());
		 		
			}
		 }	else {
		 		$this->setMsjBaseDatos($base->getError());
		 	
		 }	
		 return $arregloResponsables;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO responsable( rnumerolicencia, rnombre, rapellido) 
				VALUES ('{$this->getRnumerolicencia()}','{$this->getRnombre()}','{$this->getRapellido()}')";
		
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
		$consultaModifica="UPDATE responsable SET rapellido='".$this->getRapellido()."',rnombre='".$this->getRnombre()."'
                           ,rnumerolicencia='".$this->getRnumerolicencia()."' WHERE rnumeroempleado=". $this->getRnumeroempleado();
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
				$consultaBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getRnumeroempleado();
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