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
                       
                       $this->items[]=[
                        "item"=> trim($busres["item"]),
                        "cod_drog"=> trim($csv[0]),
                        "fecha"=> $fecha,
                        "factura"=> trim($csv[2]), 
                        "refcopi"=> trim($csv[3]), 
                        "descripcion"=> trim($busres["descripcion"]),
                        "pedido"=> intval(trim($csv[5])),
                        "costo_desc"=> floatval(trim($csv[6])), 
                        "costo_full"=> floatval(trim($csv[7])), 
                        "iva"=> floatval(trim($csv[8])), 
                        "descuento1"=> floatval(trim($csv[9])/100),
                        "cod_barras"=> trim($csv[10]), 
                        "cod_fab"=> trim($csv[11]),
                        "descuento2"=> floatval(trim($csv[13])/100),
                        "unidad"=> trim($csv[14]),
                        "algo1"=> intval(trim($csv[15])),
                        "algo2"=> intval(trim($csv[16])),
                       ];
                        // $this->items[]=['item'=> trim($busres['item']),
                        // 'cod_drog'=>trim($csv[0]),
                        // 'fecha'=>$fecha,
                        // 'cod_barras'=> trim($csv[10]),
                        // 'unidad'=> trim($csv[14]),
                        // 'cantidad'=> intval(trim($csv[5])),
                        // 'sobrantes'=>intval(trim($busres['sobrante'])),
                        // 'sede'=>trim($busres['sede']),
                        // 'sedesobrante'=>trim($busres['nomsede']),
                        // 'precio_unidad'=> intval(trim($csv[6])),
                        // 'descuento1'=> floatval(trim($csv[9])/100),
                        // 'descuento2'=> floatval(trim($csv[13])/100),
                        // 'iva'=> floatval(trim($csv[8])),
                        // 'factura'=> trim($csv[2]),
                        // 'descripcion'=> trim($busres['descripcion']),
                        // "pedido"=>1,
                        // "selected"=>false
                        // ];
                    
                   }
               }
                

        }
        return $this->items;
    }

    public function ctrUploadPlaRemi($sede,$items=null)
    {
        if (!$items) {
            $items=$this->items;
        }
        $resultado["estado"]=false;

        $res=$this->modelo->mdlUploadPLaRemi($sede,$items); 
        
        if ($res===true) {
            $resultado["estado"]=true;
            $resultado["contenido"]=$items[0]['factura'];
        }elseif($res=="23000"){
            $resultado["contenido"]="Documento ya subido";
        }else{
            $resultado["contenido"]="Error al Subir el documento";
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
            }else {
                $resultado["contenido"]="Error Remision no encontrada";
            }
        }else {
            $resultado["contenido"]="Error";
        }
        return $resultado;
    }

    

}