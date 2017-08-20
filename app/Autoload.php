<?php
/**
 * @author Clément Gilardy <clement.gilardy@outlook.fr>
 * @copyright (c) 2017, Clément Gilardy
 * @class Autoload
 * 
 * Load all class
 */
class Autoload
{
    /**
     *  all folders
     * @var array 
     */
    private $dirs;
    
    /**
     * Extension PHP
     * @var const
     */
    const EXTENSION_PHP = 'php';
    
    /**
     * Separator of path
     * @var const
     */
    const SEPARATOR_PATH = '/';
    
    /**
     * Dot file
     * @var const
     */
    const DOT_FILE = '.';
    
    /**
     * Double dot file
     * @var const
     */
    const DOUBLE_DOT_FILE = '..';
    
    /**
     * Load all class
     * @param type $class_name
     */
    public function __construct(){
        
        $dirs = array(
            __DIR__.'/../happy/',
            __DIR__.'/../src/'
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
                    if(is_file($fileOrDir.self::SEPARATOR_PATH.$file) && $this->isPhpFile($file)) {
                        require_once $fileOrDir.self::SEPARATOR_PATH.$file;
                    }  
                   
                    if (is_dir($fileOrDir.self::SEPARATOR_PATH.$file)){
                        $this->loadClass($fileOrDir.self::SEPARATOR_PATH.$file);
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
        if($file == self::DOT_FILE || $file == self::DOUBLE_DOT_FILE){
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
        if($info->getExtension() == self::EXTENSION_PHP)
            $isPhpFile = true;
        return $isPhpFile;
    }
}