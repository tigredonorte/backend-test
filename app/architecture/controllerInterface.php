<?php

namespace catho\architecture;

interface controllerInterface{
    
    public function __construct($url);
    
    public function execute();
    
}