<?php

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
     * Controller � appeler en cas d'erreur. Par defaut error
     * @var string
     */
    private $errorController;

    /**
     * Action � appeler en cas d'erreur. par defaut index
     * @var string
     */
    private $errorAction;
    
    /**
     * Construct
     */
    function __construct() {
        $this->rules = $this->feelRules();
        $this->path = $this->feelPathController();
        $this->defaultController = 'index';
        $this->defaultAction = 'index';
        $this->errorController = 'error';
        $this->errorAction = 'index';
        
    }
   
    /**
     * Charge le controller demand�.
     * Prend en compte les r�gles de routages si n�cessaire
     */
    public function load() {
        $url = $_SERVER['REQUEST_URI'];
        $script = $_SERVER['SCRIPT_NAME'];
        
        //Permet de nettoyer l'url des �ventuels sous dossier
        $tabUrl = $this->formatUrl($url, $script);
        if($tabUrl[0] == ''){
            $tabUrl = array('/');
        }
        $isCustom = false;

        //Supression des �ventuelles parties vides de l'url
        $this->clear_empty_value($tabUrl);
        if (!empty($this->rules)) {
            foreach ($this->rules as $key => $data) {
                $params = $this->matchRules($key, $tabUrl);
                if ($params) {
                    $this->controller = $data['controller'];
                    $this->action = $data['action'];
                    if($params != 1){
                        $this->params = $params;
                    }
                    $isCustom = true;
                    break;
                } 
            }
        }
            
        $this->controller = (!empty($this->controller)) ? $this->controller : $this->defaultController;
        $this->action = (!empty($this->action)) ? $this->action : $this->defaultAction;

        $ctrlPath = str_replace('_', DIRECTORY_SEPARATOR, $this->controller); // Gestion des sous dossiers dans les controllers
 
        foreach($this->path as $path){
            $pathExplode = explode('/',$path);
            $ctrl = substr($pathExplode[5], 0,-4);
            if($ctrl == $ctrlPath){
                $this->file = $path;
            }
        }
        
        //is_file bien plus rapide que file_exists
        if (!is_file($this->file)) {
            header("Status: 404 Not Found");
            $this->controller = $this->errorController;
            $this->action = $this->errorAction;
            $this->file = $this->path . $this->controller . '.php';
        }
        
        $class = $this->controller;
      
        $controller = new $class($this->getParameters());

        if (!is_callable(array($controller, $this->action)))
            $action = $this->defaultAction;
        else
            $action = $this->action;
        
        if(!empty($this->params)){
            $stringParam = implode(',', $this->params);
            $controller->$action($stringParam);
        } else {
            $controller->$action();
        }
    }
    
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
     * D�fini une route simple
     * @param array $url
     */
    private function getRoute($url) {
        $items = $url;

        if (!empty($items)) {
            if ($this->isMultiLangue)
                $this->codeLangue = array_shift($items);

            $this->controller = array_shift($items);
            $this->action = array_shift($items);
            $size = count($items);
            if ($size >= 2)
                for ($i = 0; $i < $size; $i += 2) {
                    $key = (isset($items[$i])) ? $items[$i] : $i;
                    $value = (isset($items[$i + 1])) ? $items[$i + 1] : null;
                    $this->params[$key] = $value;
                } else
                $this->params = $items;

            //Permet d'avoir des URL multilingue
            if (!empty($this->tradController)) {
                if (isset($this->tradController[$this->codeLangue][$this->controller]['controllerName'])) {
                    $controller = $this->tradController[$this->codeLangue][$this->controller]['controllerName'];
                    if (!empty($controller))
                        $this->controller = $controller;
                }

                if (isset($this->tradController[$this->codeLangue][$this->controller]['actionsNames'][$this->action])) {
                    $action = $this->tradController[$this->codeLangue][$this->controller]['actionsNames'][$this->action];
                    if (!empty($action))
                        $this->action = $action;
                }
            }
        }
    }
    
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
     * D�fini le controller et l'action en cas d'erreur
     * @param string $controler
     * @param string $action
     */
    public function setErrorControllerAction($controller, $action) {
        $this->errorController = $controller;
        $this->errorAction = $action;
    }

    /**
     * Renvoi les param�tres disponibles
     * @return array
     */
    public function getParameters() {
        return $this->params;
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
     * Check if the file is a php file
     * 
     * @param type $file
     * @return boolean
     */
    private function isPhpFile($file){
        $isPhpFile = false;
        $info = new SplFileInfo($file);
        if($info->getExtension() == 'php')
            $isPhpFile = true;
    }
        
}
