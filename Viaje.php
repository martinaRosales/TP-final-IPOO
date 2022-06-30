<?php
class Viaje{
    private $idviaje; //clave primaria
    private $vdestino;
    private $vcantmaxpasajeros;
    private $objEmpresa; //clave foránea, referencia a la clase Empresa
    private $objResponsable; //clave foránea, referencia 
    private $vimporte;
    private $tipoAsiento; //primera clase o turista, cama o semicama
    private $idayvuelta; //si, no
    private $arrayObjPasajeros;
    private $msjBaseDatos;

    //se implementa el método constructor de la clase Viaje
    public function __construct()
    {
        $this -> idviaje = 0;
        $this -> vdestino = "";
        $this -> vcantmaxpasajeros = 0;
        $this -> objEmpresa = null;
        $this -> objResponsable = null;
        $this -> vimporte = 0;
        $this -> tipoAsiento = "";
        $this -> idayvuelta = "";
        $this -> arrayObjPasajeros = array();
        $this -> msjBaseDatos = '';
    }

    public function cargar ( $vdestino, $maxPasajeros, $empresa, $responsable, $vimporte, $tipoAsiento, $idayvuelta){ //$idviaje, $pasajeros 
        //$this -> setIdviaje ($idviaje); 
        $this -> setVdestino ($vdestino);
        $this -> setVcantmaxpasajeros ($maxPasajeros);
        $this -> setObjEmpresa ($empresa);
        $this -> setObjResponsable ($responsable);
        $this -> setVimporte ($vimporte);
        $this -> setTipoAsiento ($tipoAsiento);
        $this -> setIdayvuelta ($idayvuelta);
       // $this -> setArrayObjPasajeros ($pasajeros);
    }

    //se implementan los métodos de acceso

    public function getIdviaje()
    {
        return $this->idviaje;
    }

    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;

