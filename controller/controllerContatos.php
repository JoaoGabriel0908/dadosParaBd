<?php
/************************************************************** 
* Objetivo: Arquivo responsável pela manipulação de dados de contato
*   Obs(Este arquivo fará a ponte entre a View e a Model)
* Autor: João Gabriel 
* Data: 04/03/2022
* Versão: 1.0
***************************************************************/

// Função para receber dados da View e encaminhar para a Model (Inserir)
function inserirContato ($dadosContatos){

    // Validação para verificar se o objeto esta vazio 
    if(!empty($dadosContatos)){

        // Validação de caixa vazia dos elementos nome,
        // celular e email, pois são campos obrigatórios no BD 
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
            {
                // Criação do array de dados que será encaminhado a model
                // para inserir no banco de dados, é importante
                // criar este array conforme as necessidades de manipulação do BD
                // OBS: Criar as chaves do array conforme os nomes dos atributos do BD
                $arrayDados = array(
                    "nome"          => $dadosContatos['txtNome'],
                    "telefone"      => $dadosContatos['txtTelefone'],
                    "celular"       => $dadosContatos['txtCelular'],
                    "email"         => $dadosContatos['txtEmail'],
                    "obs"           => $dadosContatos['txtObs'],
                );
                // Require do arquivo da model que faz a conexão direta com o BD
                require_once('./model/bd/contato.php');
                // Função que recebe o array e passa para o dadosContatos
                insertContato($arrayDados);
            }
        else
            echo('Dados incompletos');
    }
}

// Função para receber dados da View e encaminhar para a Model (Atualizar)
function atualizarContato (){

}

// Função para realizar a exclusão de um contato
function excluirContato (){

}

// Função para solicitar os dados da model e 
// encaminhar a lista de contatos para View
function listarContato (){

}

?>