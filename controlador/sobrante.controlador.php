<?php

class ControladorSobrante{

    private $file;
    private $sedes;
    private $modelo;
    private $items;

    function __construct($file=null)
    {
        $this->modelo=new ModeloSobrante();
        if($file){
            $this->file=$file;
        }
    }

    public function ctrGetData()
    {   
        $items;
        if (!$this->sedes) {
            $this->ctrGetSedes();
        }
        // $csv = array_map('str_getcsv', $this->file);
        
        // for ($i=1; $i < 10; $i++) { 
        //    
        // }
        // return $csv[1][0];
        // return count($this->file);
        // return $this->sedes;
        
        for ($i=1; $i <count($this->file); $i++) {
            //    $res[]=$line;
               $csv=str_getcsv($this->file[$i], ";");
               if (count($csv)<=3) {
                    $csv=str_getcsv($this->file[$i], ",");
               }
               
               if (is_numeric($csv[4])) {
                $this->items[]=["sede"=>$this->sedes[trim($csv[0])],
                            "item"=>trim($csv[1]),
                            "factor"=>trim($csv[4]),
                            "ultimo_costo"=>str_replace(["$"," ",".",","],"",$csv[5]),
                            "inventario_IO"=>trim($csv[6]),
                            "rotacion"=>trim($csv[7]),
                            "prom_6_meses"=>trim($csv[6]),
                            "sobrante"=>$csv[9]*-1
                        ]; 
                }
        }
        return array_keys($this->items[1]);
        $lenngth=count($this->items);
        return $lenngth;
    }

    public function ctrGetSedes(){
        
        $busqueda=$this->modelo->mdlMostrarUbicaciones();
        $resultado;
        while($row = $busqueda->fetch()){
            $this->sedes[trim($row["descripcion"])]=$row["codigo"];
        }
        return $this->sedes;
    }

    public function ctrUploadData($items=null)
    {

        if (!$items) {
            $items=$this->items;
        }

        $resultado=$this->modelo->mdlUploadData($items);

        return $resultado;

    }
}