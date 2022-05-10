<?php
/************************************************************** 
* Objetivo: Arquivo responsável pela manipulação de dados de estados
*   Obs(Este arquivo fará a ponte entre a View e a Model)
* Autor: João Gabriel 
* Data: 10/05/2022
* Versão: 1.0
***************************************************************/

// Import do arquivo de configurção do projeto
require_once('modulo/config.php');

// Função para solicitar os dados da model e 
// encaminhar a lista de estados para View
function listarEstado (){

    // Import do arquivo que vai buscar os dados no BD
    require_once('model/bd/estado.php');

    // Chama a função que vai buscar os dados no BD
    $dados = selectAllEstados();

    // Se não ter conteúdo ele irá retornar false
    if(!empty($dados))
        return $dados;
    else
        return false;
}

?>