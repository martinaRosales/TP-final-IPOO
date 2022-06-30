<?php

//ESTE DECIDI QUE NO SIRVE PARA LA ENTREGA, MUY COMPLEJO
include "BaseDatos.php";
include "Viaje.php";
include "ResponsableV.php";
include "Empresa.php";
include "Pasajero.php";

//RESOLVER EL PROBLEMA AL CARGAR DATOS DE PASAJERO
//RESOLVER EL PROBLEMA "no hay lugar en este viaje" CUANDO HAY CUPOS LIBRES EN agregarPasajeros()
//TESTEAR MODIFICAR DATOS Y ELIMINAR DATOS
//TESTEAR LA FUNCION MOSTRAR DATOS (me quede sin internet y se puso cursed el programa), adjunto evidencia

/* ---------------------------------------------------------------
**** VIAJE ****
Destino: 6
Cantidad máxima de pasajeros: CancunImporte: $Responsable de viajes Buendia, Rogelio
Número de empleado: 9
Número de licencia: 714
Tipo asiento: 100000Ida y vuelta: cama, primera claseResponsable del viaje: Empresa Pecom
18
Dirección: Lago Pellegrini 3000
Viajes registrados:

--------------------------------------------------------------- */
//COMENTAR PROGRAMA Y SUBIR A GITHUB


/* 
$base = new BaseDatos();
if($base->Iniciar()){
    if ($base->Ejecutar("DELETE FROM pasajero")){
        echo "Se borraron los registros de la tabla pasajeros.\n";
    } else{
        echo "No se borró contenido de pasajero.\n";
        echo $base->getError();
    }
    if($base->Ejecutar("DELETE FROM viaje")){
        echo "Se borró contenido de viaje.\n";
    }else{
        echo "No se borró contenido de viaje.\n";
        echo $base->getError();
    }
    if($base->Ejecutar("DELETE FROM empresa")){
        echo "Se borro contenido de empresa.\n";
    }else{
        echo "No se borró contenido empresa.\n";
        echo $base->getError();
    }
    if($base->Ejecutar("DELETE FROM responsable")){
        echo "Se borró contenido responsable.\n";
    }else{
        echo "No se borró contenido responsable.\n";
        echo $base->getError();
    }
}else{
    echo "No se pudo borrar ningún dato anterior.\n";
    echo $base->getError();
}
 */
/**$baseDatos = new BaseDatos();
$msj = $baseDatos -> Iniciar();
if ($msj) {
    echo "Funciona";
} else {
    echo "No funca";
}
echo $baseDatos -> getCONEXION();
 */


//generar una funcion para ver los datos de la bbdd.

function cargarDatos(){
    do {
        echo "¿Qué datos desea cargar?\n
        1) Datos de una empresa \n
        2) Datos de un empleado \n
        3) Datos de un viaje \n
        4) Datos de uno o más pasajeros \n
        5) Volver al menú principal. \n";
        $opcion = trim(fgets(STDIN));
        if ($opcion <= 5 && $opcion >= 1){
            switch ($opcion){
                case 1: // Datos de una empresa
                    cargarEmpresa();
                break;
                case 2://Datos de un empleado
                    cargarResponsableV();
                break;
                case 3: //Datos de un viaje
                    cargarViaje();
                break;
                case 4: //Datos de uno o más pasajeros
                    agregarPasajeros();
                break;
                case 5:
                break;
            }
        } else {
            echo "El número ingresado no está dentro del rango de opciones, por favor vuelva a intentarlo.\n";
        }
        
    }while ($opcion <> 5);

}


/**
 * función cargarEmpresa
 * carga los datos de un obj empresa en la bbdd.
 */
function cargarEmpresa (){
    echo "Ingrese el nombre de la empresa.\n";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la dirección de la empresa.\n";
    $direccion = trim(fgets(STDIN));
    $objEmpresa = new Empresa();
    $objEmpresa->cargar ($nombre, $direccion);
    $resp = $objEmpresa->insertar();
    if ($resp){
        echo "Empresa ingresada correctamente a la base de datos.\n";
    } else {
        echo "La empresa no pudo ser cargada a la base de datos.\n";
        echo $objEmpresa->getMsjBaseDatos()."\n";
    }
}
/**
 * función cargarResponsableV 
 * carga los datos de un objeto responsableV en caso de que no exista ya en la bbdd.
 * @return object
 */
