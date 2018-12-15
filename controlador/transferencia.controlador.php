<?php

class ControladorTransferencia{
    
    private $modelo;

    function __construct()
    {
        $this->modelo=new ModeloTransferencia();

    }

    public function ctrCrearTransferencia($items,$encargado,$sede,$plaremi)
    {   
        $transferencias;
        foreach ($items as $row) {
            $transferencias[$row->sede][]=$row;
        }
        
        foreach ($transferencias as $i => $tranfsede) {

            $transferencia=$this->modelo->mdlCrearTransferencia($i,$sede,$encargado);
            $resultado=false;

            if ($transferencia) {
                
                $res=$this->modelo->mdlAddItemsTransferencia($tranfsede,$transferencia,$plaremi);
                
                if($res){
                    // $resultado[$transferencia]=$tranfsede;       
                    $resultado=true;
                }

            }else {

                // $resultado[$transferencia]=false;
                $resultado=false;

            }
            
        }

        return $resultado;
    }

    public function ctrCerrarTransferencia($transferencias,$encargado)
    {   
        
        
        foreach ($transferencias as $transferencia) {

            $res=$this->modelo->mdlCerrarTransferencia($transferencia->id_transferencia,$transferencia->no_transferencia);
            $resultado=false;

            if ($res) {
                
                foreach ($transferencia->items as $item) {
                    $res=$this->modelo->mdlCerrarItemTransferencia($item);
                }
               
                if($res){
                    // $resultado[$transferencia]=$tranfsede;       
                    $resultado=true;
                }

            }else {

                // $resultado[$transferencia]=false;
                $resultado=false;

            }
            
        }

        return $resultado;
    }

    
    public function ctrBuscarItemsSolicitados($plaremi,$encargado)
    {
        $busqueda=$this->modelo->mdlMostrarItemsSolicitados($plaremi,$encargado);
        // return $busqueda->fetchAll();
        if ($busqueda) {
            if ($busqueda->rowCount()>0) {
                while($row = $busqueda->fetch()){

                    $resultado[intval($row["origen"])]["id_transferencia"]=$row["id_transferencia"];
                    $resultado[intval($row["origen"])]["no_transferencia"]="";
                    $resultado[intval($row["origen"])]["plaremi"]=$row["plaremi"];
                    $resultado[intval($row["origen"])]["items"][]=$row;

                }
            }else {
                $resultado=false;
            }
        }else {
            $resultado=false;
        }
        return $resultado;
        
    }

    public function ctrBuscarTransferencias($plaremi)
    {
        $busqueda=$this->modelo->mdlMostrarTransferencias($plaremi);

        if ($busqueda) {
            if ($busqueda->rowCount()>0) {
                while($row = $busqueda->fetch()){

                    $resultado[$row["id_transferencia"]]["id_transferencia"]=$row["id_transferencia"];
                    $resultado[$row["id_transferencia"]]["no_transferencia"]=$row["no_transferencia"];
                    $resultado[$row["id_transferencia"]]["destino"]=$row["destino"];
                    $resultado[$row["id_transferencia"]]["origen"]=$row["origen"];
                    $resultado[$row["id_transferencia"]]["origensede"]=$row["origensede"];
                    $resultado[$row["id_transferencia"]]["destinosede"]=$row["destinosede"];
                    // $resultado[$row["id_transferencia"]]["items"][]=$row;
                    $resultado[$row["id_transferencia"]]["items"][]=[
                    'item'=> trim($row['item']),
                    'descripcion'=> trim($row['descripcion']),
                    'pedido'=> trim($row['pedido'])
                    ];

                }
            }else {
                $resultado=false;
            }
        }else {
            $resultado=false;
        }
        return $resultado;
    }
}
