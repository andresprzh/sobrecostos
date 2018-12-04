<?php

class ControladorSobrante{

    private $file;

    function __construct($file)
    {
        $this->file=$file;
    }

    public function ctrGetData()
    {   
        $res;
        $csv = array_map('str_getcsv', $this->file);
        return count($csv);
        // foreach ($this->file as $line) {
        //        $res[]=$line;         
        // }
        // return str_getcsv($res[1], ";");
    }
}