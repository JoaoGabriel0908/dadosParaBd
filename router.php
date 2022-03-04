<?php
    /************************************************************************** 
    * Objetivo: Arquivo de rota, para segmentar as ações encaminhadas pela View
    *   (dados de um form, listagem de dados, ação para excluir ou atualizar).
    *   Esse arquivo será responsável por encaminhar as solicitações para a Controler
    * Autor: João Gabriel
    * Data: 04/03/2022
    * Versão: 1.0
    *
    ***************************************************************************/

    $action = (string) null;
    $componente = (string) null;

    // Validação para verificar se a requisicão é um POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Recebendo dados via URL para saber quem está solicitando 
        // e qual ação será realizada

        $componente = strtoupper($_GET['componente']);
        $action = $_GET['action'];
        
        // Estrutura condicional para validar quem está solicitando algo para o Router
        switch($componente){
            case 'CONTATOS';
                echo('Chamando a controller de contatos');
        break;
        }
    }

?>