function cargarResponsableV (){
    $objBaseDatos = new BaseDatos();
    $objResponsable = new ResponsableV();
    echo "Ingrese el número de licencia.\n";
    $numLicencia = trim(fgets(STDIN));
    $consulta = "SELECT * FROM resposable WHERE rnumerolicencia=$numLicencia";
    if ($objBaseDatos->Iniciar()){
        if ($objBaseDatos->Ejecutar($consulta)){

             echo "El responsable ingresado ya existe\n"; 

        } else {
            echo "Ingrese el nombre del responsable del viaje.\n";
            $nombre_responsable = trim(fgets(STDIN));
            echo  "Ingrese el apellido del resposable del viaje.\n";
            $apellido_responsable = trim(fgets(STDIN));
            $objResponsable -> cargar ( $numLicencia, $nombre_responsable, $apellido_responsable);
            $resp = $objResponsable -> insertar();
            if ($resp){
                echo "Responsable ingresado correctamente a la base de datos.\n";
            } else {
                echo "El responsable no pudo ser cargado a la base de datos.\n";
                echo $objResponsable -> getMsjBaseDatos()."\n";
            }
        }
    }
}

/**
 * función cargarViaje
 * carga los datos de un obj viaje en caso de que no exista ya en la bbdd.
 * @return object 
 */
function cargarViaje (){
    echo "Ingrese el destino del viaje.\n";
    $destino = trim(fgets(STDIN));
    echo "Ingrese el máximo de pasajeros.\n";
    $maxPasajerosV = trim(fgets(STDIN));
    echo "Ingrese el importe del viaje.\n";
    $importe = trim(fgets(STDIN));
    echo "Ingrese el tipo de asiento (cama o semi cama, primera clase o clase turista).\n";
    $tipoAsiento = trim(fgets(STDIN));
    echo "¿Es ida y vuelta? S/N.\n";
    $idayvuelta = trim(fgets(STDIN));
    echo "Ingrese el id de la empresa a la que pertenece el viaje.\n";
    $idempresa = trim(Fgets(STDIN));
    $empresa = new Empresa();
    if ($empresa->Buscar($idempresa)){
        echo "Ingrese el número de empleado del responsable del viaje. \n";
        $numEmpleado =trim(fgets(STDIN));
        $responsable = new ResponsableV();
        if ($responsable->Buscar($numEmpleado)){
            $objViaje = new Viaje();
            $objViaje->cargar($destino, $maxPasajerosV, $empresa, $responsable, $importe, $tipoAsiento, $idayvuelta);
            $resp = $objViaje->insertar();
            if ($resp){
                echo "El viaje fue ingresado correctamente a la base de datos.\n";
            } else {
                echo "El viaje no pudo ser cargado a la base de datos.\n";
                echo $objViaje->getMsjBaseDatos()."\n";
            }
        } else {
            echo "La empresa ingresada no existe.\n";
        }
    }
}

function agregarPasajeros (){
    $objEmpresa = new Empresa;
    $objViaje = new Viaje;
    $i=1;
    echo "Ingrese el destino al que desea viajar, ingrese VOLVER si desea volver atrás.\n";
    $destinoElegido = trim(fgets(STDIN));
    if ($destinoElegido <> "VOLVER" || $destinoElegido <> "volver"){
        $arrayViajes = $objViaje->listar(" vdestino= '{$destinoElegido}'");
        if ($arrayViajes == null){
            echo "No hay viajes disponibles para este destino.\n";
        } else {
            echo "Los viajes disponibles son los siguientes: \n";
            foreach ($arrayViajes as $key => $viaje) {
                $cantMaxPasajeros = $viaje->getVcantmaxpasajeros();
                $viaje->arregloPasajeros();
                $arrayPasajeros = $viaje->getArrayObjPasajeros();
                if (count ($arrayPasajeros) < $cantMaxPasajeros){
                    $objEmpresa = $viaje->getObjEmpresa(); 
                    $nombreEmpresa = $objEmpresa->getEnombre();
                    $importe = $viaje->getVimporte();
                    $tipoAsiento = $viaje->getTipoAsiento();
                    $idayvuelta = $viaje->getIdayvuelta();
                    echo "---------------------------------------------\n
                    VIAJE  $i 
                    \n Empresa:  $nombreEmpresa
                    \n Importe: $$importe
                    \n Tipo de asiento:  $tipoAsiento
                    \n idayvuelta:  $idayvuelta \n
                    ---------------------------------------------\n";
                    $i = $i+1;
                } else {
                    echo "VIAJE $i \n No hay asientos disponibles en este viaje.\n";
                    $i=$i+1;
                }
            }
            echo "¿En cuál viaje desea registrar pasajeros? Ingrese 0 si no desea ninguno. \n";
            $opcion = trim(fgets(STDIN));
            if ($opcion > 0 && $opcion <= count($arrayViajes)){
                $existe = generarRegistroPasajeros($arrayViajes[$opcion-1]);
                if ($existe){
                    echo "El pasajero ingresado ya existe en este viaje.\n";
                } 
            } elseif ($opcion == 0) {
                echo "VOLVIENDO AL MENU ANTERIOR.\n";
            } else {
                echo "El número ingresado no está dentro del rango de opciones. \n";
            }
        }
    }
}

