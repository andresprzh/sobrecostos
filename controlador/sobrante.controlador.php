<?php

class ControladorSobrante{

    private $file;

    function __construct($file)
    {
        $this->file=$file;
    }

    public function ctrGetData()
    {   
        $item;
        // $csv = array_map('str_getcsv', $this->file);
        
        // for ($i=1; $i < 10; $i++) { 
        //    
        // }
        // return $csv[1][0];
        // return count($this->file);
        for ($i=1; $i < 10; $i++) {
            //    $res[]=$line;
               $csv=str_getcsv($this->file[$i], ";");
               $item[]['sede']=$csv[0];      
        }
        
        return $item;
    }
}