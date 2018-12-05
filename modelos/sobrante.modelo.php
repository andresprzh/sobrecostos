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

    public function mdlUploadData($items)
    {
        // $columns=array_keys($items[0]);
        $sql="INSERT INTO sobrecostos (";
        foreach ($items[0] as $column=> $cell) {
            $sql.="$column ";
        }
        $sql.=") VALUES ";
        
        for ($i=0; $i <count($items) ; $i++) { 
            $sql.="(";
            foreach ($items[$i] as $j => $cell) {
                $sql.=":$j$i,";
            }
            $sql=substr($sql,-1)."),";
        }
        $sql=substr($sql,-1).";";

        $stmt= $this->link->prepare($sql);

        for ($i=0; $i <count($items) ; $i++) { 
            foreach ($items[$i] as $j => $cell) {
                $stmt->bindParam(":$j$i",$cell,PDO::PARAM_STR);
            }
        }
        $res=$stmt->execute();
        $stmt=null;
        return $res;
    }
}