<?php

class ModeloCopi extends Conexion {
    

    function __construct() {
      
      parent::__construct();

    }


    function mdlBuscarItem($cod){

        $stmt= $this->link->prepare(
        "SELECT item,sobrante,sede,items.DESCRIPCION AS descripcion, 
        sedes.descripcion AS nomsede
        FROM sobrantes
        INNER JOIN items ON items.ID_ITEM=item
        INNER JOIN cod_barras ON cod_barras.ID_ITEMS=ID_item
        INNER JOIN sedes ON codigo=sede
        WHERE cod_barras.ID_CODBAR=:codigo");

        $stmt->bindParam(":codigo",$cod,PDO::PARAM_STR);    

        if($stmt->execute()){
            return $stmt;
        }else{
            return $stmt->errorInfo();
            $stmt=null;
            return false;
        }
        $stmt=null;

    }
}
