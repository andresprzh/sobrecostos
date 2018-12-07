<?php

class ControladorCopi{

    private $file;
    private $sede;
    private $modelo;
    private $items;

    function __construct($file=null)
    {
        $this->modelo=new ModeloCopi();
        if($file){
            $this->file=$file;
        }
    }

    public function ctrGetData()
    {   
        $items;

                
        for ($i=1; $i <count($this->file); $i++) {
            
               $csv=str_getcsv($this->file[$i], ";");
               if (count($csv)<=3) {
                    $csv=str_getcsv($this->file[$i], ",");
               }
               
               if (count($csv)<14) {
                   return false;
               }
               $fecha = $csv[1];
               $fecha = substr($fecha,0,4).'-'.substr($fecha,4,6).'-'.substr($fecha,6);
               
               $busqueda=$this->modelo->mdlBuscarItem(trim($csv[10]));
               
               if ($busqueda) {
                   if ($busqueda->rowCount()>0) {
                       $busres=$busqueda->fetch();
                       if ($busres['sobrante']>0){
                        $this->items[]=['item'=> trim($busres['item']),
                        'cod_barras'=> trim($csv[10]),
                        'unidad'=> trim($csv[14]),
                        'cantidad'=> intval(trim($csv[5])),
                        'sobrantes'=>intval(trim($busres['sobrante'])),
                        'sede'=>trim($busres['sede']),
                        'sedesobrante'=>trim($busres['nomsede']),
                        'precio_unidad'=> intval(trim($csv[6])),
                        'descuento1'=> floatval(trim($csv[9])/100),
                        'descuento2'=> floatval(trim($csv[13])/100),
                        'iva'=> floatval(trim($csv[8])),
                        'factura'=> trim($csv[2]),
                        'descripcion'=> trim($busres['descripcion']),
                        "pedido"=>1,
                        "selected"=>false
                        ];
                       }
                   }
               }
                

        }
        return $this->items;
    }

    public function ctrUploadPlarRemi($items=null)
    {
        if (!$items) {
            $items=$this->items;
        }

        $resultado=$this->modelo->mdlUploadRemi(); 
    }

    

}