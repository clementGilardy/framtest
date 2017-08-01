<?php
class Autoload
{
    
    private $dirs;
    /**
     * Load all class
     * @param type $class_name
     */
    public function __construct(){
        
        $dirs = array(
            __DIR__.'/../src/',
            __DIR__.'/../happy/'
        );
        
        foreach($dirs as $dir){
            $this->loadDir($dir);
        }
    }
    
    /**
     * Load each class in each directory
     * @param type $dirPath
     */
    private function loadDir($dirPath){
        $arrayMainDir = scandir($dirPath);
        foreach ($arrayMainDir as $file){
            if(!$this->isDoteFile($file)){
                $this->loadClass($dirPath.$file);
            }
        }
    }

    /**
     * search and load the class
     * 
     * @param type $file
     * @return type
     */
    private function loadClass($fileOrDir) {
        if(is_file($fileOrDir) && $this->isPhpFile($fileOrDir)){
            require_once $fileOrDir;
        } 
        
        if (is_dir($fileOrDir)){
            $dirs = scandir($fileOrDir);
            foreach ($dirs as $file){
                if(!$this->isDoteFile($file)){
                    if(is_file($file) && $this->isPhpFile($file)) {
                        require_once $fileOrDir.'/'.$file;
                    }  
                    
                    if (is_dir($file)){
                        $this->loadClass($fileOrDir.'/'.$file);
                    }
                }
            }
        }
    }

    /**
     * Check if the file is a dote file
     * 
     * @param type $file
     * @return boolean
     */
    private function isDoteFile($file){
        $isDoteFile = false;
        if($file == '.' || $file == '..'){
            $isDoteFile = true;
        }
        return $isDoteFile;
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
        return $isPhpFile;
    }
}