/**
 * función generarRegistroPasajeros
 * genera objetos pasajeros con la función cargarPasajero(), que los ingresa a la bbdd
 * @param int $maxPasajerosV, $idViaje
 */
function generarRegistroPasajeros ($objViaje){
    $i = 0;
    $maxPasajerosV= $objViaje->getVcantmaxpasajeros();
    do {
        $existe = generarPasajero($objViaje);
            if ($existe){
                echo "\n Por favor, inténtelo de nuevo.\n";
            } else {
                $i = $i+1;
                echo "¿Desea cargar otro pasajero? S/N \n";
                $seguir = trim(fgets(STDIN));
                if ($i > $maxPasajerosV){
                    echo "**********\nYa llegó al límite de pasajeros, el pasajero no entra en este viaje.\n**********\n";
                }
            }
    }while ($seguir == "S" && $i <= $maxPasajerosV);
    return $existe;
}


/**
 * función generarPasajero
 * genera un solo objeto pasajero y lo carga en la bbdd.
 * @return object
 */
function generarPasajero ($objViaje){
    echo $objViaje->__toString();
    die();
    echo "Ingrese el nombre del pasajero.\n";
    $nombre_pasajero = trim(fgets(STDIN));
    echo "Ingrese el apellido del pasajero.\n";
    $apellido_pasajero = trim(fgets(STDIN));
    echo "Ingrese sin puntos ni espacios el numero de documento del pasajero.\n";
    $dni_pasajero = trim(fgets(STDIN));
    echo "Ingrese sin espacios el número de teléfono del pasajero.\n";
    $telefono_pasajero = trim(fgets(STDIN));
    $objPasajero = new Pasajero ();
    $objPasajero->cargar ($dni_pasajero, $nombre_pasajero, $apellido_pasajero, $telefono_pasajero, $objViaje);
    $resp = $objPasajero->insertar();
    if ($resp){
        echo "Pasajero ingresado correctamente a la base de datos.\n";
    } else {
        echo "El pasajero no pudo ser cargado a la base de datos.\n";
        echo $objPasajero->getMsjBaseDatos()."\n";
    }
    return $resp;
}


/**
 * función modificarDatos
 * menu que da opciones para modificar datos, usa funciones para modificar el viaje, responsable y pasajero
 * @param object $objViaje
 */
function modificarDatos (){
    do{
     echo "********* MENU DE CAMBIOS *********\n
     1) Modificar o eliminar una Empresa \n
     2) Modificar o eliminar un Viaje \n
     3) Modificar o eliminar un Responsable del viaje \n
     4) Modificar o eliminar un Pasajero \n
     5) Volver al menú principal. \n";
     $opcion_modificarDatos = trim(fgets(STDIN));
     if ($opcion_modificarDatos <= 5 && $opcion_modificarDatos >= 1){
         switch ($opcion_modificarDatos){
             case 1:
                    echo "Ingrese el id de la empresa que desea modificar o eliminar.\n";
                    $idempresa = trim(fgets(STDIN));
                    modificarEmpresa($idempresa);
             break; 
             case 2: 
                    echo "Ingrese el id del viaje que desea modificar o eliminar.\n";
                    $idviaje = trim(fgets(STDIN));
                    modificarViaje ($idviaje);
             break;
             case 3:
                    echo "Ingrese el numero de empleado del responsable que desea modificar o eliminar.\n";
                    $numEmpleado = trim(fgets(STDIN));
                    modificarResponsable($numEmpleado);
             break;
             case 4: 
                    echo "Ingrese el numero de documento del pasajero a modificar o eliminar.\n";
                    $documento = trim(fgets(STDIN));
                    modificarPasajeros($documento);
             break;
             case 5:
             break;
         } 
     } else {
         echo "Su número no entra en el rango de opciones, vuelva a intentar.\n";
     }
    }while ($opcion_modificarDatos <> 5);
     echo "Datos modificados exitosamente.\n";
 }
 

