<?php
    /************************************************************************** 
    * Objetivo: Arquivo de rota, para segmentar as ações encaminhadas pela View
    *   (dados de um form, listagem de dados, ação para excluir ou atualizar).
    *   Esse arquivo será responsável por encaminhar as solicitações para a Controler
    * Autor: João Gabriel
    * Data: 04/03/2022
    * Versão: 1.0
    ***************************************************************************/

    $action = (string) null;
    $componente = (string) null;

    // Validação para verificar se a requisicão é um POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'){

        // Recebendo dados via URL para saber quem está solicitando 
        // e qual ação será realizada
        $componente = strtoupper($_GET['componente']);
        $action = strtoupper($_GET['action']);

        // echo($componente);
        // Comando para forçar a parada do programa
        // die;
        
        // Estrutura condicional para validar quem está solicitando algo para o Router
        switch($componente)
        {
            case 'CONTATOS';
                // Import da controller contatos
                require_once('./controller/controllerContatos.php');

                // Validação para verificar o tipo de ação que será realizada
                if($action == 'INSERIR')
                {

                    // Chama a função de inserir na controller 
                    $resposta = inserirContato($_POST);

                    // Valida o tipo de dado que retornou
                    if(is_bool($resposta)) // Se for boleano:
                    {
                        // Verifica se o retorno foi verdadeiro
                        if($resposta)

                        echo(" <script>
                                 alert('Registro inserindo com sucesso!');
                                 window.location.href = 'index.php'; 
                            </script> ");
                        }

                        // Se o retorno for array significa que houve um erro no processo de inserção
                        elseif(is_array($resposta))
                        echo("<script>
                                alert('".$resposta['message']."');
                                window.history.back();
                            </script>");


                // Ação de deletar
                }elseif($action == 'DELETAR')
                {
                        // Recebe o id do registro que deverá ser excluído,
                        // que foi enviado pela URL do link da imagem do exluir
                        // que foi adicionado na Index.
                        $idContato = $_GET['id'];

                        // Chama a função de excluir na controller
                        $resposta = excluirContato($idContato);

                        if(is_bool($resposta))
                        {
                            if($resposta){
                                echo(" <script>
                                 alert('Registro exluído com sucesso!');
                                 window.location.href = 'index.php'; 
                            </script> ");
                            }
                        }elseif(is_array($resposta))
                        {
                            echo("<script>
                                alert('".$resposta['message']."');
                                window.history.back();
                            </script>");
                        }
                }elseif($action == 'BUSCAR')
                {
                        // Recebe o id do registro que deverá ser excluído,
                        // que foi enviado pela URL do link da imagem do exluir
                        // que foi adicionado na Index.
                        $idContato = $_GET['id'];

                        // Chama a função de excluir na controller
                        $dados = buscarContato($idContato);

                        // Ativa a utilização de variavel de sessão no servidor
                        session_start();

                        // Guarda em uma variavel de sessão os dados que o BD retornou 
                        // para a busca do ID
                            // Obs: Essa variável de sessão será utilizada na index.php,
                            // para colocar os dados na caixa de texto 
                        $_SESSION['dadosContato'] = $dados;
                        
                    /* Utilizando o header também poderemos chamar a index.php,
                    Porém haverá um ação de carregamento no navegador
                    (piscando a tela)*/
                    // header('location: index.php');

                    // Utilizando o require iremos apenas importar a tela da index.php,
                    // Assim não havendo um novo carregamento da página
                    require_once('index.php');

                }elseif($action == 'EDITAR')
                {
                    // Recebe o id que foi encaminhado no action do form pela URL
                    $idContato = $_GET['id'];

                    // Chama a função de editar na controller 
                    $resposta = atualizarContato($_POST, $idContato);

                    // Valida o tipo de dado que retornou
                    if(is_bool($resposta)) // Se for boleano:
                    {
                        // Verifica se o retorno foi verdadeiro
                        if($resposta)

                        echo(" <script>
                                 alert('Registro atualizado com sucesso!');
                                 window.location.href = 'index.php'; 
                            </script> ");
                        }

                        // Se o retorno for array significa que houve um erro no processo de inserção
                        elseif(is_array($resposta))
                        echo("<script>
                                alert('".$resposta['message']."');
                                window.history.back();
                            </script>");
                }
            break;
        }
    }

    // Quando navegamos de uma tela para outra, os dados gravados por uma variavel somem
    // Variavel de seção: uma Variavel de seção é criada no próprio navegador
    // e a qualquer momento podemos recuperar essa variavel
?>

