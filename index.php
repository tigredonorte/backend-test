<?php

//carrega o autoload
require_once './vendor/autoload.php';
require_once './app/config/database.php';

//descobre qual controller será acessado
$url = filter_input(INPUT_GET, 'url');
if(!$url){$url = 'instructions';}
$e    = explode('/', $url);
$temp = array_shift($e);
if($temp === ""){$temp = 'instructions';}
$controller = "catho\controller\\{$temp}Controller";

//separa qual action será acessada 
$urlParams = implode('/',$e);

//executa a action do controller
try{
    $obj = new $controller($urlParams);
    $obj->execute();
} catch (Exception $ex) {
    die("Falha ao executar a url $url!<br/>".$ex->getMessage());
}
