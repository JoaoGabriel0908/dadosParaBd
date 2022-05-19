<?php
/***************************************************************
* Objetivo: Arquivo principal da API que irá receber a URL 
*   requisitada e redirecionar para as APIs (router)
* Data: 19/05/2022
* Autor: João Gabriel
* Versão: 1.0
***************************************************************/

// Permite ativar quais endereços de sites que poderão fazer requisições da API (* Libera todos os sites)
header('Access-Control-Allow-Origin: *');

// Permite ativar os métodos do protocolo HTTP que irão requisitar API
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// Permite ativar o content-Types das requisições (Formato de dados que será utilizada (JSON, XML, FORK/DATA, etc...))
header('Access-Control-Allow-Header: Content-Type');

// Permite liberar quais content-Types serão utilizadas na API
header('Content-Type: application/json');

// Recebe a Url digitada na requisição
$urlHTTP = (string) $_GET['url'];

// Converte a url requisitada em um array para dividir as opções de busca que é separada pela barra (/)
$url = explode('/', $urlHTTP);

// Verifica qual a API será encaminhada a requisiçõa (contatos, estados etc...)
switch(strtoupper($url[0])) {
    case 'CONTATOS';
        require_once('contatosAPI/index.php');
    break;
    
    case 'ESTADOS';
        require_once('estadosAPI/index.php');
    break;
}

?>