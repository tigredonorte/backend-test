<?php


namespace catho\model;
use \catho\architecture\PDOConection;

/**
 * This class access and manage database's table 'vaga'
 * ANY OTHER classes in this project shouldn't access this table directly
 * only using vagaModel!
 * @package catho\model
 * @category model
 * @author thompson
 */
class vagaModel{
    
    /**
     * This variable contains the database table referred to this class
     * @var string 
     */
    private $tabela = 'vaga';
    
    /**
     * This variable contains all vaga's column name in the database
     * @var array
     */
    private $cols   = array('title', 'description', 'salario', 'cidade', 'cidadeFormated');
    
    /**
     * Instantiate new Database class
     */
    public function __construct() {
        $this->db = new PDOConection(DB_SERVER, DB_DATABASE, DB_USER, DB_PASSWORD);
    }
    
    /**
     * Create database table and import vagas.json data to database
     * Returns false if some thoubles occur otherwise returns true
     * @return boolean
     */
    public function import2Database(){
        if(false===$this->createTables()){return false;}
        $data = $this->getData();
        return $this->importData($data);
    }
    
            /**
             * Read vaga.sql schema and create it in database
             * Returns true if table is created, false otherwise
             * @return boolean 
             */
            private function createTables(){
                $file = __DIR__ ."/vaga.sql";
                $str  = file_get_contents($file);
                if(false === $this->db->execute($str, false)){
                    //die("Falha ao criar tabela no banco de dados!");
                    return false;
                }
                return true;
            }
    
            /**
             * Read vagas.json and prepare this data to be imported in mysql
             * @return array
             */
            private function getData(){
                $out  = array();
                $file = __DIR__ ."/vagas.json";
                $arr  = json_decode(file_get_contents($file), true);
                foreach($arr['docs'] as $line){
                    //como não foi especificado se uma vaga pode conter mais de uma cidade e 
                    //não há nenhum caso aonde isto ocorre, assumi que é uma vaga por cidade.
                    $temp                   = array();
                    $temp['title']          = isset($line['title'])          ? $line['title']                      :"";
                    $temp['description']    = isset($line['description'])    ? $line['description']                :"";
                    $temp['salario']        = isset($line['salario'])        ? $line['salario']                    :"";
                    $temp['cidade']         = isset($line['cidade'])         ? array_shift($line['cidade'])        :"";
                    $temp['cidadeFormated'] = isset($line['cidadeFormated']) ? array_shift($line['cidadeFormated']):"";
                    foreach($temp as &$v){
                        $v = str_replace(array("'"), array('"'), $v);
                        if(!is_numeric($v)){$v = " '$v' ";}
                    }
                    $out[] = implode(",",$temp);
                }
                return $out;
            }
            
            /**
             * Import an data array into mysql table (vaga)
             * Returns true if correctly imported, false otherwise
             * @param array $data
             * @return boolean
             */
            private function importData($data){
                $cols   = implode("`,`", $this->cols);
                $values = implode("),\n(", $data);
                $query  = "insert ignore into $this->tabela  (`$cols`) values \n ($values); \n";
                if(false === $this->db->execute($query, false)){die("Falha ao importar para o banco de dados");}
                return true;
            }
    
    
    /**
     * Make search in database. This search can contains some text to be filtered,
     * a minimun salary, a city. The result can be ordered ASC OR DESC in relation of salary.
     * @param int $page The page's number
     * @param string $text the text to be founded in description field and title field
     * @param string $city the city name
     * @param int $salary the minimum salary
     * @param bool $orderAsc if this params is true order is ASC, DESC otherwise
     * @return array
     */
    public function search($page, $text, $city, $salary, $orderAsc = false){
        if(!is_numeric($page)){$page = 0;}
        $page--;
        if($page < 0){$page = 0;}
        $limit  = 20;
        $offset = $page *$limit;
        
        $where = $this->getQuery($text, $city, $salary);
        if($where != ""){$where = "WHERE $where";}
        
        $order = ($orderAsc)?"ASC":"DESC";
        $query   = "SELECT * FROM `$this->tabela` $where ORDER BY `salario` $order LIMIT $limit OFFSET $offset";
        return $this->db->execute($query, true);
    }
    
            /**
             * Return a prepared where statement
             * @param string $text the text to be founded in description field and title field
             * @param string $city the city name
             * @param int $salary the minimum salary
             * @return string
             */
            private function getQuery($text, $city, $salary){
                $q = array();
                if($text   != ""){$q[] = "(`title` LIKE '%$text%' OR `description` LIKE '%$text%')";}
                if($city   != ""){
                    $city = str_replace("'", '"', $city);
                    $q[]  = "`cidade` LIKE '$city%'";
                }
                if($salary != "" && is_numeric($salary)){$q[] = "`salario` >= $salary";}
                return implode(" AND ", $q);
            }
}