<?php
require_once __DIR__.'/Autoload.php';
require_once __DIR__.'/../vendor/autoload.php';

/**
 * @author Clément Gilardy <clement.gilardy@outlook.fr>
 * @copyright (c) 2017, Clément Gilardy
 * @class Kernel
 * 
 * Kernel of Framwork
 */
class Kernel
{
    /**
     * prod or dev
     * @var string 
     */
    private $env;
    
    /**
     * just feel the variable $env
     * @param string $env
     */
    public function __construct($env){
        $this->$env = $env;
    }
    
    /**
     * Launch module (TODO)
     * @return array
     */
    public function registersModules(){
            $modules = array();
            return $modules;
    }

    /**
     * Load the application
     */
    public function load(){
        new Autoload();
        $router = new Router();
        $router->setDefaultControllerAction('HomeController','index');
        try {
            $router->load();
        } catch (Exception $ex) {
            echo 'euh...';
        }
        
    }
}