<?php
include_once __DIR__.'/../app/Autoload.php';
new Autoload();

$app = new Application($argv);

try{
    $app->run();
} catch (ApplicationException $ex) {
    echo $ex->getMessage();
} catch(Exception $ex){
    echo "Why doesn't work ??";
}
