<?php

class ModeloSobrante extends Conexion {
    

    function __construct() {
      
      parent::__construct();

    }

    public function mdlMostrarUbicaciones()
    {
        $stmt= $this->link->prepare(
        "SELECT codigo,descripcion
        FROM sedes
        WHERE codigo<'102';");

        if($stmt->execute()){
            return $stmt;
        }else{
            $stmt=null;
            return false;
        }
        $stmt=null;

    }

    public function mdlUploadData()
    {

    }
}