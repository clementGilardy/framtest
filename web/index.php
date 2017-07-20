<?php
require_once __DIR__.'/../app/Kernel.php';
require_once __DIR__.'/../app/Autoload.php';

$autoload = new Autoload();
$kernel = new Kernel('prod', false);
$kernel->load();

