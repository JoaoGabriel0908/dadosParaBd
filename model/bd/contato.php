<?php
/************************************************************** 
* Objetivo: Arquivo responsável pela por manipular os dados dentro
*   banco de dados (insert, update, select e delete)
* Autor: João Gabriel 
* Data: 11/03/2022
* Versão: 1.0
***************************************************************/

// Import do arquivo que estabelece a conexão com BD
require_once('conexaoMySql.php');

// Função para realizar o insert no BD
function insertContato($dadosContato){

    // Abre a conexão com o BD
    $conexao = conexaoMySql();

    // Montando o script para enviar para o BD
    $sql = "insert into tblcontatos
            (nome,
             telefone,
             celular,
             email,
             obs)
        values
            ('".$dadosContato['nome']."',
            '".$dadosContato['telefone']."',
            '".$dadosContato['celular']."',
            '".$dadosContato['email']."',
            '".$dadosContato['obs']."');";

            echo($sql);

    // Comando que executa o script no banco de dados
    mysqli_query($conexao, $sql);
}
// Função para realizar o update no BD
function uptadeContato(){

}
// Função para excluir no BD
function deleteContato(){

}
// Função para listar todos os contatos do BD
function selectAllContato(){

}
?>