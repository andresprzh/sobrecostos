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

    function mdlCerrarTransferencia($id_transferencia,$no_transferencia)
    {

        $stmt= $this->link->prepare(
        "UPDATE transferencias 
        SET no_transferencia=:no_transferencia,estado=1
        WHERE id_transferencia=:id_transferencia;");

        $stmt->bindParam(":no_transferencia",$no_transferencia,PDO::PARAM_STR);    
        $stmt->bindParam(":id_transferencia",$id_transferencia,PDO::PARAM_INT); 
        
        $res=$stmt->execute();
        
        $stmt=null;
        return $res;

    }

    function mdlCerrarItemTransferencia($item)
    {

        
        $stmt= $this->link->prepare("UPDATE transferencias_det
        SET pedido=:pedido
        WHERE item=:item
        AND id_transferencia=:id_transferencia");

        $stmt->bindParam(":pedido",$item->pedido,PDO::PARAM_STR); 
        $stmt->bindParam(":item",$item->item,PDO::PARAM_STR);
        $stmt->bindParam(":id_transferencia",$item->id_transferencia,PDO::PARAM_INT); 
           

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