<?php

/**
 * @author Clément Gilardy <clement.gilardy@outlook.fr>
 * @copyright (c) 2017, Clément Gilardy
 * @class Application
 * 
 * Main class to launch the script of the Happy Framwork 
 * 
 */

class Application {
    /**
     * User's params
     * @var array 
     */
    private $params;
    
    /**
     * List of function
     * @var array 
     */
    private $help;
    
    /**
     * Array index where is the name of classe
     * @var const
     */
    const NUM_INSTANCE_CLASS = 1;
    
    /**
     * Array index where is the name of action
     * @var const
     */
    const NUM_ACTION_TO_LAUNCH = 2;
    
    /**
     * Size max of function
     * @var const
     */
    const MAX_SIZE_FUNCTION = 15;
    
    /**
     * Array index where is the param
     * @var const
     */
    const WHERE_IS_PARAMS = 1;
    
    
    /**
     * feel params and list the function possible
     * 
     * @param array $params : params enter by the user
     */
    function __construct(array $params = null){
        $this->params = $params;
        $this->help = json_decode(file_get_contents(__DIR__.'/help.json'),true);
        
        echo "Framwork Happy ! \n\n";
    }
    
    /**
     * Run the script
     * @throws ApplicationException
     */
    public function run(){
        if(!empty($this->params[self::WHERE_IS_PARAMS])){
            if($this->isExistFunction()){
                $this->launchFunction($this->params[self::WHERE_IS_PARAMS]);
            } else {
                throw new ApplicationException("This function doesn't exist !");
            }
        } else {
            $this->displayAllMethode();
        }
    }
    
    /**
     * Launch the class and function choose by the user
     * @param type $param
     */
    private function launchFunction($param){
        //split the params
        $whatLaunch = explode(':',$param);
        
        //we retrieve the class
        $class = ucfirst($whatLaunch[self::NUM_INSTANCE_CLASS]);
        //we retrieve action launch by the user
        $action = $whatLaunch[self::NUM_ACTION_TO_LAUNCH];
        
        //instance class and launch the action
        $app = new $class;
        $app->$action();
    }
    
    /**
     * Display help
     */
    private function displayAllMethode(){
        echo "Framwork's Functions \n\n";
        foreach ($this->help as $func => $desc){
            if(strlen($func) < self::MAX_SIZE_FUNCTION){
                echo $func."\t\t\t".$desc."\n";
            } else {
                echo $func."\t\t".$desc."\n";
            }
        }
    }
    
    /**
     * Look at if the function exist in the json file
     * @return boolean
     */
    private function isExistFunction(){
        $find = false;
        foreach ($this->help as $func => $desc){
            if($func == $this->params[self::WHERE_IS_PARAMS]){
                $find = true;
            }
        }
        return $find;
    }
}