/**
 * función modificarEmpresa
 * modifica todos los atributos del objeto empresa
 * (menos la clave primaria) o lo borra por completo
 * @param object $idempresa
 */
function modificarEmpresa ($idempresa){
    $objEmpresa = new Empresa ();
    if ($objEmpresa->buscar($idempresa)){
        do {
            echo "**** MODIFICAR EMPRESA ****\n
            1) Modificar el nombre de la empresa.\n
            2) Modificar la dirección de la empresa \n
            3) Borrar datos de la empresa \n
            4) Volver atrás\n";
            $opcion = trim(fgets(STDIN));
            if ($opcion <= 4 && $opcion >= 1){
                switch  ($opcion){ 
                    case 1: //modificar nombre de la empresa.
                        echo "Ingrese el nuevo nombre de la empresa.\n";
                        $new_nombre = trim(fgets(STDIN));
                        $objEmpresa->setEnombre ($new_nombre);
                        $resp = $objEmpresa->modificar($idempresa);
                        if ($resp){
                            echo "los datos fueron actualizados correctamente. \n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objEmpresa->getMsjBaseDatos();
                        }
                    break;
                    case 2: //modificar dirección de la empresa.
                    echo "Ingrese la nueva dirección de la empresa.\n";
                    $new_direccion = trim(fgets(STDIN));
                    $objEmpresa->setEnombre ($new_direccion);
                    $resp = $objEmpresa->modificar($idempresa);
                    if ($resp){
                        echo "los datos fueron actualizados correctamente. \n";
                    } else {
                        echo "No se pudo realizar el cambio.\n";
                        echo $objEmpresa->getMsjBaseDatos();
                    }
                    break;
                    case 3: //eliminar datos de la empresa                 
                    echo "Va a eliminar la siguiente empresa: \n". $objEmpresa->__toString(). "\nADVERTENCIA: SE BORRARAN LOS DATOS DE LOS VIAJES QUE TENGAN REGISTRADA ESTA EMPRESA\n ¿Está seguro de hacerlo? S/N.\n";
                    $eliminar = trim(fgets(STDIN));
                    if ($eliminar == "S" || $eliminar == "s"){
                        if ($objEmpresa -> eliminar($idempresa)){
                            echo "Empresa eliminada correctamente.\n";
                        } else {
                            echo "No se pudieron borrar los datos.\n";
                            echo $objEmpresa -> getMsjBaseDatos();
                        }
                    } else {
                        echo "Se canceló la eliminación de la empresa.\n";
                    }
                    break;
                    case 4: //volver atrás
                    break;
                }
            } else {
                echo "El número ingresado no está dentro del rango de opciones, por favor vuelva a intentar.\n";
            }
        } while ($opcion <> 4);
        echo "Cambios en la empresa realizados exitosamente.\n";
    } else {
        echo "El registro ingresado no existe.\n";
    }
    
}

/**
 * función modificarResponsable
 * modifica todos los atributos del objeto responsable
 * (menos la clave primaria) o lo borra por completo
 * @param int $numEmpleado
 */
