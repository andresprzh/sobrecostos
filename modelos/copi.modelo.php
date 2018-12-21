<?php

class ModeloCopi extends Conexion {
    

    function __construct() {
      
      parent::__construct();

    }

    function mdlBuscarItem($cod,$ref){

        $stmt= $this->link->prepare(
        "SELECT ID_ITEMS AS item,items.DESCRIPCION AS descripcion 
        FROM items
        INNER JOIN cod_barras ON cod_barras.ID_ITEMS=ID_item
        WHERE cod_barras.ID_CODBAR=:codigo
        OR ID_REFERENCIA=:referencia
        LIMIT 1;");

        $stmt->bindParam(":codigo",$cod,PDO::PARAM_STR);    
        $stmt->bindParam(":referencia",$ref,PDO::PARAM_STR); 

        if($stmt->execute()){
            return $stmt;
        }else{
            return $stmt->errorInfo();
            $stmt=null;
            return false;
        }
        $stmt=null;

    }

    function mdlUploadPLaRemi($sede,$items)
    {
        $stmt= $this->link->prepare(
        "INSERT INTO plaremi(factura,cod_drog,fecha,sede) VALUES(:factura,:cod_drog,now(),:sede) ");
        
        $stmt->bindParam(":factura",$items[0]['factura'],PDO::PARAM_STR);     
        $stmt->bindParam(":cod_drog",$items[0]['cod_drog'],PDO::PARAM_STR);     
        $stmt->bindParam(":sede",$sede,PDO::PARAM_STR);
        
        $res=$stmt->execute();
       
        if($res){
            
            $stmt=null;

            $sql="INSERT INTO plaremi_det(item,factura,refcopi,pedido,costo_desc,costo_full,iva,descuento1,cod_barras,cod_fab,descuento2,unidad,algo1,algo2) VALUES";

            for ($i=0; $i <count($items) ; $i++) { 
                $sql.="(:item$i,:factura$i,:refcopi$i,:pedido$i,:costo_desc$i,:costo_full$i,:iva$i,:descuento1$i,:cod_barras$i,:cod_fab$i,:descuento2$i,:unidad$i,:algo1$i,:algo2$i),";
            }
            
            $sql=substr($sql,0,-1).";";
            // return $sql;
            $stmt= $this->link->prepare($sql);
            
            foreach ($items as $i => $row) {
                $stmt->bindParam(":item$i",$row['item'],PDO::PARAM_STR);
                $stmt->bindParam(":factura$i",$row['factura'],PDO::PARAM_STR);
                $stmt->bindParam(":refcopi$i",$row['refcopi'],PDO::PARAM_STR);
                $stmt->bindParam(":pedido$i",$row['pedido'],PDO::PARAM_STR);  
                $stmt->bindParam(":costo_desc$i",$row['costo_desc'],PDO::PARAM_STR);  
                $stmt->bindParam(":costo_full$i",$row['costo_full'],PDO::PARAM_STR); 
                $stmt->bindParam(":iva$i",$row['iva'],PDO::PARAM_STR);
                $stmt->bindParam(":descuento1$i",$row['descuento1'],PDO::PARAM_STR);
                $stmt->bindParam(":cod_barras$i",$row['cod_barras'],PDO::PARAM_STR);
                $stmt->bindParam(":cod_fab$i",$row['cod_fab'],PDO::PARAM_STR);
                $stmt->bindParam(":descuento2$i",$row['descuento2'],PDO::PARAM_STR);
                $stmt->bindParam(":unidad$i",$row['unidad'],PDO::PARAM_STR);
                $stmt->bindParam(":algo1$i",$row['algo1'],PDO::PARAM_STR);
                $stmt->bindParam(":algo2$i",$row['algo2'],PDO::PARAM_STR);
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
        true AS selected,plaremi_det.total AS pedido,sobrante,sede,sedes.descripcion AS sedesobrante
        FROM plaremi_det
        INNER JOIN sobrantes ON sobrantes.item=plaremi_det.item
        INNER JOIN items ON items.ID_ITEM=plaremi_det.item
        INNER JOIN sedes ON sedes.codigo=sobrantes.sede
        WHERE factura=:factura
        AND sobrante>0
        AND plaremi_det.total>0
        AND plaremi_det.estado=0
        AND sobrantes.estado=0
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

    public function mdlBuscarDatosPlaremi($factura)
    {
        $stmt= $this->link->prepare(
        "SELECT plaremi.cod_drog,DATE_FORMAT(plaremi.fecha, '%Y%m%d') AS fecha,plaremi.factura,refcopi,items.DESCRIPCION AS descripcion,
        plaremi_det.pedido,costo_desc,costo_full,plaremi_det.iva,descuento1,cod_barras,cod_fab,descuento2,unidad,algo1,algo2
        ,total
        FROM plaremi_det
        INNER JOIN plaremi ON plaremi.factura=plaremi_det.factura
        INNER JOIN items ON items.ID_ITEM=plaremi_det.item
        WHERE plaremi.factura=:factura
        AND total>0;");
    
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
