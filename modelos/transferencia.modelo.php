<?php

class ModeloTransferencia extends Conexion {
    

    function __construct() {
      
      parent::__construct();

    }

    function mdlCrearTransferencia($origen,$destino,$encargado)
    {

        $stmt= $this->link->prepare(
        "INSERT INTO transferencias(origen,destino,fecha,encargado) VALUES(:origen,:destino,now(),:encargado) ");

        $stmt->bindParam(":origen",$origen,PDO::PARAM_STR);    
        $stmt->bindParam(":destino",$destino,PDO::PARAM_STR);    
        $stmt->bindParam(":encargado",$encargado,PDO::PARAM_STR);   
        
        $res=$stmt->execute();
        
        if($res){
            $res=$this->link->lastInsertId();
        }
        $stmt=null;
        return $res;

    }

    function mdlAddItemsTransferencia($items,$transferencia,$plaremi)
    {

        $sql="INSERT INTO transferencias_det(item,id_transferencia,plaremi,pedido) VALUES ";

        for ($i=0; $i <count($items) ; $i++) { 
            $sql.="(:item$i,:id_transferencia,:plaremi,:pedido$i),";
        }
        $sql=substr($sql,0,-1).";";
        // return $sql;
        $stmt= $this->link->prepare($sql);
        
        foreach ($items as $i => $row) {
            
            $stmt->bindParam(":item$i",$row->item,PDO::PARAM_STR);
            $stmt->bindParam(":id_transferencia",$transferencia,PDO::PARAM_INT); 
            $stmt->bindParam(":plaremi",$plaremi,PDO::PARAM_INT);
            $stmt->bindParam(":pedido$i",$row->pedido,PDO::PARAM_STR);    
             
        }
        $res=$stmt->execute();
        
        $stmt=null;
        return $res;

    }

    function mdlMostrarItemsTransferencia($plaremi,$encargado){

        $stmt= $this->link->prepare("CALL BuscarItemsTransferencia(:plaremi,:encargado);");

        $stmt->bindParam(":plaremi",$plaremi,PDO::PARAM_STR);
        $stmt->bindParam(":encargado",$encargado,PDO::PARAM_INT);

        if($stmt->execute()){
            return $stmt;
        }else {
            return false;
        }
    } 
}