<?php
class Pasajero {
    private $pdocumento; //clave primaria
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $objViaje;
    private $msjBaseDatos;

    //se implementa el método constructor de la clase Pasajero

    public function __construct()
    {
        $this -> pdocumento = "";
        $this -> pnombre = "";
        $this -> papellido = "";
        $this -> ptelefono = "";
        //$this -> objViaje = null;
    }

    //se implementa la función cargar(), que ingresa datos en los atributos, para ser cargados en la bbdd.

    public function cargar($dni, $nombre, $apellido, $telefono, $objViaje)
    {
        $this -> setPdocumento ($dni);
        $this -> setPnombre ($nombre);
        $this -> setPapellido ($apellido);
        $this -> setPtelefono ($telefono);;
        $this -> setObjViaje($objViaje);
    }

    //se implementan los métodos de acceso

    public function getPdocumento()
    {
        return $this->pdocumento;
    }
 
    public function setPdocumento($pdocumento)
    {
        $this->pdocumento = $pdocumento;

        return $this;
    }
 
    public function getPnombre()
    {
        return $this->pnombre;
    }
 
    public function setPnombre($pnombre)
    {
        $this->pnombre = $pnombre;

        return $this;
    }

    public function getPapellido()
    {
        return $this->papellido;
    }

    public function setPapellido($papellido)
    {
        $this->papellido = $papellido;

        return $this;
    }
 
    public function getPtelefono()
    {
        return $this->ptelefono;
    }

    public function setPtelefono($ptelefono)
    {
        $this->ptelefono = $ptelefono;

        return $this;
    }

    public function getObjViaje()
    {
        return $this->objViaje;
    }

    public function setObjViaje($objViaje)
    {
        $this->objViaje = $objViaje;

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
		$objViaje = $this->getObjViaje();
		$idViaje = $objViaje->getIdviaje();
        $infoPasajero= "\n----------------------------------------\nNro documento: {$this -> getPdocumento()}\nNombre: {$this -> getPnombre()}
        \nApellido:  {$this->getPapellido()} \nTeléfono: {$this->getPtelefono()}\nId del viaje: $idViaje\n";
        return $infoPasajero;
    }

    /**
	 * Recupera los datos de un pasajero por dni
	 * @param int $pdocumento
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){ 
		$base=new BaseDatos();  
		$consultaPasajero="Select * from pasajero where pdocumento=".$dni;
		$resp= false; 
		if($base->Iniciar()){ 
			if($base->Ejecutar($consultaPasajero)){
				if($row2=$base->Registro()){					 
				    $this->setPdocumento($dni);
					$this->setPnombre($row2["pnombre"]);
					$this->setPapellido($row2["papellido"]);
					$this->setPtelefono($row2["ptelefono"]);
                    $idviaje=($row2["idviaje"]);
					$resp= true;
					$objViaje = new Viaje();
					$objViaje->Buscar($idviaje);
					$this->setObjViaje($objViaje);
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
	    $arregloPasajeros = null;
		$base=new BaseDatos(); 
		$consultaPasajero="Select * from pasajero ";
		if ($condicion!=""){
		    $consultaPasajero=$consultaPasajero.' where '.$condicion;
		}
		$consultaPasajero.=" order by papellido ";
		//echo $consultaPasajero;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPasajero)){				
				$arregloPasajeros= array();
				while($row2=$base->Registro()){
					
					$NroDoc=$row2['pdocumento'];
					$Nombre=$row2['pnombre'];
					$Apellido=$row2['papellido'];
					$Telefono=$row2['ptelefono'];
                    $IdViaje=$row2['idviaje'];
					$objViaje = new Viaje();
					if ($objViaje->Buscar($IdViaje)){
						$this->setObjViaje($objViaje);
					} else {
						$this->setObjViaje(null);
					}
				
					$pasajero=new Pasajero();
					$pasajero->cargar($NroDoc,$Nombre,$Apellido,$Telefono, $objViaje);
					array_push($arregloPasajeros,$pasajero);
	
				}
				
			
		 	}	else {
		 			$this->setMsjBaseDatos($base->getError());
		 		
			}
		 }	else {
		 		$this->setMsjBaseDatos($base->getError());
		 	
		 }	
		 return $arregloPasajeros;
	}	

	//decidí agregar por parámetro la variable $idviaje para resolver un error que me tiraba el programa si intentaba recuperar el id directamente desde la clase viaje.
    public function insertar(){
		$base=new BaseDatos();
		$resp= false; 
		$objViaje = $this->getObjViaje();
		$idviaje = $objViaje->getIdviaje();
		$consultaInsertar="INSERT INTO pasajero	VALUES ({$this->getPdocumento()},'{$this->getPnombre()}','{$this->getPapellido()}',{$this->getPtelefono()}, $idviaje)";		
		
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
		$viaje = $this->getObjViaje(); 
		$idViaje = $viaje->getIdviaje();
		$consultaModifica="UPDATE pasajero SET papellido='{$this->getPapellido()}',pnombre='{$this->getPnombre()}'
                           ,ptelefono={$this->getPtelefono()}, idviaje=$idViaje WHERE pdocumento={$this->getPdocumento()}";
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
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getPdocumento();
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