function modificarResponsable ($numEmpleado){
    $objResponsable = new ResponsableV ();
    $existe = $objResponsable -> buscar($numEmpleado);
    if ($existe){
        do {
            echo "**** MODIFICAR RESPONSABLE DEL VIAJE ****\n
            1) Modificar el número de licencia.\n
            2) Modificar el nombre del responsable del viaje \n
            3) Modificar el apellido del responsable del viaje \n
            4) Eliminar responsable del viaje.
            5) Volver atrás\n";
            $opcion_modificarResponsable = trim(fgets(STDIN));
            if ($opcion_modificarResponsable <= 4 && $opcion_modificarResponsable >= 1){
                switch  ($opcion_modificarResponsable){ 
                    case 1: //modificar número de licencia.
                        echo "Ingrese el nuevo número de licencia.\n";
                        $new_numLicencia = trim(fgets(STDIN));
                        $objResponsable -> setRnumerolicencia ($new_numLicencia);
                        $resp = $objResponsable -> modificar($numEmpleado);
                        if ($resp){
                            echo "los datos fueron actualizados correctamente. \n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objResponsable -> getMsjBaseDatos();
                        }
                    break;
                    case 2: //modificar nombre del responsable.
                        echo "Ingrese el nuevo nombre.\n";
                        $new_nombreResponsable = trim(fgets(STDIN));
                        $objResponsable -> setRnombre ($new_nombreResponsable);
                        $resp = $objResponsable -> modificar($numEmpleado);
                        if ($resp){
                            echo "los datos fueron actualizados correctamente. \n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objResponsable -> getMsjBaseDatos();
                        }
                    break;
                    case 3: // modificar apellido del responsable. 
                        echo "Ingrese el nuevo apellido.\n";
                        $new_apellidoResponsable = trim(fgets(STDIN));
                        $objResponsable -> setRapellido ($new_apellidoResponsable);
                        $resp = $objResponsable -> modificar($numEmpleado);
                        if ($resp){
                            echo "los datos fueron actualizados correctamente. \n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objResponsable -> getMsjBaseDatos();
                        }
                    break;
                    case 4: //eliminar registro del responsable
                        echo "Va a eliminar al siguiente responsable: \n". $objResponsable->__toString(). "\nADVERTENCIA: SE BORRARAN LOS DATOS DE LOS VIAJES EN LOS QUE ESTÉ REGISTRADO\n ¿Está seguro de hacerlo? S/N.\n";
                        $eliminar = trim(fgets(STDIN));
                        if ($eliminar == "S" || $eliminar == "s"){
                            if ($objResponsable -> eliminar($numEmpleado)){
                                echo "Responsable eliminado correctamente.\n";
                            } else {
                                echo "No se pudieron borrar los datos.\n";
                                echo $objResponsable -> getMsjBaseDatos();
                            }
                        } else {
                            echo "Se canceló la eliminación del responsable.\n";
                        }

                    break;
                    case 5: //volver atrás
                    break;
                }
            } else {
                echo "El número ingresado no está dentro del rango de opciones, por favor vuelva a intentar.\n";
            }
        } while ($opcion_modificarResponsable <> 5);
        echo "Cambios en el responsable del viaje realizados exitosamente.\n";
    } else {
        echo "El registro ingresado no existe.\n";
    }
    
}


/**
 * función modificarViaje
 * permite modificar todos los atributos del objeto viaje (menos la clave primaria)
 * o eliminarlo por completo
 * @param int $idviaje
 */
