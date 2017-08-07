<?php
class HomeController extends Controller {
    
    public function index(){
        echo $this->render('index.html.twig', ['name' => 'zhinyz']);
    }
    
    public function create(){
        echo $this->render('create.html.twig',array());
    }
    
    public function show($id){
        echo $this->render('show.html.twig',array('id'=>$id));
    }
}