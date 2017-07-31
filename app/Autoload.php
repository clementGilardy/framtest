<?php

require_once __DIR__.'/../vendor/autoload.php';

class Autoload
{
    /**
     * Load all class
     * @param type $class_name
     */
    public function __construct($class_name){
        $pathToClass = dirname(__DIR__)."/../src/";
        $arrayMainDir = scandir($pathToClass);
        foreach ($arrayMainDir as $file){
            if(!$this->isDoteFile($file)){
                $this->loadClass($file);
            }
        }
    }

    /**
     * search and load the class
     * 
     * @param type $file
     * @return type
     */
    public function loadClass($fileOrDir) {
        $fileOrDir = dirname($fileOrDir).'/'.$fileOrDir;
        if(is_file($fileOrDir)){
            require_once $fileOrDir;
        } else {
            foreach ($fileOrDir as $file){
                if(is_file($file)) {
                    require_once dirname($file).'/'.$file;
                } else {
                    $this->loadClass($file);
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
}