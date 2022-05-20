<?php
/************************************************************** 
* Objetivo: Arquivo responsável pela criação de variaveis e constantes dos projetos
* Autor: João Gabriel 
* Data: 25/04/2022
* Versão: 1.0
***************************************************************/

/******************** VARIAVEIS E CONTANTES GLOBAIS DO PROJETO ********************/ 

// Limitação de 5mb de uploads de imagens
const MAX_FILE_UPLOAD = 5120;

const EXT_FILE_UPLOAD = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

const DIRETORIO_FILE_UPLOAD = "arquivos/";

// Define o caminho padrão do servidor
define ('SRC', $_SERVER['DOCUMENT_ROOT'].'/joao/Aula07conexao-com-banco/');

/******************** FUNÇÕES GLOBAIS PARA O PROJETO ********************/

// Funções para converter um array em um formato JSON
function createJSON($arrayDados) {

    // Validação para tratar array sem informação
    if(!empty($arrayDados)) 
    {
        // Configura o padrão da conversão para o formato JSON
        header('Content-Type: application/json');
        $dadosJSON = json_encode($arrayDados);

        // Função que converte um array para JSON
        // json_encode()

        // Função que converte de JSON para array
        // json_decode()

        return $dadosJSON;
    } else {
        return false;
    }
}

?>