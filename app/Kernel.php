<?php
require_once __DIR__.'/Autoload.php';
require_once __DIR__.'/../vendor/autoload.php';

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
    
    public function loadRouterRules(Router $router){
        
    }
}