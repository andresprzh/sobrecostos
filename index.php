<?php

require "cors.php";
if (isset($_GET['ruta'])) {
    switch ($_GET['ruta']) {

        
        /* ============================================================================================================================
                                                MUESTRA PUNTOS DE VENTA
        ============================================================================================================================*/
        case 'puntosv':

        if ($_SERVER['REQUEST_METHOD']==='GET') {
            $modelo=new ModeloSobrante();
            $busqueda=$modelo->mdlMostrarUbicaciones();
            $resultado=$busqueda->fetchAll();
            print json_encode($resultado);
            return 1;
        }

        break;

        /* ============================================================================================================================
                                                SUBE ARCHIVO DE SOBRECOSTOS
        ============================================================================================================================*/
        case 'sobrecostos':
            if ($_SERVER['REQUEST_METHOD']==='POST') {

                if (isset($_FILES["archivo"]["tmp_name"])) {
                    
                    if (0 != $_FILES['archivo']['error']) {
                        
                        $resultado["estado"]=false;
                        $resultado["contenido"]='¡Error al subir el acrhivo¡';
                        
                    }
                        //abre el archivo si no hay errores
                    else {

                        $tipos_permitidos = array('text/plain','text/x-Algol68');//tipos permitidos de archivos
                        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                        $tipo = finfo_file($fileInfo, $_FILES['archivo']['tmp_name']);//tipo de archivo subido
                        
                        // SI EL ARCHIVO NO ES DE TIPO TEXTO NO LO ABRE
                        // if (false) {
                        if (!in_array($tipo, $tipos_permitidos)) {
                            $resultado["estado"]=false;
                            $resultado["contenido"]='¡Tipo de archivo no valido¡';
                        } else {
                            
                            $archivo = file($_FILES['archivo']['tmp_name']); 
                            
                            $controlador = new ControladorSobrante($archivo);
                            $resultado["num_rows"]=$controlador->ctrGetData();
                            if ( $resultado["num_rows"]) {
                                $resultado["estado"]=$controlador->ctrUploadData();
                                
                                if ($resultado["estado"]) {
                                    $resultado["contenido"]='Datos subidos exitosamente';
                                }else {
                                    $resultado["contenido"]='Error al subir los datos';
                                }
                            }
                            
                            
                        }

                        finfo_close($fileInfo);

                    }

                }
                print json_encode($resultado);
            }
            break;
        /* ============================================================================================================================
                                                SUBE ARCHIVO DE COPI
        ============================================================================================================================*/
        case "copiupload":
            if ($_SERVER['REQUEST_METHOD']==='POST') {
                $resultado["estado"]=true;
                if (isset($_FILES["archivo"]["tmp_name"])) {
                    
                    if (0 != $_FILES['archivo']['error']) {
                        
                        $resultado["estado"]=false;
                        $resultado["contenido"]='¡Error al subir el acrhivo¡';
                        
                    }
                    //abre el archivo si no hay errores
                    else {
                        
                        $tipos_permitidos = array('text/plain','text/x-Algol68');//tipos permitidos de archivos
                        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                        $tipo = finfo_file($fileInfo, $_FILES['archivo']['tmp_name']);//tipo de archivo subido
                        
                        // SI EL ARCHIVO NO ES DE TIPO TEXTO NO LO ABRE
                        if (!in_array($tipo, $tipos_permitidos)) {
                            $resultado["estado"]=false;
                            $resultado["contenido"]='¡Tipo de archivo no valido¡';
                        } else {
                            
                            $archivo = file($_FILES['archivo']['tmp_name']); 
                            
                            $controlador = new ControladorCopi($archivo);
                            $resultado["contenido"]=$controlador->ctrGetData();

                            if (!$resultado["contenido"]) {
                                $resultado["estado"]=false;
                                $resultado["contenido"]="Error al subir documento";
                            }
                            // $resultado=$controlador->ctrUploadData();
                            
                        }

                        finfo_close($fileInfo);

                    }
                    
                    print json_encode($resultado);
                }
                
            }
            break;
        default:
            $controlador = new ControladorSobrante();
            $resultado=$controlador->ctrGetSedes();
            print json_encode($resultado);
            return 0;
            print json_encode("Restfull api sobrecosto");
                
            return 1;

            break;
    }   
}else{
    print json_encode("Restfull api sobrecosto");
                
    return 1; 
}