        return $this;
    }

    public function getVdestino()
    {
        return $this->vdestino;
    }
 
    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;

        return $this;
    }

    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }
 
    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;

        return $this;
    }

    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }

    public function setObjEmpresa($empresa)
    {
        $this->objEmpresa = $empresa;

        return $this;
    }

    public function getObjResponsable()
    {
        return $this->objResponsable;
    }

    public function setObjResponsable($responsable)
    {
        $this->objResponsable = $responsable;

        return $this;
    }

    public function getVimporte()
    {
        return $this->vimporte;
    }

    public function setVimporte($vimporte)
    {
        $this->vimporte = $vimporte;

        return $this;
    }

    public function getTipoAsiento()
    {
        return $this->tipoAsiento;
    }

    public function setTipoAsiento($tipoAsiento)
    {
        $this->tipoAsiento = $tipoAsiento;

        return $this;
    }

    public function getIdayvuelta()
    {
        return $this->idayvuelta;
    }
 
    public function setIdayvuelta($idayvuelta)
    {
        $this->idayvuelta = $idayvuelta;

        return $this;
    }

    public function getArrayObjPasajeros()
    {

        return $this->arrayObjPasajeros;
    }
 
    public function setArrayObjPasajeros($arrayObjPasajeros)
    {
        $this->arrayObjPasajeros = $arrayObjPasajeros;

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
    //NECESITO VER COMO RECUPERAR EL ID
    public function __toString()
    {
        $responsable = $this->getObjResponsable();
        $infoResponsable = $responsable->__toString();
        $infoViaje= "**** VIAJE  {$this->getIdviaje()} ****\n 
        Destino: {$this-> getVdestino()} \nCantidad máxima de pasajeros: {$this -> getVcantmaxpasajeros()}
        \nImporte: $  {$this -> getVimporte()} \nTipo asiento: {$this-> getTipoAsiento()}
        \n Ida y vuelta: {$this->getIdayvuelta()} \nResponsable del viaje:\n $infoResponsable";
        /** \n** INFO PASAJEROS ** {$this->infoPasajero()}" */
        return $infoViaje;
    }

    //método para extraer la información de los pasajeros
    //repetitiva que concatena la información de los pasajeros
    public function infoPasajero (){
        $listaPasajeros = " ";
        $pasajeros = $this -> getArrayObjPasajeros ();
        for ($i = 0; $i < count ($pasajeros); $i++){
           $listaPasajeros = $listaPasajeros. "\n".$pasajeros[$i]->__toString();
        }
        return $listaPasajeros;
    }

    public function arregloPasajeros (){
        $pasajero = new Pasajero();
        $condicion = "";
        $arrayPasajeros = $pasajero -> listar($condicion);
        $this -> setArrayObjPasajeros($arrayPasajeros);
        return $arrayPasajeros;
    }

    /**
	 * Recupera los datos de un viaje por id
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($idviaje){ 
		$base=new BaseDatos(); 
        $resp = false; 
		$consultaViaje="Select * from viaje where idviaje=".$idviaje;
		if($base->Iniciar()){ 
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){					 
				    $this->setIdviaje($idviaje);
					$this->setVdestino($row2["vdestino"]);
					$this->setVcantmaxpasajeros($row2["vcantmaxpasajeros"]);
					$idempresa=($row2["idempresa"]);
                    $numEmpleado=($row2["rnumeroempleado"]);
                    $this->setVimporte($row2["vimporte"]);
                    $this->setTipoAsiento($row2["tipoAsiento"]);
                    $this->setIdayvuelta($row2["idayvuelta"]);
                    $empresa = new Empresa();
                    $this->setObjEmpresa($empresa->Buscar($idempresa));
                    $responsable = new ResponsableV();
                    $this->setObjResponsable($responsable->Buscar($numEmpleado));
                    $resp= true;
				}				
			//$this->setRnumeroempleado
            //$this->setIdempresa
		 	}	else {
		 			$this->setMsjBaseDatos($base->getError());
		 		
			}
		 }	else {
		 		$this->setMsjBaseDatos($base->getError());
		 	
		 }		
		 return $resp;
	}	
    
	public function listar($condicion){
	    $arregloViajes = null;
		$base=new BaseDatos(); 
		$consultaViaje="Select * from viaje ";
		if ($condicion!=""){
		    $consultaViaje=$consultaViaje.' where '.$condicion;
		}
		$consultaViaje=$consultaViaje." order by vdestino ";
		//echo $consultaViaje;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){				
				$arregloViajes= array();
				while($row2=$base->Registro()){
					
					$idviaje=$row2['idviaje'];
					$vdestino=$row2['vdestino'];
					$vcantmaxpasajeros=$row2['vcantmaxpasajeros'];
					$idempresa=$row2['idempresa'];
                    $rnumeroempleado=$row2['rnumeroempleado'];
                    $vimporte=$row2['vimporte'];
                    $tipoAsiento=$row2['tipoAsiento'];
                    $idayvuelta=$row2['idayvuelta'];
                    $objEmpresa = new Empresa();
                    if ($objEmpresa->Buscar($idempresa)){
						$this->setObjEmpresa($objEmpresa);
					} else {
						$this->setObjEmpresa(null);
					}
                    $objResponsable = new ResponsableV();
                    if ($objResponsable->Buscar($rnumeroempleado)){
						$this->setObjResponsable($objResponsable);
					} else {
						$this->setObjResponsable(null);
                    }
					$viaje=new Viaje();
					$viaje->cargar($vdestino,$vcantmaxpasajeros,$objEmpresa,$objResponsable,$vimporte,$tipoAsiento,$idayvuelta);
                    $this->setIdviaje($idviaje);
					array_push($arregloViajes,$viaje);
	
				}
				
			
		 	}	else {
		 			$this->setMsjBaseDatos($base->getError());
		 		
			}
		 }	else {
		 		$this->setMsjBaseDatos($base->getError());
		 	
		 }	
		 return $arregloViajes;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
        $empresa = $this->getObjEmpresa();
        $idempresa = $empresa->getIdempresa();
        $responsable = $this->getObjResponsable();
        $numEmpleado = $responsable->getRnumeroempleado();
		$consultaInsertar="INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
				VALUES ('{$this->getVdestino()}',{$this->getVcantmaxpasajeros()},$idempresa,$numEmpleado,{$this ->getVimporte()},'{$this -> getTipoAsiento()}','{$this -> getIdayvuelta()}')";
		
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
        $empresa = $this->getObjEmpresa();
        $idempresa = $empresa->getIdempresa();
        $responsable = $this->getObjResponsable();
        $numEmpleado = $responsable->getRnumeroempleado();
		$consultaModifica="UPDATE viaje SET vdestino='{$this->getVdestino()}',vcantmaxpasajeros={$this->getVcantmaxpasajeros()} ,idempresa=$idempresa,rnumeroempleado=$numEmpleado,vimporte={$this->getVimporte()} ,tipoAsiento='{$this->getTipoAsiento()}',idayvuelta='{$this->getIdayvuelta()}' WHERE idviaje={$this->getIdviaje()}";
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
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdviaje();
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