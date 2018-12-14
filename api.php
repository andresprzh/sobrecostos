<?php

require "cors.php";
if (isset($_GET['ruta'])) {
    switch ($_GET['ruta']) {

        
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
                            if ($resultado["contenido"]) {
                                $resultado=$controlador->ctrUploadPlaRemi();
                            }else{
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
        /* ============================================================================================================================
                                                REALIZA EL LOGIN EN LA PAGINA
        ============================================================================================================================*/
        case "login":
            $username=$_POST["username"];
            $password=$_POST["password"];

            $controlador = new ControladorLoginUsuario();
            $resultado=$controlador->ctrIngresoUsuario($username,$password);
            print json_encode($resultado);
            break;
        /* ============================================================================================================================
                                                MODIFICA UN USUARIO
        ============================================================================================================================*/
        case "modusuario":
            if ($_SERVER['REQUEST_METHOD']==='POST') {
                
                $datosusuario=json_decode($_POST["datosusuario"]);
                // $button=$_POST["button"];
               
                $controlador=new ControladorUsuarios();

                //encripta la contraseña
                $datosusuario->password=password_hash($datosusuario->password, PASSWORD_BCRYPT);
                if ( $datosusuario->id_usuario<1) {
                    $resultado=$controlador->ctrCrearUsuario($datosusuario);
                    
                }else {
                    $resultado=$controlador->ctrModificarUsuario($datosusuario);
                }

                print json_encode($resultado);
                
            }
            break;
        /* ============================================================================================================================
                                            BUSCA PERFILES DE USUARIOS
        ============================================================================================================================*/
        case "perfiles":
            // $perfil=$_SESSION["usuario"]["perfil"];
            $perfil=$_REQUEST["perfil"];
            $controlador=new ControladorUsuarios();
            
            $resultado=$controlador->ctrBuscarPerfiles($perfil);
            
            print json_encode($resultado);
            break;
        /* ============================================================================================================================
                                                MUESTRA ITEMS REMISION
        ============================================================================================================================*/
        case 'plaremi':

            if ($_SERVER['REQUEST_METHOD']==='GET') {
                if (!isset($_GET['factura'])) {

                    $modelo=new ModeloCopi();
                    $busqueda=$modelo->buscaritem('plaremi');
                    $resultado=$busqueda->fetchAll();
                    print json_encode($resultado);
                    return 1;

                }else {
                    $controlador=new ControladorCopi();
                    $resultado=$controlador->ctrBsucarSobrantes($_GET["factura"]);
                   
                    print json_encode($resultado);
                    return 1;
                }
            }

            break;
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
                                                MUESTRA PUNTOS DE VENTA
        ============================================================================================================================*/
        case 'salir':
            $res=session_destroy();
            $_SESSION = array();
            print json_encode($res);
            break;
        /* ============================================================================================================================
                                                    MUESTRA SEDES
        ============================================================================================================================*/   
        case 'sedes':

            // $perfil=$_SESSION["usuario"]["perfil"];
            // $perfil=$_REQUEST["perfil"];
            $resultado["estado"]=false;
            $modelo=new ModeloUsuarios();
            
            $busqueda=$modelo->mdlMostrarSedes();
            if ($busqueda) {
                if($busqueda->rowCount()>0){
                    $resultado["estado"]=true;
                    $resultado["contenido"]=$busqueda->fetchAll();
                }else {
                    $resultado["contenido"]="Datos no encontrados";
                }
            }
            echo json_encode($resultado);
            
            
            
            break;
        /* ============================================================================================================================
                                                MUESTRA PUNTOS DE VENTA
        ============================================================================================================================*/
        case 'transferencia':

            // crea transferencia
            if ($_SERVER['REQUEST_METHOD']==='POST') {

                if (!isset($_POST["update"])) {
                    $items=json_decode($_POST["items"]);
                    // $perfil=$_SESSION["usuario"]["sede"];
                    // $encargado=$_SESSION["usuario"]["id"];
                    $sede=$_POST["sede"];
                    $encargado=$_POST["encargado"];
                    $plaremi=$_POST["plaremi"];
                    
                    $controlador = new ControladorTransferencia();
                    $transferencias=$controlador->ctrCrearTransferencia($items,$encargado,$sede,$plaremi);
                    print json_encode($transferencias);
                    return 1;
                }else {
                    $items=json_decode($_POST["items"]);
                    print json_encode($items);
                    return 0;
                    $controlador = new ControladorTransferencia();
                    $transferencias=$controlador->ctrCerrarTransferencia($items,$encargado);
                    print json_encode($transferencias);
                }
                

            // muestra transferencias
            }elseif ($_SERVER['REQUEST_METHOD']==='GET') {
                // $perfil=$_SESSION["usuario"]["sede"];
                // $encargado=$_SESSION["usuario"]["id"];
                // $sede=$_POST["sede"];
                $encargado=$_GET["encargado"];
                $plaremi=$_GET["plaremi"];

                $controlador = new ControladorTransferencia();
                $resultado = $controlador->ctrBuscarItemsTransferencia($plaremi,$encargado);
                print json_encode($resultado);
                return 1;
            }

            break;
       
        /* ============================================================================================================================
                                                    MUESTRA USUARIOS
        ============================================================================================================================*/   
        case 'usuarios':

            // $perfil=$_SESSION["usuario"]["perfil"];
            $perfil=$_REQUEST["perfil"];
            $controlador=new ControladorLoginUsuario();
            
            $resultado=$controlador->ctrBuscarUsuarios($perfil);
            
            echo json_encode($resultado);
            
            
            
            break;

        /* ============================================================================================================================
                                                     DEFAULT
        ============================================================================================================================*/
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