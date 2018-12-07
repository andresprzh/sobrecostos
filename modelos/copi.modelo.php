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

    function mdlCrearTransferencia($destino,$encargado){

        $stmt= $this->link->prepare(
        "INSERT INTO transferencias(destino,fecha,encargado) VALUES(:destino,now(),:encargado) ");

        $stmt->bindParam(":destino",$destino,PDO::PARAM_STR);    
        $stmt->bindParam(":encargado",$encargado,PDO::PARAM_STR);   
        
        $res=$stmt->execute();
        
        if($res){
            $res=$this->link->lastInsertId();
        }
        $stmt=null;
        return $res;

    }

    function mdlAddItemsTransferencia($items,$transferencia){

        $sql="INSERT INTO transferencia_det(";
        foreach ($items[0] as $column=> $cell) {
            $sql.="$column,";
        }
        $sql=substr($sql,0,-1).") VALUES ";

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
            $stmt->bindParam(":pedido$i",$row['pedido'],PDO::PARAM_STR);    
        }
        $res=$stmt->execute();
        $stmt=null;
        return $res;

    }
}
