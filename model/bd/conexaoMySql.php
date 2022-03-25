<?php
/****************************************************************
 * Objetivo: Arquivo para criar a conexão com o BD Mysql
 * Auto: Marcel
 * Data 25/02/2022
 * Versão: 1.0
******************************************************************/

// Constantes para estabelecer a conexão com BD (local do BD, usuário, senha e database)
const SERVER = 'localhost';
const USER = 'root';
const PASSWORD = 'bcd127';
const DATABASE = 'dbcontatos';

// Abre a conexão com o BD Mysql
function conexaoMySql(){

    $conexao = array();

    // Se a conexão for estabelecida com o BD, iremos ter um array de dados sobre a conexão
    $conexao = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

    // Validação para verificar se a conexão foi realizada com sucesso
    if($conexao)
        return $conexao;
    else 
        return false;
}

// Fecha a conexão com o BD Mysql
function fecharConexaoMysql($conexao){
    mysqli_close($conexao);
}

/*
    Existem 3 formas de criar a conexão com banco de dados BD Mysql

        mysql_connect() - versão antiga do php de fazer conexão com BD 
            (Não oferece performace e segurança)

        mysqli_connect() - versão mais atualizado do PHP de fazer a conexão com BD
            (Permite ser utilizada para programação estruturada e POO)

        PDO() - Uma versão mais completa e eficiente para conexão com BD
            (É indicada pela segurança e POO)
*/

?>