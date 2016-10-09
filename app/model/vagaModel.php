<?php

namespace catho\model;
use \catho\architecture\PDOConection;
class vagaModel{
    
    private $tabela = 'vaga';
    private $cols   = array('title', 'description', 'salario', 'cidade', 'cidadeFormated');
    
    public function __construct() {
        $this->db = new PDOConection(DB_SERVER, DB_DATABASE, DB_USER, DB_PASSWORD);
    }
    
    public function import2Database(){
        $this->createTables();
        $data = $this->getData();
        return $this->importData($data);
    }
    
            private function createTables(){
                $file = __DIR__ ."/vaga.sql";
                $str  = file_get_contents($file);
                if(false === $this->db->execute($str, false)){die("Falha ao criar tabela no banco de dados!");}
                return true;
            }
    
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
            
            private function importData($data){
                $cols   = implode("`,`", $this->cols);
                $values = implode("),\n(", $data);
                $query  = "insert ignore into $this->tabela  (`$cols`) values \n ($values); \n";
                if(false === $this->db->execute($query, false)){die("Falha ao importar para o banco de dados");}
                return true;
            }
    
}