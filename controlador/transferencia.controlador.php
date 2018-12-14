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

    
    public function ctrBuscarItemsTransferencia($plaremi,$encargado)
    {
        $busqueda=$this->modelo->mdlMostrarItemsTransferencia($plaremi,$encargado);
        // return $busqueda->fetchAll();
        if ($busqueda) {
            if ($busqueda->rowCount()>0) {
                while($row = $busqueda->fetch()){

                    $resultado[$row["origen"]]["id_transferencia"]=$row["id_transferencia"];
                    $resultado[$row["origen"]]["no_transferencia"]="";
                    $resultado[$row["origen"]]["plaremi"]=$row["plaremi"];
                    $resultado[$row["origen"]]["items"][]=$row;

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
