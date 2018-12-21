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

                
        for ($i=0; $i <count($this->file); $i++) {
            
               $csv=str_getcsv($this->file[$i], ";");
               if (count($csv)<=3) {
                    $csv=str_getcsv($this->file[$i], ",");
               }
               
               if (count($csv)<14) {
                   return false;
               }
               $fecha = $csv[1];
               $fecha = substr($fecha,0,4).'-'.substr($fecha,4,6).'-'.substr($fecha,6);
      
               
               $busqueda=$this->modelo->mdlBuscarItem(trim($csv[10]),trim($csv[3]));
            
               if ($busqueda) {
                   if ($busqueda->rowCount()>0) {
                       $busres=$busqueda->fetch();
                       
                       $this->items[]=[
                        "item"=> trim($busres["item"]),
                        "cod_drog"=> trim($csv[0]),
                        "fecha"=> $fecha,
                        "factura"=> trim($csv[2]), 
                        "refcopi"=> trim($csv[3]), 
                        "descripcion"=> trim($csv[4]),
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

    public function ctrGenerarPlaremi($factura)
    {
        $busqueda=$this->modelo->mdlBuscarDatosPlaremi($factura);
        // return $busqueda->fetchAll();
        $resultado["documento"]="";
        $resultado["plaremi"]=$factura;        
        
        while($row = $busqueda->fetch()){
            $cantidad=round($row["total"]);
            $valor_desc=round($row["costo_desc"]);
            
            $resultado["documento"].=str_pad(trim($row["cod_drog"]), 5, " ", STR_PAD_RIGHT).",";
            $resultado["documento"].=str_replace("-","",substr($row["fecha"],0,10)).",";
            $resultado["documento"].=str_pad($factura, 20, " ", STR_PAD_RIGHT).",";//factura
            $resultado["documento"].=str_pad($row["refcopi"], 15, " ", STR_PAD_RIGHT).",";//referencia copi
            $resultado["documento"].=str_pad($row["descripcion"], 60, " ", STR_PAD_RIGHT).",";
            $resultado["documento"].=str_pad($cantidad, 7, "0", STR_PAD_LEFT).",";
            $resultado["documento"].=str_pad($valor_desc, 10, "0", STR_PAD_LEFT).",";
            $resultado["documento"].=str_pad(round($row["costo_full"]), 10, "0", STR_PAD_LEFT).",";
            $resultado["documento"].=str_pad($row["iva"], 5, "0", STR_PAD_LEFT).",";
            $resultado["documento"].=str_pad($row["descuento1"]*100, 8, " ", STR_PAD_RIGHT).",";
            $resultado["documento"].=str_pad($row["cod_barras"], 13, " ", STR_PAD_LEFT).",";// codigo de fabricante
            $resultado["documento"].=str_pad($row["cod_fab"], 5, " ", STR_PAD_LEFT).",";// codigo de fabricante
            $resultado["documento"].="0000000000".",";
            $resultado["documento"].=str_pad($row["descuento2"]*100, 8, " ", STR_PAD_RIGHT).",";
            $resultado["documento"].=str_pad($row["unidad"], 4, " ", STR_PAD_RIGHT).",";
            $resultado["documento"].=str_pad($row["algo1"], 1, " ", STR_PAD_RIGHT).",";
            $resultado["documento"].=str_pad($row["algo2"], 2, "0", STR_PAD_LEFT);

            $resultado["documento"].="\r\n";

        }
        return $resultado;
    }

}