function modificarViaje ($idviaje){
    $objViaje = new Viaje();
    if ($objViaje->buscar ($idviaje)){
        do{
            echo "**** MODIFICAR VIAJE ****\n
            1) Modificar destino del viaje \n
            2) Modificar el máximo de pasajeros del viaje \n
            3) Modificar empresa registrada \n
            4) Modificar responsable a cargo \n 
            5) Modificar importe del viaje \n
            6) Modificar el tipo de asiento \n
            7) Modificar ida y vuelta \n
            8) Eliminar viaje \n
            9) Volver atrás.\n";
            $opcion_modificarViaje = trim(fgets(STDIN));
            if ($opcion_modificarViaje <= 9 && $opcion_modificarViaje >= 1){
                switch ($opcion_modificarViaje ){
                    case 1: //modificar destino del viaje.
                        echo "Escriba el nuevo destino. \n";
                        $destino_nuevo = trim(fgets(STDIN));
                        $objViaje -> setVdestino ($destino_nuevo);
                        $resp = $objViaje -> modificar($idviaje);
                        if ($resp){
                            echo "Cambio realizado correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objViaje -> getMsjBaseDatos();
                        }
                    break;
                    case 2:
                        //modificar el máximo de pasajeros.
                        echo "Escriba el nuevo máximo de pasajeros. \n";
                        $maximoPasajeros_nuevo = trim(fgets(STDIN));
                        if ($maximoPasajeros_nuevo >= count($objViaje -> getArrayObjPasajeros())){
                            $objViaje -> setVcantmaxpasajeros ($maximoPasajeros_nuevo);
                            $resp = $objViaje -> modificar($idviaje);
                            if ($resp){
                                echo "Cambio realizado correctamente.\n";
                            } else {
                                echo "No se pudo realizar el cambio.\n";
                                echo $objViaje -> getMsjBaseDatos();
                            }
                        } else {
                            echo "El número ingresado es igual al máximo actual o menor a la cantidad de pasajeros registrados.\n";
                        }
    
                    break; 
                    case 3: //Modificar empresa registrada 
                        echo "Ingrese el id de la nueva empresa.\n";
                        $id_nuevo = trim(fgets(STDIN));
                        $objEmpresa = new Empresa();
                        $objEmpresa->Buscar($id_nuevo);
                        $objViaje -> setObjEmpresa ($objEmpresa);
                        $resp = $objViaje -> modificar($idviaje);
                        if ($resp){
                            echo "Cambio realizado correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objViaje -> getMsjBaseDatos();
                        }
                    break;
                    case 4: // Modificar responsable a cargo
                        echo "Ingrese el número de empleado del nuevo responsable.\n";
                        $numEmpleado_nuevo =trim(fgets(STDIN));
                        $objResponsable = new ResponsableV();
                        $objResponsable->Buscar($numEmpleado_nuevo);
                        $objViaje -> setObjResponsable ($objResponsable);
                        $resp = $objViaje -> modificar($idviaje);
                        if ($resp){
                            echo "Cambio realizado correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objViaje -> getMsjBaseDatos();
                        }
                    break;
                    case 5: //Modificar importe del viaje
                        echo "Ingrese el nuevo importe del viaje.\n";
                        $importe_nuevo = trim(fgets(STDIN));
                        $objViaje -> setVimporte ($importe_nuevo);
                        $resp = $objViaje -> modificar($idviaje);
                        if ($resp){
                            echo "Cambio realizado correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objViaje -> getMsjBaseDatos();
                        }
                    break;
                    case 6: //Modificar el tipo de asiento
                        echo "Ingrese las nuevas características del asiento (cama o semi cama, clase turista o primera clase).\n";
                        $tipoAsiento_nuevo = trim(fgets(STDIN));
                        $objViaje -> setTipoAsiento($tipoAsiento_nuevo);
                        $resp = $objViaje -> modificar($idviaje);
                        if ($resp){
                            echo "Cambio realizado correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objViaje -> getMsjBaseDatos();
                        }
                    break;
                    case 7: //Modificar ida y vuelta
                        echo "Ingrese la nueva característica ida y vuelta (S/N).\n";
                        $idayvuelta_nuevo = trim(fgets(STDIN));
                        $objViaje -> setIdayvuelta ($idayvuelta_nuevo);
                        $resp = $objViaje -> modificar($idviaje);
                        if ($resp){
                            echo "Cambio realizado correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objViaje -> getMsjBaseDatos();
                        }
                    break;
                    case 8: 
                        echo "Va a eliminar al siguiente viaje: \n". $objViaje->__toString(). "\nADVERTENCIA: SE BORRARAN LOS DATOS DE LOS PASAJEROS REGISTRADOS EN ESTE VIAJE\n ¿Está seguro de hacerlo? S/N.\n";
                        $eliminar = trim(fgets(STDIN));
                        if ($eliminar == "S" || $eliminar == "s"){
                            if ($objViaje -> eliminar($idviaje)){
                                echo "Viaje eliminado correctamente.\n";
                            } else {
                                echo "No se pudieron borrar los datos.\n";
                                echo $objViaje -> getMsjBaseDatos();
                            }
                        } else {
                            echo "Se canceló la eliminación del viaje.\n";
                        }

                    break;
                    case 9: //volver atrás
                    break;
                }
            } else {
                echo "El número ingresado no está dentro del rango de opciones, por favor, vuelva a intentar.\n";
            }
        } while ($opcion_modificarViaje <> 9);
        echo "Datos del VIAJE modificados correctamente.\n";
    } else {
        echo "El registro ingresado no existe.\n";
    }
} 


