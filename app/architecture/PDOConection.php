<?php

namespace catho\architecture;
class PDOConection{
    
    private $bd_server = "", $bd_name = "", $bd_user = "", $bd_password = "";
    public function __construct($bd_server = "", $bd_name = "", $bd_user = "", $bd_password = "") {
        $this->bd_server   = $bd_server;
        $this->bd_name     = $bd_name;
        $this->bd_user     = $bd_user;
        $this->bd_password = $bd_password;
        
        if($this->bd_name == "" || $this->bd_server == "") {
            throw new \Exception(__CLASS__.": Servidor ou Banco de dados não podem ser vazios");
        }
        $this->connect();
    }
    
    public function getDbName(){
        return $this->bd_name;
    }
    
    public function getDbServer(){
        return $this->bd_server;
    }
    
    public function getDbUser(){
        return $this->bd_user;
    }
    
    private $pdo = NULL;
    public function connect(){
       $charset = defined("CHARSET")?CHARSET:'utf8';
       $charset = str_replace('-', '', $charset);
       $dsn = 'mysql:host='.$this->bd_server.';dbname='.$this->bd_name.";charset=$charset";
       try {
           
           //echo "($this->bd_user), ($this->bd_password) <br/>";
           $this->pdo = new \PDO($dsn, $this->bd_user, $this->bd_password);
           $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
           $this->pdo->exec("set names $charset");
       }catch (\Exception $e) {throw new \Exception($e->getMessage());}
       
       if(!is_object($this->pdo)) throw new \Exception(__CLASS__ . ": Não foi possível instanciar o objeto do banco de dados!");
       return true;
    }
    
    public function getConection(){
        return $this->pdo;
    }
    
    private $status = true;
    public function getStatus(){
        return $this->status;
    }
    
    public function execute(&$query, $fetch = true){
        $bd = $this->getConection();
        if(!is_object($bd)){throw new \Exception(__CLASS__ . ": Erro na conexão do banco de dados");}
        $q = $bd->prepare($query);
        $this->status = $q->execute();
        //echo("($query\n<hr/>)"); 
        if($fetch){
            return($q->fetchAll(\PDO::FETCH_ASSOC));
        }

        return $this->status;
    }
}