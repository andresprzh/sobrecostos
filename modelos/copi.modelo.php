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
        AND sobrante>0
        ORDER BY DESCRIPCION ASC;");

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
