<?php


class ModeloLoginUsuario extends Conexion {
    

    function __construct() {
      
      parent::__construct();

    }
    


    /*==========================================
            MOSTRAR USUARIOS
      ========================================*/
    public function mdlMostrarUsuarios($perfil=null,$item=null,$valor=null){
            
      //busca los items
  
        
      if ($item) {
        $stmt= $this->link->prepare(
          "SELECT *
          FROM usuarios
          INNER JOIN perfiles ON id_perfil=perfil
          WHERE $item=:$item;
          LIMIT 1");

          $stmt->bindParam(":".$item,$valor,PDO::PARAM_STR);
      }elseif($perfil==1) {
        
        $stmt= $this->link->prepare(
        "SELECT id_usuario AS id,usuario,nombre,cedula,perfil,des_perfil,sede,descripcion AS des_sede
        FROM usuarios
        INNER JOIN perfiles ON id_perfil=perfil
        INNER JOIN sedes ON sede=sedes.codigo
        ORDER BY des_perfil ASC;");
      }else {

        return "Error usuario no tiene permisos";

      }

      $stmt->execute();
        
      return $stmt;
      $stmt=null;
    } 
  

}