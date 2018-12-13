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

    public function ctrUploadPlaRemi($items=null)
    {
        if (!$items) {
            $items=$this->items;
        }

        $res=$this->modelo->mdlUploadPLaRemi($items); 
        
        if ($res===true) {
            $resultado["estado"]=true;
            $resultado["contenido"]=$items[0]['factura'];
        }else{
            $resultado["estado"]=false;
            $resultado["contenido"]="Documento ya subido";
        }   
        return $resultado;
    }

    public function ctrBsucarSobrantes($factura)
    {
        $busqueda=$this->modelo->mdlBuscarPlaremiItems($factura);
        $resultado["estado"]=false;
        if($busqueda){
            if ($busqueda->rowCount() > 0) {
                $resultado["estado"]=true;
                while($row = $busqueda->fetch()){
                    $resultado['contenido'][]=[
                    'item'=> trim($row['item']),
                    'descripcion'=> trim($row['descripcion']),
                    'pedido'=> trim($row['pedido']),
                    'solicitado'=> 1,
                    'sobrante'=>intval(trim($row['sobrante'])),
                    'sede'=>trim($row['sede']),
                    'sedesobrante'=>trim($row['sedesobrante']),
                    "selected"=>false
                    ];
                    
                }
            }
        }
        return $resultado;
    }

    

}