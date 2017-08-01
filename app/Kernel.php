<?php
require_once __DIR__.'/Autoload.php';

class Kernel
{
    private $env;
    
    public function __construct($env){
        $this->$env = $env;
    }
    
    public function registersModules(){
            $modules = array();
            return $modules;
    }

    public function load(){
        new Autoload();
    }
}