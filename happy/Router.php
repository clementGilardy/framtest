<?php
/**
 * @author Clément Gilardy <clement.gilardy@outlook.fr>
 * @copyright (c) 2017, Clément Gilardy
 * @class Router
 * 
 * Catch route and check with the router
 */
class Router {
    /**
     * Controller � utiliser. Par defaut index
     * @var string
     */
    private $controller;

    /**
     * Action du controller. Par d�faut index
     * @var string
     */
    private $action;

    /**
     * Tableau des param�tres
     * @var array
     */
    private $params;

    /**
     * Liste des r�gles de routage
     * @var array
     */
    private $rules;

    /**
     * Chemin vers le dossier contenant les controllers
     * @var string
     */
    private $path;

    /**
     * Fichier � inclure
     * @var string
     */
    private $file;

    /**
     * Controller par defaut (index)
     * @var string
     */
    private $defaultController;

    /**
     * Action par defaut (index)
     * @var string
     */
    private $defaultAction;
   
    /**
     * Construct
     */
    function __construct() {
        $this->rules = $this->feelRules();
        $this->path = $this->feelPathController();
    }
   
    /**
     * load the ask controller
     */
    public function load() {
        $url = $_SERVER['REQUEST_URI'];
        $script = $_SERVER['SCRIPT_NAME'];
        
        //clean the url
        $tabUrl = $this->formatUrl($url, $script);
        
        if(empty($tabUrl[0]) || $tabUrl[0] == ''){
            $tabUrl = array('/');
        }
        
        //feel params, controler and action
        $this->feelsParamsAndActionController($tabUrl);
        
        //feel file (contains link to controller
        $this->file = $this->feelFile();
        
        //we instance the controller
        $controller = new $this->controller($this->params);

        if (!is_callable(array($controller, $this->action))) {
            $action = $this->defaultAction;
        } else {
            $action = $this->action;
        }

        if(!empty($this->params)){
            $stringParam = implode(',', $this->params);
            $controller->$action($stringParam);
        } else {
            $controller->$action();
        }
    }
    
    /**
     * Check if the rules are the same
     * 
     * @param type $rule
     * @param type $dataItems
     * @return boolean
     */
    public function matchRules($rule, $dataItems) {
        $find = false;
        $result = array();
        $ruleItems = explode('/', $rule);
        $this->clear_empty_value($ruleItems);
        if(empty($ruleItems)){
            $ruleItems = array('/');
        }
        
        if (count($ruleItems) == count($dataItems)) {
            $result = array();
            foreach ($ruleItems as $rKey => $rValue) {
                if($ruleItems == $dataItems){
                    $find = true;
                }
            }
        }
        
        foreach ($ruleItems as $rKey => $rValue) {
            if ($rValue[0] == ':') {
                $rValue = substr($rValue, 1); //Supprime les : de la cl�
                $result[$rValue] = $dataItems[$rKey];
            } else {
                if ($rValue != $dataItems[$rKey]) {
                    return false;
                }
            }
        }
        
        if(!empty($result)){
            return $result;
        } else {
            return $find;   
        }       
    }
    
    /**
     * feel the controller and the action and the params
     * @param array $tabUrl
     */
    private function feelsParamsAndActionController($tabUrl){
         if (!empty($this->rules)) {
            foreach ($this->rules as $key => $data) {
                $params = $this->matchRules($key, $tabUrl);
                if ($params) {
                    $this->controller = $data['controller'];
                    $this->action = $data['action'];
                    if($params != 1){
                        $this->params = $params;
                    }
                    break;
                } 
            }
        }
    }
       
    /**
     * Return all rules present in the json file routing.json
     * @return array
     */
    public function feelRules(){
        $pathRules = __DIR__.'/routing.json';
        $rawRules = file_get_contents(__DIR__."/../app/routing.json");
        return json_decode($rawRules,true);
    }
  
    /**
     * D�fini le controller et l'action par d�faut
     * @param string $controller
     * @param string $action
     */
    public function setDefaultControllerAction($controller, $action) {
        $this->defaultController = $controller;
        $this->defaultAction = $action;
    }

    /**
     * Supprime d'un tableau tous les �lements vide
     * @param array $array
     */
    private function clear_empty_value(&$array) {
        foreach ($array as $key => $value) {
            if (empty($value))
                unset($array[$key]);
        }
        $array = array_values($array); // R�organise les clés
    }

    /**
     * Supprime les sous dossier d'une url si n�cessaire
     * @param string $url
     * @return string
     */
    private function formatUrl($url, $script) {
        $tabUrl = explode('/', $url);
        $tabScript = explode('/', $script);
        $size = count($tabScript);

        for ($i = 0; $i < $size; $i++) {
            if(isset($tabScript[$i]) && isset($tabUrl[$i])){
                if ($tabScript[$i] == $tabUrl[$i]) {
                    unset($tabUrl[$i]);
                }
            }
        }
        return array_values($tabUrl);
    }
    
    /**
     * Return an array of all controller of the application
     * @return array
     */
    private function feelPathController(){
        $pathController = __DIR__.'/../src';
        $dirsControllers = scandir($pathController);
        $path = array();
        foreach ($dirsControllers as $dir){
            if($dir != '.' && $dir != '..'){
                $newPath = $pathController.'/'.$dir;
                $file = $newPath.'/controller/'.$dir.'Controller.php';
                if(is_file($file)){
                    $path[] = $file;
                }
            }
        }
        return $path;
    }
    
    /**
     * feel $this->file which contains real path of controller
     */
    private function feelFile(){
         foreach($this->path as $path){
            $pathExplode = explode('/',$path);
            $ctrl = substr($pathExplode[5], 0,-4);
            if($ctrl == $this->controller){
                $this->file = $path;
            }
        }
    }
        
}
