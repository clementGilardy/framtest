<?php
/**
 * @author Clément Gilardy <clement.gilardy@outlook.fr>
 * @copyright (c) 2017, Clément Gilardy
 * @class Controller
 * 
 * Controller
 */
class Controller {
    
    /**
     * Contains twig as an object
     * @var Twig_Environment 
     */
    private $twig;
    
    /**
     * params of the function
     * @var array 
     */
    protected $params;
    
    /**
     * load twig, ressource and views
     * @param array $params
     */
    public function __construct($params){
        $templates = array(__DIR__.'/../app/Ressources/',__DIR__.'/../src/Home/views/');
        $loader = new Twig_Loader_Filesystem($templates);
        $this->twig =  new Twig_Environment($loader);
        $this->params = $params;
    }
    
    /**
     * Display the views and send the param to the view
     * 
     * @param type $filename
     * @param array $params
     */
    protected function render($filename,array $params){
        echo $this->twig->render($filename,$params);
    }
}
