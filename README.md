<p align="center">
  <a href="http://www.catho.com.br">
      <img src="http://static.catho.com.br/svg/site/logoCathoB2c.svg" alt="Catho"/>
  </a>
</p>

# API Catho

## INSTALAÇÃO

É necessário que o mod rewrite do apache esteja ativo, pois utilizei urls amigáveis para acessar os controllers desta aplicação

Para instalação é necessário uma conexão com o banco de dados mysql, para isto é necessário editar o arquivo app/config/database.php e alterar os dados de conexão com o banco de dados

Para instalar as tabelas e importar os dados para o banco de dados, acesse a url /vaga/initDatabase se o arquivo database.php estiver corretamente configurado, os dados serão importados para o banco ao acessar esta url

Para rodar os testes é necessário utilizar o [PHPUnit]. Para instalar basta rodar o [composer](https://getcomposer.org/download/) basta rodar o seguinte código na linha de comando:
```bash
$ composer install
```

---

## FUNCIONAMENTO

A API desenvolvida utiliza url amigável. As seguintes URLs estão disponíveis:
### /vaga: Aceita os parâmetros get:
- text: uma string que filtrará as vagas 
- salary: um inteiro que vai filtrar as vagas com salários >= ao parâmetro enviado
- city: pesquisará as cidades que começam com os caracteres enviados. ex: city=Belo, retornará vagas de Belo horizonte, Belo Monte, Belo oriente, etc
- order: 1 = ordenará os salários do menor para o maior, qualquer outro valor retornará do maior para o menor
- page: são retornados 20 resultados por página. O parâmetro page é numérico e sempre maior ou igual a 1. Valores incorretos deste parâmetro irão retornar a página 1

Exemplos de uso:
> /vaga&page=5
> /vaga&text=Programador&order=1
> /vaga&salary=2000&page=10
> /vaga&text=Programador&salary=4000&city=Porto%20Alegre

O resultado será exibido no formato JSON.

### /vaga/initDatabase
- Fará a instalação do banco de dados se o mesmo estiver configurado corretamente no arquivo app/config/database.php
- Exibirá mensagem de erro caso ocorra algum problema
- Caso a instalação ocorra corretamente, exibirá uma mensagem de sucesso com um link para utilizar a API

---
### Testes
Neste projeto utilizei o [PHPUnit]. Para executar um teste utilize o seguinte comando:
>  phpunit tests/app/model/vagaModel.php
```

### Documentação
Neste projeto utilizei o [PHPDoc](https://phpdoc.org/docs/latest/getting-started/installing.html).
Acesse a url 
> /docs/
para ver a documentação