/**
 * función modificarPasajeros
 * permite modificar todos los atributos de un objeto pasajero 
 * (menos la clave primaria) o eliminarlo por completo
 * @param int $pdocumento
 */
function modificarPasajeros ($pdocumento){
    $objPasajero = new Pasajero();
    if ($objPasajero->Buscar($pdocumento)){
        do {
            echo "* MODIFICAR PASAJERO *\n
            1) Modificar nombre \n
            2) Modificar apellido \n
            3) Modificar número de teléfono \n
            4) Eliminar pasajero.\n
            5) Volver atrás \n";
            $opcion = trim(fgets(STDIN));
            if ($opcion <= 5 && $opcion >=1){
                switch ($opcion){
                    case 1: //modificar nombre
                        echo "Ingrese el nombre nuevo.\n";
                        $nuevo_nombrePasajero = trim(fgets(STDIN));
                        $objPasajero -> setPnombre ($nuevo_nombrePasajero);
                        $resp = $objPasajero -> modificar($pdocumento);
                        if ($resp){
                            echo "Datos actualizados correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objPasajero -> getMsjBaseDatos();
                        }
                    break;
                    case 2://modificar apellido
                        echo "Ingrese el apellido nuevo.\n";
                        $nuevo_apellidoPasajero = trim(fgets(STDIN));
                        $objPasajero -> setPapellido ( $nuevo_apellidoPasajero);
                        $resp = $objPasajero -> modificar($pdocumento);
                        if ($resp){
                            echo "Datos actualizados correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objPasajero -> getMsjBaseDatos();
                        }
                    break;
                    case 3: //modificar número de teléfono
                        echo "Ingrese el número de teléfono nuevo.\n";
                        $nuevo_telefonoPasajero = trim(fgets(STDIN));
                        $objPasajero -> setPtelefono ($nuevo_telefonoPasajero);
                        $resp = $objPasajero -> modificar($pdocumento);
                        if ($resp){
                            echo "Datos actualizados correctamente.\n";
                        } else {
                            echo "No se pudo realizar el cambio.\n";
                            echo $objPasajero -> getMsjBaseDatos();
                        }
                    break;
                    case 4: //eliminar pasajero.
                        echo "Va a eliminar al siguiente pasajero: \n". $objPasajero -> __toString(). "\n ¿Está seguro de hacerlo? S/N.\n";
                        $eliminar = trim(fgets(STDIN));
                        if ($eliminar == "S" || $eliminar == "s"){
                            if ($objPasajero -> eliminar($pdocumento)){
                                echo "Pasajero eliminado correctamente.\n";
                            } else {
                                echo "No se pudieron borrar los datos.\n";
                                echo $objPasajero -> getMsjBaseDatos();
                            }
                        } else {
                            echo "Se canceló la eliminación del pasajero.\n";
                        }
                    break;
                    case 5://volver atrás.
                    break;
                }
            } else {
                echo "El número ingresado no está dentro del rango de opciones, por favor inténtelo de nuevo. \n";
            }
        
        } while ($opcion <> 5);
    } else {
        echo "El pasajero ingresado no existe en la base de datos.\n";
    }
}

function mostrarDatos (){
    echo "********* MOSTRAR DATOS *********\
    \nIngrese el id de la empresa cuyos datos quiera ver.\n";
    $idempresa = trim(fgets(STDIN));
    $objEmpresa = new Empresa;
    if ($objEmpresa->Buscar($idempresa)){
        echo $objEmpresa->__toString();
    } else {
        echo "La empresa que busca no existe.\n";
    }
}

// PROGRAMA PRINCIPAL
do {
    echo "******* MENU PRINCIPAL *******\n
    1) Cargar datos\n
    2) Modificar datos \n
    3) Ver datos \n
    4) Salir. \n";
    $opcionMenu = trim(fgets(STDIN));
    if ($opcionMenu >= 1 && $opcionMenu <= 4){
        switch ($opcionMenu){
            case 1:
                cargarDatos();
            break;
            case 2:
                modificarDatos();
            break;
            case 3:
                mostrarDatos();
            break;
            case 4:
            break;
        }
    }else {
        echo "El número ingresado no está dentro del rango de opciones, por favor inténtelo de nuevo.\n";
    }
} while ($opcionMenu <> 4);
echo "¡Hasta pronto!";
