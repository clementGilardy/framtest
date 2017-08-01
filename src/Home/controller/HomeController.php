<?php


class HomeController extends Controller {
    
    public function index(){
        echo $twig->render('default.html.twig', ['name' => 'zhinyz']);
    }
}