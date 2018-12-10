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

        $sql="INSERT INTO transferencias_det(";
        foreach ($items[0] as $column=> $cell) {
            $sql.="$column,";
        }
        $sql.="id_transferencia) VALUES ";

        for ($i=0; $i <count($items) ; $i++) { 
            $sql.="(";
            foreach ($items[$i] as $j => $cell) {
                $sql.=":$j$i,";
            }
            $sql.=":id_transferencia),";
        }
        
        $sql=substr($sql,0,-1).";";
        // return $sql;
        $stmt= $this->link->prepare($sql);
        
        foreach ($items as $i => $row) {
            $stmt->bindParam(":sede$i",$row->sede,PDO::PARAM_STR);
            $stmt->bindParam(":item$i",$row->item,PDO::PARAM_STR);
            $stmt->bindParam(":pedido$i",$row->pedido,PDO::PARAM_STR);    
            $stmt->bindParam(":id_transferencia",$transferencia,PDO::PARAM_INT);  
        }
        $res=$stmt->execute();
        // return $stmt->errorInfo();
        $stmt=null;
        return $res;

    }

    function mdlUploadPLaRemi($items)
    {
        $stmt= $this->link->prepare(
        "INSERT INTO plaremi(factura,fecha) VALUES(:factura,now()) ");
        
        $stmt->bindParam(":factura",$items[0]['factura'],PDO::PARAM_STR);     
        
        $res=$stmt->execute();
        
        if($res){
            
            $stmt=null;

            $sql="INSERT INTO plaremi_det(item,factura,pedido) VALUES";

            for ($i=0; $i <count($items) ; $i++) { 
                $sql.="(:item$i,:factura$i,:pedido$i),";
            }
            
            $sql=substr($sql,0,-1).";";
            // return $sql;
            $stmt= $this->link->prepare($sql);
            
            foreach ($items as $i => $row) {
                $stmt->bindParam(":item$i",$row['item'],PDO::PARAM_STR);
                $stmt->bindParam(":factura$i",$row['factura'],PDO::PARAM_STR);
                $stmt->bindParam(":pedido$i",$row['pedido'],PDO::PARAM_STR);  
            }
            $res=$stmt->execute();

            $stmt=null;

            // elimina registro de  plaremi si no se pueden subir los items
            if (!$res) {
                $stmt= $this->link->prepare("DELETE FROM plaremi WHERE factura=:factura");
                $stmt->bindParam(":factura",$items[0]['factura'],PDO::PARAM_STR);
                $stmt->execute();
            }

        }
        if($res==false) {
            $res=$stmt->errorCode();
        }
        
        $stmt=null;
        return $res;
    
    }

    function mdlBuscarPlaremiItems($factura)
    {
        $stmt= $this->link->prepare(
        "SELECT ID_ITEM AS item,items.DESCRIPCION AS descripcion,1 AS solicitado,
        true AS selected,pedido,sobrante,sede,sedes.descripcion AS sedesobrante
        FROM plaremi_det
        INNER JOIN sobrantes ON sobrantes.item=plaremi_det.item
        INNER JOIN items ON items.ID_ITEM=plaremi_det.item
        INNER JOIN sedes ON sedes.codigo=sobrantes.sede
        WHERE factura=:factura
        AND sobrante>0;");

        $stmt->bindParam(":factura",$factura,PDO::PARAM_STR);     

        
        if($stmt->execute()){
            return $stmt;
        }else{
            
            $stmt=null;
            return false;
        }
        $stmt=null;

        

    }
}
