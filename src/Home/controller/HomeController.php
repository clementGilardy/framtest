<?php
class HomeController extends Controller {
    
    public function index(){
        echo $this->render('default.html.twig', ['name' => 'zhinyz']);
    }
}