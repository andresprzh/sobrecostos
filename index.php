<?php

require "cors.php";
if (isset($_GET['ruta'])) {
    switch ($_GET['ruta']) {

        /* ============================================================================================================================
                                                SUBE ARCHIVO DE SOBRECOSTOS
        ============================================================================================================================*/
        case 'sobrecostos':
        
            if (isset($_FILES["archivo"]["tmp_name"])) {
                
                if (0 != $_FILES['archivo']['error']) {
                    
                    $resultado["estado"]=false;
                    $resultado["contenido"]='¡Error al subir el acrhivo¡';
                    
                }
                    //abre el archivo si no hay errores
                else {
                    
                    // finfo_close($fileInfo);
                    // return 0;
                    $tipos_permitidos = array('text/plain','text/x-Algol68');//tipos permitidos de archivos
                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $tipo = finfo_file($fileInfo, $_FILES['archivo']['tmp_name']);//tipo de archivo subido
                    
                        // SI EL ARCHIVO NO ES DE TIPO TEXTO NO LO ABRE
                        
                    if (!in_array($tipo, $tipos_permitidos)) {
                        $resultado["estado"]=false;
                        $resultado["contenido"]='¡Tipo de archivo no valido¡';
                    } else {
                        
                        $archivo = file($_FILES['archivo']['tmp_name']); 
                        
                        //se crea objeto requerir, que busca y manda los items a la base de datos
                        $controlador = new ControladorSobrante($archivo);
                        $resultado=$controlador->ctrGetData();
                        
                    }

                    finfo_close($fileInfo);

                }
                
            
            }
            print json_encode($resultado);
            
            break;
        /* ============================================================================================================================
                                                MUESTRA PUNTOS DE VENTA
        ============================================================================================================================*/
        case 'puntosv':
        $modelo=new ModeloSobrante();
        $busqueda=$modelo->mdlMostrarUbicaciones();
        $resultado=$busqueda->fetchAll();
        print json_encode($resultado);
        
        // return 1;

        break;

        default:
            print json_encode("Restfull api sobrecosto");
                
            return 1;

            break;
    }   
}else{
    print json_encode("Restfull api sobrecosto");
                
    return 1; 
}