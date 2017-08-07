<?php
class Controller {
    
    private $twig;
    protected $params;
    
    public function __construct($params){
        $templates = array(__DIR__.'/../app/Ressources/',__DIR__.'/../src/Home/views/');
        $loader = new Twig_Loader_Filesystem($templates);
        $this->twig =  new Twig_Environment($loader);
        $this->params = $params;
    }
    
    protected function render($filename,array $params){
        echo $this->twig->render($filename,$params);
    }
}
