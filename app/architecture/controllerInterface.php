<?php

namespace catho\architecture;

/**
 * This interface must be implemented by all controllers in this aplications.
 * @package catho\architecture
 * @category controller
 * @author thompson
 */
interface controllerInterface{
    
    /**
     * This method receive url param to call internal actions
     * @param string $url this paramether should be manipulated by controllers 
     * to call internal actions or do everythink else the class need
     */
    public function __construct($url);
    
    /**
     * This method is the only one external called method
     * If some action must be executed, the execute method should filter $url statement to do this
     */
    public function execute();
    
}