<?php
include_once __DIR__.'/Route.php';

class Router {
    public $routes;

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function resolve($app_path) {
        $matched = false;
        foreach($this->routes as $route) {
            if(strpos($app_path, $route->pattern) === 0) {
                $matched = true;
                break;
            }
        }

        if(!$matched) 
            throw new Exception('Could not match route.');

        $param_str = str_replace($route->pattern, '', $app_path);
        $params = explode('/', trim($param_str, '/'));
        $params = array_filter($params);

        $match = clone($route);
        $match->params = $params;

        return $match;
    }
    
}