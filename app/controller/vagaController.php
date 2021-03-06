<?php

namespace catho\controller;

/**
 * All actions involved with vaga's is called by this class
 * @package catho\controller
 * @category controller
 * @author thompson
 */
class vagaController implements \catho\architecture\controllerInterface{
    
    /**
     * contain url's parameters used to call internal actions
     * @var type 
     */
    private $params = '';
    public function __construct($url){
        $this->params = $url;
        try{
            $this->model = new \catho\model\vagaModel();
        }catch (Exception $ex) {
            die("falha ao conectar ao banco de dados! Por favor edite o arquivo app/config/database.php");
        }
    }
    
    public function execute(){
        $e = explode('/', $this->params);
        $m = array_shift($e);
        $method = "{$m}Action";
        if(!method_exists($this, $method)){$method = "indexAction";}
        $this->$method();
    }
    
    /**
     * Create database's table and init database's data
     */
    private function initDatabaseAction(){
        if(false === $this->model->import2Database()){
            die("falha ao importar para o banco de dados!");
        }
        die("dados importados com sucesso!");
    }
    
    /**
     * Do the search in database 
     * @return string print a json string in screen
     */
    private function indexAction(){
        $text    = filter_input(INPUT_GET, 'text');
        $city    = filter_input(INPUT_GET, 'city');
        $salary  = filter_input(INPUT_GET, 'salary');
        $order   = filter_input(INPUT_GET, 'order');
        $page    = filter_input(INPUT_GET, 'page');
        $results = $this->model->search($page, $text, $city, $salary, $order);
        die(json_encode($results, JSON_NUMERIC_CHECK));
    }
    
    
}