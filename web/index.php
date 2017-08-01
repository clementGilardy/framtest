<?php
require_once __DIR__.'/../app/Kernel.php';
require_once __DIR__.'/../vendor/autoload.php';

$kernel = new Kernel('prod');
$kernel->load();

$loader = new Twig_Loader_Filesystem(__DIR__.'/../app/Ressources/');
$twig = new Twig_Environment($loader);
