<?php

namespace catho\controller;
class vagaController implements \catho\architecture\controllerInterface{
    
    private $params = '';
    public function __construct($url){$this->params = $url;}
    
    public function execute(){
        $e = explode('/', $this->params);
        $m = array_shift($e);
        $method = "{$m}Action";
        if(!method_exists($this, $method)){$method = "indexAction";}
        $this->$method();
    }
    
    private function initDatabaseAction(){
        try{
            $model = new \catho\model\vagaModel();
            if(false === $model->import2Database()){
                die("falha ao importar para o banco de dados!");
            }
            die("Banco de dados importado com sucesso! Agora acesse  <a href='/vaga'>esta url</a>");
        } catch (Exception $ex) {
            die("falha ao conectar ao banco de dados! Por favor edite o arquivo app/config/database.php");
        }
        
    }
    
    private function indexAction(){
        
    }
    
    
}