<?php
require_once __DIR__.'/../app/Kernel.php';

$kernel = new Kernel('prod');
$kernel->load();


// Si aucun controller n'est spécifié on appèlera HomeController et sa méthode index()

// En cas d'url invalid on appèlera le controller errorController et sa méthode alert()
//$router->setErrorControllerAction('error', 'alert'); 


