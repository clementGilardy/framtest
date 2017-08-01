<?php
class Controller {
    
    private $twig;
    
    public function __construct($twig){
        $this->twig = $twig;
    }
    
    protected function render($filename,array $params){
        echo $this->twig->render($filename,$params);
    }
}
