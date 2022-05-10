<?php
/************************************************************** 
* Objetivo: Arquivo responsável por manipular os dados dentro do BD
*           (select)
* Autor: João Gabriel 
* Data: 10/05/2022
* Versão: 1.0
***************************************************************/

// Import do arquivo que estabelece a conexão com BD
require_once('conexaoMySql.php');

// Função para listar todos os contatos do BD
function selectAllEstados(){

    // Abre a conexão 
    $conexao = conexaoMySql();

    //Script para listar todos os dados do Banco de dados
    $sql = "select * from tblestados order by nome asc";

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
                "idestado"          => $rsDados['idestado'],
                "nome"              => $rsDados['nome'],
                "sigla"             => $rsDados['sigla'],
            );
            $cont++;
        }

        // Solicita o fechamento da conexão com o Banco de Dados
        fecharConexaoMysql($conexao);

        if(isset($arrayDados)){
            return $arrayDados;
        } else 
            return false;
    }

}


?>