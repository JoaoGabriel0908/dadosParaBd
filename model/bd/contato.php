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

    $statusRepostas = (boolean) false;

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
        // Validação para verificar se o script sql está correto 
    if (mysqli_query($conexao, $sql)){
        
        // Validação para verificar se uma linha foi acrescentada no BD
        if(mysqli_affected_rows($conexao))
            $statusRepostas =  true;

    fecharConexaoMysql($conexao);
    return $statusRepostas;
}

}
// Função para realizar o update no BD
function uptadeContato(){

}
// Função para excluir no BD
function deleteContato($id){

    // Declaração da Variavel para utilizar no return desta função
    $statusRepostas = (boolean) false;

    // Abre a conexão com o BD
    $conexao = conexaoMySql();

    // Script para deletar um registro do BD
    $sql = "delete from tblcontatos where idcontato=".$id;

    // Valida se o script está correto, sem erro de sintaxe e executa o BD
    if(mysqli_query($conexao, $sql))
    {
        // Valida se o BD teve sucesso na execução do exscript
        if(mysqli_affected_rows($conexao))
            $statusRepostas = true;
    }
    fecharConexaoMysql($conexao);
    return $statusRepostas;
}
// Função para listar todos os contatos do BD
function selectAllContato(){

    // Abre a conexão 
    $conexao = conexaoMySql();

    //Script para listar todos os dados do Banco de dados
    $sql = "select * from tblcontatos order by idcontato desc";

    // Executa o Script para listar todos os dados do Banco de dados
    $result = mysqli_query($conexao, $sql);

    if($result)
    {
        // Mysql_fetch_assoc() - Permite converter os dados do BD
            // em um array para manipulação
        // Nesta repetição estamos, convertendo os dados do BD em um array ($rsDados),
            // além de o próprio while conseguir gerenciar a Quantidade de 
            // vezes que deverá ser feita a repetição.
            $cont = 0;
        while ($rsDados = mysqli_fetch_assoc($result))
        {
            // Cria um array com os dados do BD baseado em índice e em chave
            $arrayDados[$cont] = array(
                "id"            => $rsDados['idcontato'],
                "nome"          => $rsDados['nome'],
                "telefone"      => $rsDados['telefone'],
                "celular"       => $rsDados['celular'],
                "email"         => $rsDados['email'],
                "obs"           => $rsDados['obs']
            );
            $cont++;
        }

        // Solicita o fechamento da conexão com o Banco de Dados
        fecharConexaoMysql($conexao);

        return $arrayDados;
    }

}
// Quando manda script insert, update e delect ele pergunta se uma linha foi afetada no Banco
// O Select devolve os dados do Banco 

?>