<?php

namespace catho\architecture;


/**
 * This class is used to connect to mysql databases and execute any query in theese database.
 * This class is not mysql injection free. Every model must verify and protect by theese vulnerability
 * @package catho\architecture
 * @category database
 * @author thompson
 */
class PDOConection{
    
    /**
     * This var store database server's name. 
     * @var string 
     */
    private $bd_server = "";
    
    /**
     * This var store database's name. 
     * @var string 
     */
    private $bd_name = "";
    
    /**
     * This var store database's user. 
     * @var string 
     */
    private $bd_user = "";
    
    /**
     * This var store database's password. 
     * @var string 
     */
    private $bd_password = "";
    
    /**
     * Stores a \PDO object instance
     * @var \PDO 
     */
    private $pdo = NULL;
    
    /**
     * Stores the last operation status
     * @var bool 
     */
    private $status = true;
    
    /**
     * Save database's data and connect to database
     * @param string $bd_server database's server name to be stored in $bd_server variable
     * @param string $bd_name database's name to be stored in $bd_name variable
     * @param string $bd_user database's user to be stored in $bd_user variable
     * @param string $bd_password database's password to be stored in $bd_password variable
     * @throws \Exception if database name is empty
     */
    public function __construct($bd_server, $bd_name, $bd_user = "", $bd_password = "") {
        $this->bd_server   = $bd_server;
        $this->bd_name     = $bd_name;
        $this->bd_user     = $bd_user;
        $this->bd_password = $bd_password;
        
        if($this->bd_name == "" || $this->bd_server == "") {
            throw new \Exception(__CLASS__.": Servidor ou Banco de dados não podem ser vazios");
        }
        $this->connect();
    }
    
    /**
     * returns database's name
     * @return string
     */
    public function getDbName(){
        return $this->bd_name;
    }
    
    /**
     * returns database's server name
     * @return string
     */
    public function getDbServer(){
        return $this->bd_server;
    }
    
    /**
     * returns database user
     * @return string
     */
    public function getDbUser(){
        return $this->bd_user;
    }
    
    /**
     * Connect to database
     * @return boolean true if connection successfull, throws exception otherwise
     * @throws \Exception if can't connect to database
     */
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
    
    /**
     * Returns a PDO Instance
     * @return \PDO
     */
    public function getConection(){
        return $this->pdo;
    }
    
    /**
     * Returns the last operation status
     * @return bool
     */
    public function getStatus(){
        return $this->status;
    }
    
    /**
     * Execute a mysql query in database
     * @param string $query
     * @param bool $fetch
     * @return mixed if $fetch = false returns true if query executed or false if some throuble occurs
     * if $fetch = true returns an array
     * @throws \Exception if database is not connected
     */
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
