<?php
require_once __DIR__.'/../app/Kernel.php';

$kernel = new Kernel('prod');
$kernel->load();

$templates = array(__DIR__.'/../app/Ressources/',__DIR__.'/../src/Home/views/');
$loader = new Twig_Loader_Filesystem($templates);
$twig = new Twig_Environment($loader);

$controller = new HomeController($twig);
$action = 'index';

if(method_exists($controller, $action)){
    $controller->$action();
} else {
    echo 'erreur 404';
}




