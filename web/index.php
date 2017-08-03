<?php
require_once __DIR__.'/../app/Kernel.php';

$kernel = new Kernel('prod');
$kernel->load();
 
$router = new Router();
//Définition du dossier contenant les controlleur
$router->setPath('../src/Home/controller/');
// Si aucun controller n'est spécifié on appèlera HomeController et sa méthode index()
$router->setDefaultControllerAction('HomeController','index');
// En cas d'url invalid on appèlera le controller errorController et sa méthode alert()
//$router->setErrorControllerAction('error', 'alert'); 

$router->addRule('/', array('controller' => 'HomeController', 'action' => 'index'));
$router->load();