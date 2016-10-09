<?php

namespace catho\controller;

class instructionsController implements \catho\architecture\controllerInterface{
    
    public function __construct($url){$this->params = $url;}
    
    public function execute(){
        $view = "/app/view/instructionsView.phtml";
        require_once $view;
    }
}