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
        $sql="SET FOREIGN_KEY_CHECKS=0; INSERT INTO sobrantes (";
        foreach ($items[0] as $column=> $cell) {
            $sql.="$column,";
        }
        $sql=substr($sql,0,-1).") VALUES ";
        // return $sql;
        for ($i=0; $i <count($items) ; $i++) { 
            $sql.="(";
            foreach ($items[$i] as $j => $cell) {
                $sql.=":$j$i,";
            }
            $sql=substr($sql,0,-1)."),";
        }
        
        $sql=substr($sql,0,-1).";";
        
        $stmt= $this->link->prepare($sql);
        
        foreach ($items as $i => $row) {
            $stmt->bindParam(":sede$i",$row['sede'],PDO::PARAM_STR);
            $stmt->bindParam(":item$i",$row['item'],PDO::PARAM_STR);
            $stmt->bindParam(":factor$i",$row['factor'],PDO::PARAM_INT);
            $stmt->bindParam(":ultimo_costo$i",$row['ultimo_costo'],PDO::PARAM_STR);
            $stmt->bindParam(":inventario_IO$i",$row['inventario_IO'],PDO::PARAM_INT);
            $stmt->bindParam(":rotacion$i",$row['rotacion'],PDO::PARAM_INT);
            $stmt->bindParam(":prom_6_meses$i",$row['prom_6_meses'],PDO::PARAM_STR);
            $stmt->bindParam(":sobrante$i",$row['sobrante'],PDO::PARAM_INT);    
        }
        // for ($i=0; $i <count($items) ; $i++) { 
            
        //     foreach ($items[$i] as $j => $cell) {
                
        //         $stmt->bindParam(":$j$i",$cell,PDO::PARAM_STR);
        //     }
        //     // $stmt->bindParam(":sede",$cell,PDO::PARAM_STR);
        // }
        
        // return 0;
        $res=$stmt->execute();
        return ($stmt->errorInfo());
        $stmt=null;
        return $res;
    }
}